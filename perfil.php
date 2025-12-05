<?php 
include 'conexion.php';
if (!isset($_SESSION['usuario_id'])) header("Location: index.php");
$uid = $_SESSION['usuario_id'];

// Cancelar Reserva
if (isset($_GET['cancelar_reserva'])) {
    $rid = $_GET['cancelar_reserva'];
    $conn->query("DELETE FROM reservas WHERE id=$rid AND usuario_id=$uid");
    header("Location: perfil.php");
}

// Modificar Perfil
if (isset($_POST['actualizar'])) {
    $tarjeta = $_POST['tarjeta'];
    $conn->query("UPDATE usuarios SET tarjeta_credito='$tarjeta' WHERE id=$uid");
    echo "<script>alert('Datos actualizados');</script>";
}

// Eliminar Cuenta
if (isset($_POST['eliminar_cuenta'])) {
    $conn->query("DELETE FROM reservas WHERE usuario_id=$uid");
    $conn->query("DELETE FROM usuarios WHERE id=$uid");
    session_destroy();
    header("Location: index.php");
}

// Obtener datos
$user = $conn->query("SELECT * FROM usuarios WHERE id=$uid")->fetch_assoc();
$reservas = $conn->query("SELECT r.id, v.origen, v.destino, v.fecha_salida, r.estado_pago 
                          FROM reservas r 
                          JOIN vuelos v ON r.vuelo_id = v.id 
                          WHERE r.usuario_id=$uid");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="inicio.php" class="btn btn-secondary mb-3">Volver a Búsqueda</a>
        <h2>Mi Perfil</h2>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3 mb-3">
                    <h5>Mis Datos</h5>
                    <form method="POST">
                        <p><strong>Nombre:</strong> <?php echo $user['nombre']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                        <div class="mb-3">
                            <label>Tarjeta de Crédito (Para compras automáticas)</label>
                            <input type="text" name="tarjeta" class="form-control" value="<?php echo $user['tarjeta_credito']; ?>">
                        </div>
                        <button type="submit" name="actualizar" class="btn btn-primary btn-sm">Actualizar Tarjeta</button>
                    </form>
                    <hr>
                    <form method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar tu cuenta?');">
                        <button type="submit" name="eliminar_cuenta" class="btn btn-danger btn-sm w-100">Eliminar mi Cuenta</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-3">
                    <h5>Mis Reservas / Boletos</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Vuelo</th>
                                <th>Fecha</th>
                                <th>Estado Pago</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($r = $reservas->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $r['origen'] . " -> " . $r['destino']; ?></td>
                                <td><?php echo $r['fecha_salida']; ?></td>
                                <td>
                                    <?php if($r['estado_pago'] == 'Pagado'): ?>
                                        <span class="badge bg-success">BOLETO EMITIDO</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">RESERVA (Pagar en mostrador)</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="perfil.php?cancelar_reserva=<?php echo $r['id']; ?>" class="btn btn-danger btn-sm">Cancelar</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>