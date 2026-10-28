<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "spidersuits_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// PROCESAR DATOS ENVIADOS DESDE EL HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    // ACCIÓN: REGISTRARSE
    if ($accion == 'registrar') {
        $usuario = $_POST['usuario'];
        $edad = $_POST['edad'];
        $correo = $_POST['correo'];
        $pass = $_POST['password'];

        // Verificar si el correo ya existe
        $checkEmail = "SELECT id FROM usuarios WHERE correo = '$correo'";
        $resultado = $conn->query($checkEmail);

        if ($resultado->num_rows > 0) {
            echo "<script>alert('Error: El correo ya está registrado.'); window.location.href='index.html#registro';</script>";
        } else {
            $sql = "INSERT INTO usuarios (usuario, edad, correo, password) VALUES ('$usuario', '$edad', '$correo', '$pass')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('¡Registro exitoso! Ya puedes iniciar sesión.'); window.location.href='index.html#login';</script>";
            } else {
                echo "<script>alert('Error al registrar usuario.'); window.location.href='index.html#registro';</script>";
            }
        }
    }
    
    // ACCIÓN: INICIAR SESIÓN
    if ($accion == 'login') {
        $correo = $_POST['correo'];
        $pass = $_POST['password'];

        $sql = "SELECT usuario FROM usuarios WHERE correo = '$correo' AND password = '$pass'";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $nombreUsuario = $row['usuario'];
            echo "<script>alert('¡Bienvenido de nuevo, $nombreUsuario!'); window.location.href='index.html#inicio';</script>";
        } else {
            echo "<script>alert('Correo o contraseña incorrectos.'); window.location.href='index.html#login';</script>";
        }
    }
}

$conn->close();
?>