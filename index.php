<?php include 'conexion.php'; 

// Lógica de Registro
if (isset($_POST['registro'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptación
    $tarjeta = $_POST['tarjeta'];

    $sql = "INSERT INTO usuarios (nombre, email, password, tarjeta_credito) VALUES ('$nombre', '$email', '$pass', '$tarjeta')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registro exitoso. Por favor inicie sesión.');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Lógica de Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM usuarios WHERE email='$email'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_nombre'] = $row['nombre'];
            header("Location: inicio.php");
        } else {
            echo "<script>alert('Contraseña incorrecta');</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Reserva Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sistema de Reserva de Vuelos</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card p-4">
                    <h4>Iniciar Sesión</h4>
                    <form method="POST">
                        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Ingresar</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <h4>Registrarse</h4>
                    <form method="POST">
                        <div class="mb-3"><label>Nombre Completo</label><input type="text" name="nombre" class="form-control" required></div>
                        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="mb-3"><label>Contraseña</label><input type="password" name="password" class="form-control" required></div>
                        <div class="mb-3"><label>Tarjeta de Crédito (Opcional)</label><input type="text" name="tarjeta" class="form-control"></div>
                        <button type="submit" name="registro" class="btn btn-success w-100">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>