<?php


namespace App\Services;

class TJMG {

    public function generateXmlResponse($stampAmount)
    {
        $xml = '<Selos>';
        for ($i = 0; $i < $stampAmount; $i++) {
            $xml .= '<Selo>';
            $xml .= '<CodigoUnico>';
            $xml .= 'AAA' . sprintf("%'.05d", $i);
            $xml .= '</CodigoUnico>';
            $xml .= '<CodigoSeguranca>' . rand('1000000000000000', '9999999999999999') . '</CodigoSeguranca>';
            $xml .= '</Selo>';
        }
        $xml .= '</Selos>';

        return $this->generateStampRequestXmlFile($xml);
    }

    public function generateStampRequestXmlFile($content)
    {

        $fileContent = '<?xml version="1.0" encoding="ISO-8859-1"?>' . $content;
        $fileName = 'stampbatch.xml';
        $fileRealPath = storage_path('app' . DIRECTORY_SEPARATOR . $fileName);

        $fp = fopen($fileRealPath, 'w+');
        fwrite($fp, $fileContent);
        fclose($fp);

        return $fileRealPath;


    }

}