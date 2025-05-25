<?php
session_start();
require_once 'config.php';
require_once 'api_helper.php';

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

$api = new ApiHelper();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$response = $api->get('citas.php', ['page' => $page]);

// Convertir la respuesta en un array de citas
$citas = [];
if ($response) {
    if (is_string($response)) {
        $response = json_decode($response, true);
    }
    
    if (is_array($response)) {
        foreach ($response as $cita) {
            if (isset($cita['CitaId'])) {
                $citas[] = $cita;
            }
        }
    }
}

$hasNextPage = count($citas) >= 5;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas - Sistema de Gestión Médica</title>
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
                        <a class="nav-link" href="index.php">Pacientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="citas.php">Citas</a>
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
            <h2>Gestión de Citas</h2>
            <a href="nueva_cita.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Nueva Cita
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Estado</th>
                        <th>Motivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($citas)): ?>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($cita['Fecha']))); ?></td>
                                <td><?php echo htmlspecialchars(date('H:i', strtotime($cita['HoraInicio'])) . ' - ' . 
                                                             date('H:i', strtotime($cita['HoraFIn']))); ?></td>
                                <td><?php echo htmlspecialchars($cita['Nombre'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge <?php echo $cita['Estado'] == 'Confirmada' ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo htmlspecialchars($cita['Estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($cita['Motivo']); ?></td>
                                <td>
                                    <a href="editar_cita.php?id=<?php echo $cita['CitaId']; ?>" 
                                       class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-pencil"></i>
                                    </a>
                                    <button onclick="eliminarCita(<?php echo $cita['CitaId']; ?>)" 
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <?php if ($response === false): ?>
                                    Error de conexión con la API
                                <?php else: ?>
                                    No hay citas registradas
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
                    <a class="page-link" href="citas.php">Inicio</a>
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
        function eliminarCita(id) {
            if (confirm('¿Está seguro de que desea eliminar esta cita?')) {
                fetch('eliminar_cita.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ citaId: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        location.reload();
                    } else {
                        alert('Error al eliminar la cita');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar la cita');
                });
            }
        }
    </script>
</body>
</html> 