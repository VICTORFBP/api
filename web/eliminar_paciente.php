<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['token'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['pacienteId'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'ID de paciente no proporcionado']);
    exit;
}

$data = [
    'token' => $_SESSION['token'],
    'pacienteId' => $input['pacienteId']
];

$ch = curl_init(API_URL . 'pacientes.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'token: ' . $_SESSION['token'],
    'pacienteId: ' . $input['pacienteId']
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

header('Content-Type: application/json');
if (isset($result['status']) && $result['status'] == "ok") {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => isset($result['result']['error_msg']) ? $result['result']['error_msg'] : 'Error al eliminar el paciente'
    ]);
}
?> 