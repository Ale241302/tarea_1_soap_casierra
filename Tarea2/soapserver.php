<?php
require 'nusoap.php';

$namespace = "http://localhost/Tarea2";

$server = new soap_server();
$server->configureWSDL("WSDLTST", $namespace);
$server->soap_defencoding = 'UTF-8';
$server->wsdl->schemaTargetNamespace = $namespace;

function conteo($nomb) {
    $nomb = strlen($nomb);
    return $nomb;
}

function suma($num1, $num2) {
    $num = $num1 + $num2;
    return $num;
}

function resta($num1, $num2) {
    $num = $num1 - $num2;
    return $num;
}

function multiplicacion($num1, $num2) {
    $num = $num1 * $num2;
    return $num;
}

function division($num1, $num2) {                                                             
    if($num2 != 0) {
        $num = $num1 / $num2;
        return $num;
    } else {
        throw new SoapFault("Server", "No se puede dividir por cero.");
    }
}

$server->register(
    'conteo',
    array("nomb" => "xsd:string"),
    array('return'=> 'xsd:int'), // Cambiamos el tipo de retorno a xsd:int
    $namespace,
    false,
    'rpc',
    'encoded',
    'Función para contar caracteres'
);

$server->register(
    'suma',
    array("num1" => "xsd:float", "num2" => "xsd:float"), 
    array('return'=> 'xsd:float'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Función para sumar'
);

$server->register(
    'resta',
    array("num1" => "xsd:float", "num2" => "xsd:float"),
    array('return'=> 'xsd:float'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Función para restar'
);

$server->register(
    'division',
    array("num1" => "xsd:float", "num2" => "xsd:float"),
    array('return'=> 'xsd:float'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Función para dividir'
);

$server->register(
    'multiplicacion',
    array("num1" => "xsd:float", "num2" => "xsd:float"),
    array('return'=> 'xsd:float'),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Función para multiplicar'
);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
}

$server->service($HTTP_RAW_POST_DATA);
?>