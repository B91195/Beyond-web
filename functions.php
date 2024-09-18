<?php

function conexion() {
    $host = 'localhost';
    $usuario = 'root';
    $contraseña = '';
    $baseDeDatos = 'cajauno';

    $conn = new mysqli($host, $usuario, $contraseña, $baseDeDatos);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    return $conn;
}



function mostrarRegistros($conn, $tabla, $busqueda = '') {
    $colores_categoria = array(
        "piezas_3d" => "blue",
        "impresiones" => "red",
    );

    // Escapar la entrada para evitar inyecciones SQL
    $busqueda = $conn->real_escape_string($busqueda);
    $sql = "SELECT * FROM $tabla WHERE nombre LIKE '%$busqueda%' OR categoria LIKE '%$busqueda%'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imagen = htmlspecialchars($row["imagen"]);
            $categoria = htmlspecialchars($row["categoria"]);

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["contador"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
            echo "<td style='color: " . $colores_categoria[$categoria] . "'>" . htmlspecialchars($row["categoria"]) . "</td>";
            echo "<td>";
            if ($imagen) {
                echo "<img src='$imagen' alt='Imagen' style='width:100px; height:auto; cursor:pointer;' onclick='openModal(\"$imagen\")'>";
            } else {


            
                echo "No disponible";
            }
            echo "</td>";
            echo "<td>
                    <a href='?edit=" . htmlspecialchars($row["id"]) . "'>Editar</a>
                    <a href='?delete=" . htmlspecialchars($row["id"]) . "' onclick='return confirm(\"¿Estás seguro de eliminar este registro?\")'>Eliminar</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No se encontraron registros.</td></tr>";
    }
}

// Función para agregar un nuevo registro
function registrarRegistro($conn, $nombre, $descripcion, $categoria, $imagen) {
    $imagen_path = '';
    if ($imagen['error'] == UPLOAD_ERR_OK) {
        $imagen_tmp_name = $imagen['tmp_name'];
        $imagen_name = basename($imagen['name']);
        $imagen_path = "uploads/" . $imagen_name; // Asegúrate de que el directorio 'uploads' exista
        move_uploaded_file($imagen_tmp_name, $imagen_path);
        $imagen_path = $conn->real_escape_string($imagen_path);
    }

    if (!empty($nombre) && !empty($descripcion) && !empty($categoria)) {
        $nombre = $conn->real_escape_string($nombre);
        $descripcion = $conn->real_escape_string($descripcion);
        $categoria = $conn->real_escape_string($categoria);

        $sql_contador = "SELECT COALESCE(MAX(contador), 0) AS max_contador FROM piezas_impresiones_combinadas";
        $result_contador = $conn->query($sql_contador);
        $row_contador = $result_contador->fetch_assoc();
        $nuevo_contador = $row_contador["max_contador"] + 1;

        $sql = "INSERT INTO piezas_impresiones_combinadas (nombre, descripcion, categoria, contador, imagen) VALUES ('$nombre', '$descripcion', '$categoria', $nuevo_contador, '$imagen_path')";

        if ($conn->query($sql) === TRUE) {
            echo "Registro agregado con éxito.";
        } else {
            echo "Error al agregar registro: " . $conn->error;
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

// Función para actualizar un registro en la tabla específica
// Función para actualizar un registro en la tabla específica
// ... (código existente)

// Función para editar un registro
function editarRegistro($conn, $id, $nombre, $descripcion, $categoria, $imagen) {
    // Consulta SQL básica sin imagen
    $sql = "UPDATE piezas_impresiones_combinadas SET nombre = ?, descripcion = ?, categoria = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Verificar si hay una nueva imagen
    if (!empty($imagen['name'])) {
        $imagePath = 'uploads/' . basename($imagen['name']);
        move_uploaded_file($imagen['tmp_name'], $imagePath);

        // Actualiza el SQL para incluir la imagen
        $sql = "UPDATE piezas_impresiones_combinadas SET nombre = ?, descripcion = ?, categoria = ?, imagen = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $nombre, $descripcion, $categoria, $imagePath, $id); // Añadimos la imagen
    } else {
        // Si no hay nueva imagen, mantener la consulta original
        $stmt->bind_param('sssi', $nombre, $descripcion, $categoria, $id); // Sin imagen
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro actualizado exitosamente.";
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }
}



// Función para eliminar un registro
function eliminarRegistro($conn, $id) {
    $id = $conn->real_escape_string($id);
    
    $sql = "DELETE FROM piezas_impresiones_combinadas WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Registro eliminado con éxito.";
    } else {
        echo "Error al eliminar registro: " . $conn->error;
    }
}

// Función para obtener un registro por ID (para edición)
function obtenerRegistro($conn, $id) {
    $id = $conn->real_escape_string($id);

    $sql = "SELECT * FROM piezas_impresiones_combinadas WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        echo "No se encontró el registro con ID $id.";
        return null;
    }
}




?>

