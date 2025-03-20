<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

if($method === 'GET'){
    if(isset($_GET['id'])){
        require_once 'read_single.php';
    }
    else{
        
        require_once 'read.php';
    }
}

if($method === 'POST'){
    require_once 'create.php';
}

if($method === 'PUT'){
    require_once 'update.php';
}

if($method === 'DELETE'){
    require_once 'delete.php';
}