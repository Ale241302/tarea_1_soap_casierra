<?php
require 'nusoap.php';

// Incluye el archivo que contiene las funciones de operaciones
require 'operaciones.php';

$Serve = new nusoap_server();
$Serve->configureWSDL("Calculadora", "http://localhost/tarea1_soap_casierra/soapserver.php?wsdl");
$Serve->register(
    "conteo",
    array("nomb" => "xsd:string"),
    array("return" => "xsd:string")
);
$Serve->register(
    "suma",
    array("num1" => "xsd:float", "num2" => "xsd:float"), 
    array("return" => "xsd:float")
);
$Serve->register(
    "resta",
    array("num1" => "xsd:float", "num2" => "xsd:float"),
    array("return" => "xsd:float")
);
$Serve->register(
    "division",
    array("num1" => "xsd:float", "num2" => "xsd:float"),
    array("return" => "xsd:float")
);
$Serve->register(
    "multiplicacion",
    array("num1" => "xsd:float", "num2" => "xsd:float"),
    array("return" => "xsd:float")
);
$Serve->service(file_get_contents("php://input"));
?>
