<?php
include('./client.php');
$id_array = array('id'=> '1');

$client = new client();
$client->getInfo($id_array);
