<?php

class server{

    public function __construct()
    {


    }

    public function getName($id_array)
    {
        return 'Sam';
    }
}

$params = array('uri'=> 'webservice/server.php');
$server = new SoapServer(NULL, $params);
$server->setClass('server');
$server->handle();

