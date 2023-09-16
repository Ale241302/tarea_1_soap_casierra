<?php
class Calculadora {
    public function conteo($nomb) {
        $nomb = strlen($nomb);
        return $nomb;
    }

    public function suma($num1, $num2) {
        $num = $num1 + $num2;
        return $num;
    }

    public function resta($num1, $num2) {
        $num = $num1 - $num2;
        return $num;
    }

    public function multiplicacion($num1, $num2) {
        $num = $num1 * $num2;
        return $num;
    }

    public function division($num1, $num2) {                                                             
        if($num2 != 0) {
            $num = $num1 / $num2;
            return $num;
        } else {
            throw new SoapFault("Server", "No se puede dividir por cero.");
        }
    }
}
?>