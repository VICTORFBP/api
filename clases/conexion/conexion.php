<?php

class conexion{

    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    private $conexion;

    function __construct(){
        $listadatos = $this->datosConexion();
        foreach($listadatos as $key => $value){
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }
        $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
        if($this->conexion->connect_errno){
            echo "Va mal la conexión";
        }
    }
    
    private function datosConexion(){
        $direccion = dirname(__FILE__);
        $jsondata = file_get_contents($direccion . "/" . "config");
        return json_decode($jsondata, true); # En esta linea convierto el json en un array para manejarlo mejor
    }

    private function convertirUTF8($array){
        array_walk_recursive($array,function(&$item,$key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    public function obtenerDatos($sqlstr){
        $results = $this->conexion->query($sqlstr);
        $resultArray = array();
        foreach($results as $key){
            $resultArray[] = $key;
        }
        return $this->convertirUTF8($resultArray);
    }
    public function nonQuery($sqlstr){
        $results = $this -> conexion-> query($sqlstr);
        return $this -> conexion -> affected_rows;
    }


//para insertar 
    public function nonQueryid($sqlstr){
        $results = $this -> conexion -> query ($sqlstr);
        $filas = $this ->conexion -> affected_rows;
        if ($filas >=1) {
            return $this -> conexion -> insert_id;
        }
        return 0;

    }
    protected function encriptar ($string){
        return md5($string);
    }
}

?>