<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

// Obtener el ID del paciente
$pacienteId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$pacienteId) {
    header('Location: index.php');
    exit;
}

// Obtener los datos del paciente usando el formato original
$ch = curl_init(API_URL . 'pacientes.php?id=' . $pacienteId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'token: ' . $_SESSION['token']
]);

$response = curl_exec($ch);
curl_close($ch);

$paciente = json_decode($response, true);
if (!$paciente || !is_array($paciente) || empty($paciente)) {
    header('Location: index.php');
    exit;
}
$paciente = $paciente[0]; // Tomar el primer resultado ya que es un paciente específico

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'token' => $_SESSION['token'],
        'pacienteId' => $pacienteId,
        'nombre' => $_POST['nombre'],
        'dni' => $_POST['dni'],
        'correo' => $_POST['correo'],
        'telefono' => $_POST['telefono'],
        'direccion' => $_POST['direccion'],
        'codigoPostal' => $_POST['codigo_postal'],
        'genero' => $_POST['genero'],
        'fechaNacimiento' => $_POST['fecha_nacimiento']
    ];

    $ch = curl_init(API_URL . 'pacientes.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'token: ' . $_SESSION['token']
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['status']) && $result['status'] == "ok") {
        header('Location: index.php');
        exit;
    } else {
        $error = isset($result['result']['error_msg']) ? $result['result']['error_msg'] : 'Error al actualizar el paciente';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente - Sistema de Gestión Médica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Gestión Médica</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Volver a la lista</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Editar Paciente</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" class="form-control" id="dni" name="dni" 
                           value="<?php echo htmlspecialchars($paciente['DNI'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($paciente['Nombre'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" 
                           value="<?php echo htmlspecialchars($paciente['Direccion'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="codigo_postal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                           value="<?php echo htmlspecialchars($paciente['CodigoPostal'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                           value="<?php echo htmlspecialchars($paciente['Telefono'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="genero" class="form-label">Género</label>
                    <select class="form-control" id="genero" name="genero" required>
                        <option value="">Seleccione...</option>
                        <option value="M" <?php echo ($paciente['Genero'] ?? '') == 'M' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="F" <?php echo ($paciente['Genero'] ?? '') == 'F' ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                           value="<?php echo htmlspecialchars($paciente['FechaNacimiento'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" 
                           value="<?php echo htmlspecialchars($paciente['Correo'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 