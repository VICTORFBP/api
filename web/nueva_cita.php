<?php
session_start();
require_once 'config.php';
require_once 'api_helper.php';

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

$api = new ApiHelper();

// Obtener lista de pacientes para el select
$pacientes = $api->get('pacientes.php');
if (is_string($pacientes)) {
    $pacientes = json_decode($pacientes, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'PacienteId' => $_POST['paciente_id'],
        'Fecha' => $_POST['fecha'],
        'HoraInicio' => $_POST['hora_inicio'],
        'HoraFIn' => $_POST['hora_fin'],
        'Estado' => $_POST['estado'],
        'Motivo' => $_POST['motivo']
    ];

    $response = $api->post('citas.php', $data);

    if (isset($response['status']) && $response['status'] == "success") {
        header('Location: citas.php');
        exit;
    } else {
        $error = "Error al crear la cita";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Cita - Sistema de Gestión Médica</title>
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
                        <a class="nav-link" href="citas.php">Volver a Citas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Nueva Cita</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="paciente_id" class="form-label">Paciente</label>
                    <select class="form-control" id="paciente_id" name="paciente_id" required>
                        <option value="">Seleccione un paciente...</option>
                        <?php if (is_array($pacientes)): ?>
                            <?php foreach ($pacientes as $paciente): ?>
                                <option value="<?php echo $paciente['PacienteId']; ?>">
                                    <?php echo htmlspecialchars($paciente['Nombre'] . ' - ' . $paciente['DNI']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="hora_fin" class="form-label">Hora de Fin</label>
                    <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-control" id="estado" name="estado" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Confirmada">Confirmada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="motivo" class="form-label">Motivo</label>
                    <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Guardar Cita</button>
                <a href="citas.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validar que la hora de fin sea posterior a la hora de inicio
        document.querySelector('form').addEventListener('submit', function(e) {
            var horaInicio = document.getElementById('hora_inicio').value;
            var horaFin = document.getElementById('hora_fin').value;
            
            if (horaFin <= horaInicio) {
                e.preventDefault();
                alert('La hora de fin debe ser posterior a la hora de inicio');
            }
        });
    </script>
</body>
</html> 