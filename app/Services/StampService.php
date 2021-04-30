<?php

namespace App\Services;

use App\Models\Stamps;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\TJMG;
use RuntimeException;
use Selective\XmlDSig\DigestAlgorithmType;
use Selective\XmlDSig\XmlSigner;
use Whoops\Run;
use App\XML\GenerateStamps;

class StampService
{

    private $TJMG;

    private $xmlGenerator;

    public function __construct(TJMG $tjmg, GenerateStamps $xmlGenerator)
    {
      $this->TJMG = $tjmg;
      $this->xmlGenerator = $xmlGenerator;
    }
    public function importStampsFromFile(Request $request)
    {

        $stampAmount = $request['stampAmount'];

        //Lançamento de exceção quando a quantidade solicitada de selos não for a mínima exigida pelo TJ
        if ($stampAmount < 100) {
            Throw new RuntimeException('Quantidade de selos solicitada insuficiente', 1);
        }

        // -------------------------------------------------------------------------
        //Gerador de arquivo xml para simular resposta do TJ à solicitacao de selos
        $filePath = $this->TJMG->generateXmlResponse($stampAmount);
        // -------------------------------------------------------------------------

        $fileContent = file_get_contents($filePath);
        $fileName = uniqid('stamps');

        $fileRealPath = storage_path('app' . DIRECTORY_SEPARATOR . 'stamps' . DIRECTORY_SEPARATOR . $fileName . '.xml');

        $fp = fopen($fileRealPath, 'w+');
        fwrite($fp, $fileContent);
        fclose($fp);
        

        //Tratamento para arquivo não encontrado
        if (!file_exists($fileRealPath)){
            Throw new RuntimeException('Arquivo não encontrado', 2);
        }

        $xml = simplexml_load_file($fileRealPath);
        
        foreach($xml as $xmlContent){

            $stamp = Stamps::where('stamp_code', $xmlContent->CodigoSeguranca)->first();

            if ($stamp){
                Throw new RuntimeException('Existem selos no arquivo ja registrados no banco', '1');
            }
            $stamp = new Stamps;

            $stamp->act_id = 1;
            $stamp->act_code = 2122;
            $stamp->company_id = 2495;
            $stamp->state_id = 11;
            $stamp->stamp_unique_code = (string) $xmlContent->CodigoUnico;
            $stamp->stamp_code = (string) $xmlContent->CodigoSeguranca;
            $stamp->created_at = Carbon::now()->format('Y-m-d H:i:s');
            $stamp->save();

        }

        return response('Selos importados para o banco');
    }


    public function prepareStampToSendToCourt()
    {
        $stamps = DB::table('stamps')->where('is_sent_tj', '=', false)->get();
        $file = $this->xmlGenerator->generateStampsToSendToCourt($stamps);

        // XML SIGNER
        $xmlsigner = new XmlSigner();
        $xmlsigner->loadPrivateKeyFile(storage_path('app' . DIRECTORY_SEPARATOR . 'private.pem'), 'nocartorio');
        $xmlsigner->signXmlFile($file, $file, DigestAlgorithmType::SHA512);
        


    }
}
