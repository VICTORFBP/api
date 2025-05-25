<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Realizar la solicitud directamente a pacientes.php
$ch = curl_init(API_URL . 'pacientes.php?page=' . $page);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'token: ' . $_SESSION['token']
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Convertir la respuesta en un array de pacientes
$pacientes = [];
$totalPacientes = 0;

if ($response) {
    $pacientes = json_decode($response, true);
    if (is_array($pacientes)) {
        $totalPacientes = count($pacientes);
    }
}

// Calcular si hay páginas anteriores o siguientes
$hasPrevPage = $page > 1;
$hasNextPage = count($pacientes) >= 5; // Asumiendo que mostramos 5 pacientes por página
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Médica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Sistema de Gestión Médica</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Pacientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="citas.php">Citas</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Pacientes</h2>
            <a href="nuevo_paciente.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Nuevo Paciente
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pacientes)): ?>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($paciente['DNI']); ?></td>
                                <td><?php echo htmlspecialchars($paciente['Nombre']); ?></td>
                                <td><?php echo htmlspecialchars($paciente['Telefono']); ?></td>
                                <td><?php echo htmlspecialchars($paciente['Correo']); ?></td>
                                <td>
                                    <a href="editar_paciente.php?id=<?php echo $paciente['PacienteId']; ?>" 
                                       class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-pencil"></i>
                                    </a>
                                    <button onclick="eliminarPaciente(<?php echo $paciente['PacienteId']; ?>)" 
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <?php if ($response === false): ?>
                                    Error de conexión con la API
                                <?php else: ?>
                                    No hay pacientes registrados
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Navegación de páginas">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page - 1); ?>">Anterior</a>
                    </li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="index.php">Inicio</a>
                </li>
                <?php if ($hasNextPage): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Siguiente</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function eliminarPaciente(id) {
            if (confirm('¿Está seguro de que desea eliminar este paciente?')) {
                fetch('eliminar_paciente.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ pacienteId: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        location.reload();
                    } else {
                        alert('Error al eliminar el paciente');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el paciente');
                });
            }
        }
    </script>
</body>
</html> 