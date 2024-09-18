<?php
 
session_start(); // Inicia la sesión en PHP

// Verifica si el usuario está autenticado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php"); // Redirige a la página de inicio de sesión si no está autenticado
    exit;
}


include 'functions.php';
$conn = conexion(); // Establece la conexión a la base de datos

// Manejo del formulario de registro
if (isset($_POST['submit'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $imagen = $_FILES['imagen'];
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if ($_POST['action'] == 'add') {
        registrarRegistro($conn, $nombre, $descripcion, $categoria, $imagen);
    } elseif ($_POST['action'] == 'edit') {
        editarRegistro($conn, $id, $nombre, $descripcion, $categoria, $imagen);
    }
}

// Manejo de la solicitud de eliminación
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    eliminarRegistro($conn, $id);
}

// Manejo de la solicitud de edición
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $registro = obtenerRegistro($conn, $id);
}

// Manejo de la búsqueda
$busqueda = '';
if (isset($_POST['search'])) {
    $busqueda = $_POST['search'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Piezas</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="uploads/riddler.png" type="image/png">

    <script src="script.js" defer></script>
</head>
<body>

    <!-- Formulario de búsqueda -->
    <div class="navbar">
        <h1>Gestión de Piezas 3D e Impresiones</h1>
        <form action="logout.php" method="post">
            <input type="hidden" name="action" value="logout">
            <input type="submit" value="Cerrar sesión">
        </form>

        <!-- Formulario de búsqueda -->
        <form action="" method="post" class="search-form">
            <input type="text" name="search" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar por nombre o categoría">
            <input type="submit" value="Buscar">
        </form>

        <h2 id="formTitle"><?php echo isset($registro) ? 'Editar Registro' : 'Agregar nuevo registro'; ?></h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo isset($registro) ? 'edit' : 'add'; ?>">
            <input type="hidden" name="id" id="recordId" value="<?php echo isset($registro['id']) ? $registro['id'] : ''; ?>">
            Nombre: <input type="text" name="nombre" id="recordName" value="<?php echo isset($registro['nombre']) ? $registro['nombre'] : ''; ?>" required><br>
            Descripción: <input type="text" name="descripcion" value="<?php echo isset($registro['descripcion']) ? $registro['descripcion'] : ''; ?>" required><br>
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria">
                <option value="piezas_3d" <?php echo isset($registro['categoria']) && $registro['categoria'] == 'piezas_3d' ? 'selected' : ''; ?>>Piezas 3D</option>
                <option value="impresiones" <?php echo isset($registro['categoria']) && $registro['categoria'] == 'impresiones' ? 'selected' : ''; ?>>Impresiones</option>
            </select>
            Imagen: <input type="file" name="imagen"><br>
            <input type="submit" name="submit" value="<?php echo isset($registro) ? 'Actualizar Registro' : 'Guardar Registro'; ?>">
        </form>
        <?php if (isset($registro)) : ?>
            <input type="button" value="Agregar Nuevo Registro" onclick="window.location.href='index.php';">
        <?php endif; ?>
    </div>

    <!-- Tabla para mostrar registros -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            mostrarRegistros($conn, 'piezas_impresiones_combinadas', $busqueda);
            ?>
        </tbody>
    </table>

   
    <?php $conn->close(); // Cerrar la conexión aquí ?>
</body>
</html>
