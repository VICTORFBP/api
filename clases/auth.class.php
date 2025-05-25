<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';
class auth extends conexion{

    public function login($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        //inser verifica si un campo esta vacío
        if( !isset($datos['usuario']) || !isset($datos['password'])){
            //datos mal
            return $_respuestas -> error_400();
        }else{
            //todo bien
            $usuario = $datos ['usuario'];
            $password = $datos ['password'];
            $password = parent:: encriptar($password);
            $datos = $this -> obtenerDatosUsuario($usuario);
                if ($datos){
                    //si datos
                    if( $password == $datos [0]['Password']){
                        if( $datos [0]['Estado']== 'Activo'){ 
                            //crear el token
                            $verificar = $this -> insertarToken($datos [0]['UsuarioId']);
                            if($verificar){
                                //si se guarda
                                $result = $_respuestas -> response;
                                $result ["result"] =array(
                                    "token" => $verificar
                                );
                                return $result;
                            }else{
                                //si hubo error al guardar
                                return $_respuestas -> error_500("Error interno, solucionaremos prontamente ");
                            }
                        } else{
                            return $_respuestas -> error_200("Usuario inactivo");
                        }

                    }else{
                        return $_respuestas -> error_200("La contraeña es invalida");
                    }
            }else{
                 return $_respuestas -> error_200(("El usuario $usuario no existe"));
             }
            
        }

    }
    

    public function obtenerDatosUsuario($correo){
        $query = "SELECT UsuarioId, Password, Estado FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent ::obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return $datos;
            
        }else{
            return 0;
        }

    }
    public function insertarToken($usuarioid){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date (" y- m - d - H : i ");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha)VALUES('$usuarioid', '$token', '$estado', '$date')";
        $verifica = parent :: nonQuery($query);
        if($verifica){
            return $token;
        } else{
            return 0;
        }
    }

}

?>
