<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

$_respuestas = new respuestas;
$_pacientes = new pacientes;

if ($_SERVER['REQUEST_METHOD']== 'GET') {

    if (isset($_GET["page"])) {
        $pagina = $_GET["page"];
        //$pacientes->listaPacientes($pagina);
        $listapacientes = $_pacientes->listaPacientes($pagina);
        header("content-Type: application/json");
        echo json_encode($listapacientes);
        http_response_code(200);
    }elseif (isset($_GET['id'])) {
        $pacienteid = $_GET['id'];
        $datospaciente = $_pacientes->obtenerPaciente($pacienteid);
        echo json_encode($datospaciente);
        http_response_code(200);
    }
}else if ($_SERVER['REQUEST_METHOD']== 'POST') {

    $postBody = file_get_contents("php://input");
    $datosArray = $_pacientes->post($postBody);

    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }

    echo json_encode($datosArray);


}else if ($_SERVER['REQUEST_METHOD']== 'PUT') {
    $postBody = file_get_contents("php://input");
    $datosArray = $_pacientes->put($postBody);

    header('Content-Type: application/json');
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);


}else if ($_SERVER['REQUEST_METHOD']== 'DELETE') {

    $headers = getallheaders();
    if(isset($headers["token"]) && isset($headers["pacienteId"])){ //Si en la variable token y en pácienteId viene algo (no estan vacias) se hace lo siguiente...
        //Recibimos por headers
        $send = [
            "token" => $headers["token"],
            "pacienteId" => $headers["pacienteId"]
        ];
        $postBody = json_encode($send);

    }else{
        //Recibir datos que envian
        $postBody = file_get_contents("php://input");
    }

    //Enviamos 
    $datosArray = $_pacientes->delete($postBody);

    header("content-Type: application/json");
    if (isset($datosArray["result"]["error_id"])) {
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    } else {
        echo json_encode($datosArray);
        http_response_code(200);
    }
}else {
    header('conten-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);

}

?>