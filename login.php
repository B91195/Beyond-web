<?php
session_start(); // Inicia la sesión en PHP

// Credenciales de usuario codificadas (nombre de usuario y contraseña en texto plano)
$usuarios = [
    'BISHOP' => '2099', // Usuario de prueba
];

// Función para iniciar sesión
function iniciarSesion($usuarios) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "login") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        // Verificar las credenciales
        if (isset($usuarios[$username]) && $usuarios[$username] === $password) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            header("Location: index.php"); // Redirige a la página principal
            exit;
        } else {
            echo "Nombre de usuario o contraseña incorrectos";
        }
    }
}

// Manejo del formulario para iniciar sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    iniciarSesion($usuarios);
}

// Mostrar formulario de inicio de sesión si no está autenticado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<h2>Iniciar sesión</h2>";
    echo "<form action='' method='post'>";
    echo "<input type='hidden' name='action' value='login'>";
    echo "Nombre de usuario: <input type='text' name='username' required><br>";
    echo "Contraseña: <input type='password' name='password' required><br>";
    echo "<input type='submit' value='Iniciar sesión'>";
    echo "</form>";
}
?>
<style>
    /* Estilos generales */
    body {
        /* Estilos para los botones generales */
button, input[type="submit"] {
    background-color: #000000; /* Color de fondo negro */
    border: none; /* Sin borde */
    color: white; /* Texto blanco */
    padding: 10px 20px; /* Relleno interno */
    text-align: center; /* Alineación del texto */
    text-decoration: none; /* Sin subrayado */
    display: inline-block; /* Muestra como elemento en línea */
    font-size: 16px; /* Tamaño de fuente */
    margin: 4px 2px; /* Margen */
    cursor: pointer; /* Cambia el cursor a un puntero cuando pasa el ratón */
    border-radius: 5px; /* Bordes redondeados */
    transition: background-color 0.3s ease; /* Animación suave en el cambio de color */
}

/* Estilo de hover para los botones */
button:hover, input[type="submit"]:hover {
    background-color: #8b0000; /* Color de fondo más oscuro en hover */
}

/* Estilo para botones de acción secundaria (Eliminar, Editar) */
button.secondary, input[type="submit"].secondary {
    background-color: #f44336; /* Color de fondo rojo */
    color: white; /* Texto blanco */
}

/* Estilo de hover para botones de acción secundaria */
button.secondary:hover, input[type="submit"].secondary:hover {
    background-color: #8b0000; /* Color de fondo más oscuro en hover */
}

        font-family: 'BankGothic Md BT'; /* Fuente de aspecto técnico */
        background-color: #222; /* Fondo oscuro, común en interfaces sci-fi */
        color: #fff; /* Texto blanco para contraste */
    }

    /* Estilos para la tabla */
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #333;
    }

    tr:nth-child(even) {
        background-color: #8b0000;
    }

    /* Efecto de hover en filas */
    tr:hover {
        background-color: #000000;
    }

    /* Estilos para los formularios */

