<?php

namespace App\XML;

use App\Models\Stamps;

class GenerateStamps
{

    public function generateStampsToSendToCourt($stamps)
    {
        $xml = $this->header();
        $xml .= $this->prepareStamp($stamps);
        $xml .= '</Selos>';

        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . uniqid('stamps') . '.xml');
        $fp = fopen($filePath, 'w+');
        fwrite($fp, $xml);
        fclose($fp);

        return $filePath;

    }

    public function header()
    {
        $fileHeader = '<?xml version="1.0" encoding="ISO-8859-1"?>';

        $fileHeader .= '<Selos xmlns="http://notarial.tjmg.jus.br" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://selos.tjmg.jus.br atospraticados.xsd">';
        $fileHeader .= '<CodigoServentia>Id da Serventia</CodigoServentia>';

        return $fileHeader;

    }

    public function prepareStamp($stamps)
    {
        $stampContent = '';

        foreach($stamps as $stamp){
            $stampContent .= '<Selo>';
            $stampContent .= '<CodigoSequencialUnico>' . $stamp->stamp_unique_code . '</CodigoSequencialUnico>';
            $stampContent .= '<CodigoDeSeguranca>' . $stamp->stamp_code .'</CodigoDeSeguranca>';
            $stampContent .= '<SeloConsulta>' . $stamp->stamp_unique_code . '</SeloConsulta>';
            $stampContent .= '<DataUtilizacao>' . $stamp->updated_at . '</DataUtilizacao>';
            $stampContent .= '<Ato>';
            $stampContent .= '<CodigoFiscalDoAto>7201</CodigoFiscalDoAto>';
            $stampContent .= '<DataSolicitacao>01-04-2021</DataSolicitacao>';
            $stampContent .= '<DataPraticaAto>03-04-2021</DataPraticaAto>';
            $stampContent .= '<ComposicaoAto>';
            $stampContent .= '<TipoTributacao>1</TipoTributacao>';
            $stampContent .= '<ValorEmolumento>21.36</ValorEmolumento>';
            $stampContent .= '<ValorTFJ>4.31</ValorTFJ>';
            $stampContent .= '<ValorFinalUsuario>25.67</ValorFinalUsuario>';
            $stampContent .= '<Protocolo/>';
            $stampContent .= '<TipoLivro>402</TipoLivro>';
            $stampContent .= '<OrdemSequencial>M</OrdemSequencial>';
            $stampContent .= '<Folha>25 verso</Folha>';
            $stampContent .= '<TipoDeEscrituracao/>';
            $stampContent .= '<NumeroDeOrdem/>';
            $stampContent .= '<Etiqueta/>';
            $stampContent .= '<Responsavel/>';
            $stampContent .= '<Qualificacao/>';
            $stampContent .= '<Mac/>';
            $stampContent .= '<NumeroProcesso/>';
            $stampContent .= '<RegimeBens/>';
            $stampContent .= '<Validade/>';
            $stampContent .= '</ComposicaoAto>';
            $stampContent .= '</Ato>';
            $stampContent .= '</Selo>';
        }

        return $stampContent;

    }
}
