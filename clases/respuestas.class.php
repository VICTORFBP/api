<?php
class respuestas {
    public $response = [
        'status' => "ok",
        "result" => array()


    ];

    public function error_405(){
        $this -> response ['status'] = "error";
        $this -> response [ 'result'] = array(
            "error id " => "405",
            "error_msg" => "metodo no permitido"
        );
        return $this -> response;
    }

    public function error_400(){
        $this -> response ['status'] = "error";
        $this -> response [ 'result'] = array(
            "error id " => "405",
            "error_msg" => "Datos enviados incompletos o con formatos incorrectos"
        );
        return $this -> response;
    }

    public function error_401($valor = "No autorizado"){
        $this -> response ['status'] = "error";
        $this -> response [ 'result'] = array(
            "error id " => "401",
            "error_msg" => $valor
        );
        return $this -> response;
    }

    
    public function error_500( $valor = "error intento del servidor"){
        $this -> response ['status'] = "error";
        $this -> response [ 'result'] = array(
            "error id " => "405",
            "error_msg" => "Datos enviados incompletos o con formatos incorrectos"
        );
        return $this -> response;
    }

    public function error_200($valor = "Datos incorrectos"){
        $this -> response ['status'] = "error";
        $this -> response [ 'result'] = array(
            "error id " => "405",
            "error_msg" => $valor
        );
        return $this -> response;
    }

    


}

?>