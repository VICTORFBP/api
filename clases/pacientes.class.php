<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class pacientes extends conexion{
    private $pacienteId= "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal= "";
    private $genero = "";
    private $telefono = "";
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";
    private $table = "pacientes";
    private $token = "";

    //dba2641a11b462d567b7fcd3e37ffe43

    public function listaPacientes($pagina = 1){
        $inicio =0;
        $cantidad =100;
        if ($pagina >1) {
            $inicio =($cantidad*($pagina-1))+1;   
            $cantidad = $cantidad * $pagina; 
        }

        $query = "SELECT PacienteId, Nombre, DNI, Telefono, Correo FROM pacientes limit $inicio, $cantidad";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

    public function obtenerPaciente($id){
        $query = "SELECT * FROM pacientes WHERE PacienteId = '$id'";       
        return parent::obtenerDatos($query);
    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401(); 
        }else{
            $this-> token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if (!isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])) {
                    return $_respuestas->error_400();
                }else {
                    $this -> nombre = $datos['nombre'];
                    $this -> dni = $datos['dni'];
                    $this -> correo = $datos['correo'];
                    if (isset ($datos ['telefono'])){$this -> telefono = $datos['telefono'];}
                    if (isset ($datos ['direccion'])){$this -> direccion = $datos['direccion'];}
                    if (isset ($datos ['codigPostal'])){$this -> codigoPostal = $datos['codigoPostal'];}
                    if (isset ($datos ['genero'])){$this -> genero = $datos['genero'];}
                    if (isset ($datos ['fechaNacimiento'])){$this -> fechaNacimiento = $datos['fechaNacimiento'];}
                    $resp = $this -> insertarPaciente();
                    if($resp){
                        $respuesta =$_respuestas->response ;
                        $respuesta ["result"]=array (
                            "pacienteId" =>$resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas ->error_500();
                    }
            
                    
                }

            }else{
                return $_respuestas->error_401("el token que envio es invalid o caduco");
            }
        }
    
    }

    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401(); 
        }else{
            $this-> token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if (!isset($datos['pacienteId'])) {
                    return $_respuestas->error_400();
                } else {
                    $this->pacienteId = $datos['pacienteId'];
                    if (isset($datos['nombre'])) { $this->nombre = $datos['nombre']; }
                    if (isset($datos['dni'])) { $this->dni = $datos['dni']; }
                    if (isset($datos['correo'])) { $this->correo = $datos['correo']; }
                    if (isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
                    if (isset($datos['direccion'])) { $this->direccion = $datos['direccion']; }
                    if (isset($datos['codigoPostal'])) { $this->codigoPostal = $datos['codigoPostal']; }
                    if (isset($datos['genero'])) { $this->genero = $datos['genero']; }
                    if (isset($datos['fechaNacimiento'])) { $this->fechaNacimiento = $datos['fechaNacimiento']; }
            
                    $resp = $this->modificarPaciente();
                    if ($resp) {
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "pacienteId" => $this->pacienteId
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("el token que envio es invalid o caduco");
            }
        }
    

    }


    private function insertarPaciente(){
        $query = "INSERT INTO $this->table (DNI, Nombre,  Direccion, CodigoPostal, Telefono, Genero, FechaNacimiento, Correo)
        VALUES ('".$this->dni."','".$this->nombre."','".$this->direccion."','".$this->codigoPostal."','".
                $this->telefono."','".$this->genero."','".$this->fechaNacimiento."','".$this->correo."')";
        $resp = parent::nonQueryid($query);
        if ($resp){
            return $resp;
        }else{
            return 0; 
        }
        
        
    }

    private function modificarPaciente(){
        $query = "UPDATE $this->table SET ";
    
        if ($this->nombre != "") { $query .= "Nombre = '".$this->nombre."',"; }
        if ($this->dni != "") { $query .= "DNI = '".$this->dni."',"; }
        if ($this->correo != "") { $query .= "Correo = '".$this->correo."',"; }
        if ($this->telefono != "") { $query .= "Telefono = '".$this->telefono."',"; }
        if ($this->direccion != "") { $query .= "Direccion = '".$this->direccion."',"; }
        if ($this->codigoPostal != "") { $query .= "CodigoPostal = '".$this->codigoPostal."',"; }
        if ($this->genero != "") { $query .= "Genero = '".$this->genero."',"; }
        if ($this->fechaNacimiento != "0000-00-00") { $query .= "FechaNacimiento = '".$this->fechaNacimiento."',"; }
    

        $query = rtrim($query, ',');
    
        $query .= " WHERE PacienteId = '".$this->pacienteId."'";
    
        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        } else {
            return 0;
        }
    }

    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401(); 
        }else{
            $this-> token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['pacienteId'])){
                    return $_respuestas->error_400();
                }else{
                    $this->pacienteId = $datos['pacienteId'];
                    $resp = $this->eliminarPaciente();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "pacienteId" => $this->pacienteId
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }
            }else{
                return $_respuestas->error_401("el token que envio es invalid o caduco");
            }
        }
    
    }




    public function eliminarPaciente(){
        $query = "DELETE FROM " .$this->table . " WHERE pacienteId= '" . $this->pacienteId . "'";
        $resp = parent::nonQuery($query);
        if($resp>=1){
            return $resp;  
        }else{
            return 0;
        }
    }

    public function buscarToken(){
        $query = "SELECT TokenId, UsuarioId, Estado from usuarios_token  WHERE Token ='" . $this->token .
        "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    public function actualizarToken($token){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$token' ";
        $resp = parent:: nonQuery($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }
}

?>