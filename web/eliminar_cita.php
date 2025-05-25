<?php
session_start();
require_once 'config.php';
require_once 'api_helper.php';

if (!isset($_SESSION['token'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$api = new ApiHelper();

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['citaId'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'ID de cita no proporcionado']);
    exit;
}

$data = [
    'citaId' => $input['citaId']
];

$response = $api->delete('citas.php', $data);

header('Content-Type: application/json');
if (isset($response['status']) && $response['status'] == "success") {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la cita']);
}
?> 