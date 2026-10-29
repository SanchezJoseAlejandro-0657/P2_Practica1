<?php
// Aqui se guardan los accesos del servidor local de MySQL y el nombre de la base de datos
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "spidersuits_db";

// Se crea la conexion activa usando el objeto mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Se revisa si hubo un problema al conectar para detener el sistema y mostrar el error
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Deteccion del envio de datos
// Comprueba si la pagina recibio informacion por medio del metodo seguro POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Guarda el valor del campo oculto para saber si se dio clic en registrar o en login
    $accion = $_POST['accion'];

    // Logica de registro de nuevos usuarios
    // Si la variable accion dice registrar se ejecuta este bloque para dar de alta la cuenta
    if ($accion == 'registrar') {
        // Se jalan todos los datos enviados desde las cajas de texto del formulario de registro
        $usuario = $_POST['usuario'];
        $edad = $_POST['edad'];
        $correo = $_POST['correo'];
        $pass = $_POST['password'];

        // Verificar si el correo ya existe
        // Consulta SQL para buscar si el correo ingresado ya esta registrado en la tabla
        $checkEmail = "SELECT id FROM usuarios WHERE correo = '$correo'";
        $resultado = $conn->query($checkEmail);

        // Si el resultado es mayor a cero significa que el correo ya esta ocupado por otra persona
        if ($resultado->num_rows > 0) {
            echo "<script>alert('Error: El correo ya está registrado.'); window.location.href='index.html#registro';</script>";
        } else {
            // Si el correo esta libre se insertan los nuevos datos en la tabla usuarios de MySQL
            $sql = "INSERT INTO usuarios (usuario, edad, correo, password) VALUES ('$usuario', '$edad', '$correo', '$pass')";
            if ($conn->query($sql) === TRUE) {
                // Alerta que avisa que todo salio bien y redirige al bloque de inicio de sesion
                echo "<script>alert('¡Registro exitoso! Ya puedes iniciar sesión.'); window.location.href='index.html#login';</script>";
            } else {
                // Alerta de respaldo por si ocurre un fallo interno en la base de datos al guardar
                echo "<script>alert('Error al registrar usuario.'); window.location.href='index.html#registro';</script>";
            }
        }
    }
    
    // Logica de inicio de sesion(login)
    // Si la variable accion dice login se ejecuta este bloque para dejar pasar al usuario
    if ($accion == 'login') {
        // Se jalan el correo y contraseña escritos en el formulario de acceso
        $correo = $_POST['correo'];
        $pass = $_POST['password'];

        // Consulta SQL que busca una fila que coincida exactamente con el correo y la clave dados
        $sql = "SELECT usuario FROM usuarios WHERE correo = '$correo' AND password = '$pass'";
        $resultado = $conn->query($sql);

        // Si se encuentra una fila significa que las credenciales son correctas
        if ($resultado->num_rows > 0) {
            // Se extrae el registro para obtener el nombre del usuario y saludarlo en pantalla
            $row = $resultado->fetch_assoc();
            $nombreUsuario = $row['usuario'];
            echo "<script>alert('¡Bienvenido de nuevo, $nombreUsuario!'); window.location.href='index.html#inicio';</script>";
        } else {
            // Si no hay coincidencias se le avisa que se equivoco en los datos de entrada
            echo "<script>alert('Correo o contraseña incorrectos.'); window.location.href='index.html#login';</script>";
        }
    }
}

// Cierre de seguridad
// Corta la comunicacion con MySQL para liberar memoria del servidor una vez terminado todo
$conn->close();
?>
