<?php
class Calculadora {
    public function conteo($nomb) {
        $nomb = strlen($nomb);
        return $nomb;
    }

    public function suma($num1, $num2) {
        return $num1 + $num2;
    }

    public function resta($num1, $num2) {
        return $num1 - $num2;
    }

    public function multiplicacion($num1, $num2) {
        return $num1 * $num2;
    }

    public function division($num1, $num2) {                                                             
        if($num2 != 0) {
            return $num1 / $num2;
        } else {
            throw new SoapFault("Server", "No se puede dividir por cero.");
        }
    }
}

?>