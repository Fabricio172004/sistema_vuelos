<?php 
include 'conexion.php';
if (!isset($_SESSION['usuario_id'])) header("Location: index.php");

// Lógica de Reserva
if (isset($_GET['reservar'])) {
    $vuelo_id = $_GET['reservar'];
    $usuario_id = $_SESSION['usuario_id'];
    
    // Verificar si ya tiene tarjeta para comprar directamente o solo reservar
    $checkUser = $conn->query("SELECT tarjeta_credito FROM usuarios WHERE id=$usuario_id");
    $userRow = $checkUser->fetch_assoc();
    $estado_pago = ($userRow['tarjeta_credito'] != '') ? 'Pagado' : 'Pendiente';

    $sql = "INSERT INTO reservas (usuario_id, vuelo_id, estado_pago) VALUES ($usuario_id, $vuelo_id, '$estado_pago')";
    if($conn->query($sql)){
        echo "<script>alert('Reserva realizada. Estado: $estado_pago'); window.location='perfil.php';</script>";
    }
}

// Lógica de Búsqueda
$where = "1=1";
if (isset($_POST['buscar'])) {
    if (!empty($_POST['origen'])) $where .= " AND origen LIKE '%".$_POST['origen']."%'";
    if (!empty($_POST['destino'])) $where .= " AND destino LIKE '%".$_POST['destino']."%'";
    
    // Ordenar (Tarifas vs Horarios)
    $order = "fecha_salida ASC"; // Por defecto horario
    if ($_POST['filtro'] == 'precio') $order = "precio ASC";
} else {
    $order = "fecha_salida ASC";
}

$vuelos = $conn->query("SELECT * FROM vuelos WHERE $where ORDER BY $order");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Vuelos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary px-4">
        <a class="navbar-brand" href="#">VuelosApp</a>
        <div>
            <span class="text-white me-3">Hola, <?php echo $_SESSION['usuario_nombre']; ?></span>
            <a href="perfil.php" class="btn btn-light btn-sm">Mi Perfil</a>
            <a href="logout.php" class="btn btn-danger btn-sm">Salir</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card p-4 mb-4">
            <h5>Consultar Vuelos</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-3"><input type="text" name="origen" class="form-control" placeholder="Origen"></div>
                <div class="col-md-3"><input type="text" name="destino" class="form-control" placeholder="Destino"></div>
                <div class="col-md-3">
                    <select name="filtro" class="form-select">
                        <option value="horario">Ver por Horarios</option>
                        <option value="precio">Ver por Tarifas (Económico primero)</option>
                    </select>
                </div>
                <div class="col-md-3"><button type="submit" name="buscar" class="btn btn-primary w-100">Buscar</button></div>
            </form>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Aerolínea</th>
                    <th>Origen > Destino</th>
                    <th>Fecha/Hora</th>
                    <th>Estado</th>
                    <th>Precio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $vuelos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['aerolinea']; ?></td>
                    <td><?php echo $row['origen'] . " -> " . $row['destino']; ?></td>
                    <td><?php echo $row['fecha_salida']; ?></td>
                    <td>
                        <span class="badge bg-<?php echo ($row['estado']=='A tiempo')?'success':'warning'; ?>">
                            <?php echo $row['estado']; ?>
                        </span>
                    </td>
                    <td>$<?php echo $row['precio']; ?></td>
                    <td>
                        <?php if($row['asientos_disponibles'] > 0): ?>
                            <a href="inicio.php?reservar=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Reservar/Comprar</a>
                        <?php else: ?>
                            <span class="text-danger">Agotado</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>