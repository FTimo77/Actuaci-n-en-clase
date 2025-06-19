<?php
session_start();

require_once 'config/conexion.php';
$conexion = new Conexion();
$conn = $conexion->conectar(); // Asegúrate de que este método retorna un objeto PDO, no mysqli

// Lógica para insertar un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO usuarios (nombre, email, telefono) VALUES (:nombre, :email, :telefono)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $mensaje = "Usuario creado exitosamente.";
    } else {
        $mensaje = "Error al crear el usuario.";
    }
}

// Lógica para leer todos los usuarios
$sql_leer = "SELECT * FROM usuarios ORDER BY id DESC";
$stmt_leer = $conn->prepare($sql_leer);
$stmt_leer->execute();
$usuarios = $stmt_leer->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuarios con PHP</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Usuarios (CRUD)</h1>

        <!-- Formulario para Crear Usuario -->
        <div class="card mb-5">
            <div class="card-header">
                Añadir Nuevo Usuario
            </div>
            <div class="card-body">
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-info"><?php echo $mensaje; ?></div>
                <?php endif; ?>
                <form action="crud.php" method="POST">
                    <input type="hidden" name="accion" value="crear">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                </form>
            </div>
        </div>

        <!-- Tabla para Mostrar Usuarios (Leer) -->
        <div class="card">
            <div class="card-header">
                Lista de Usuarios
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                            <td>
                                <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="eliminar.php?id=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>