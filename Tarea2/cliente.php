<?php
require 'nusoap.php';
$namespace = "http://localhost/Tarea2";
$serverScript = 'soapserver.php';
$metodoALlamar = $_POST['funcion'];
$client = new nusoap_client("$namespace/$serverScript?wsdl", 'wsdl');

if ($metodoALlamar == 'conteo') {
    $result = $client->call(
        "$metodoALlamar",
        array('nomb' => $_POST['nomb']),
        "$namespace/$serverScript",
        "$namespace/$serverScript/$metodoALlamar"
    );

    echo "Resultado: $result";
}
if ($metodoALlamar == 'sumar') {
    $result = $client->call(
        "$metodoALlamar",
        array('num1' => $_POST['num1'], 'num2' => $_POST['num2']),
        "uri:$namespace/$serverScript",
        "uri:$namespace/$serverScript/$metodoALlamar"
    );
    echo "Resultado: $result";
}
if ($metodoALlamar == 'resta') {
    $result = $client->call(
        "$metodoALlamar",
        array('num1' => $_POST['num1'], 'num2' => $_POST['num2']),
        "uri:$namespace/$serverScript",
        "uri:$namespace/$serverScript/$metodoALlamar"
    );
    echo "Resultado: $result";
}
if ($metodoALlamar == 'multiplicacion') {
    $result = $client->call(
        "$metodoALlamar",
        array('num1' => $_POST['num1'], 'num2' => $_POST['num2']),
        "uri:$namespace/$serverScript",
        "uri:$namespace/$serverScript/$metodoALlamar"
    );
    echo "Resultado: $result";
}
if ($metodoALlamar == 'division') {
    $result = $client->call(
        "$metodoALlamar",
        array('num1' => $_POST['num1'], 'num2' => $_POST['num2']),
        "uri:$namespace/$serverScript",
        "uri:$namespace/$serverScript/$metodoALlamar"
    );
    echo "Resultado: $result";
}
echo "<br><br><a href='form.html'>Volver a formulario</a>";

?>