<?php


class client{


    public function __construct()
    {
        $params = array('location' => 'http://127.0.0.1:8000/webservice/server.php',
            'uri'=>'urn://webservice/server.php',
            'trace'=>1);
        $this->instance = new SoapClient(NULL, $params);
    }

    public function getInfo($id_array)
    {
        return $this->instance->__soapCall('getName', $id_array);
    }

}

$client = new client();
