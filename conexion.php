<?php
$host = "localhost";
$user = "root";
$pass = ""; // Por defecto en XAMPP es vacío
$db = "vuelos_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
session_start();
?>