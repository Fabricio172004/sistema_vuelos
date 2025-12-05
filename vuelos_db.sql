CREATE DATABASE vuelos_db;
USE vuelos_db;

-- 1. Crear Tabla de Usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tarjeta_credito VARCHAR(20) NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Crear Tabla de Vuelos
CREATE TABLE vuelos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origen VARCHAR(50) NOT NULL,
    destino VARCHAR(50) NOT NULL,
    fecha_salida DATETIME NOT NULL,
    aerolinea VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    estado ENUM('A tiempo', 'Retrasado', 'Cancelado') DEFAULT 'A tiempo',
    asientos_disponibles INT NOT NULL
);

-- 3. Crear Tabla de Reservas
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    vuelo_id INT,
    fecha_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_pago ENUM('Pendiente', 'Pagado') DEFAULT 'Pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (vuelo_id) REFERENCES vuelos(id)
);

-- 4. Insertar Datos de Prueba (Semilla)
INSERT INTO vuelos (origen, destino, fecha_salida, aerolinea, precio, estado, asientos_disponibles) VALUES
('Lima', 'Cusco', '2023-12-20 08:00:00', 'Latam', 150.00, 'A tiempo', 50),
('Lima', 'Arequipa', '2023-12-20 09:30:00', 'Sky', 120.00, 'A tiempo', 20),
('Cusco', 'Lima', '2023-12-21 14:00:00', 'Latam', 160.00, 'Retrasado', 10),
('Trujillo', 'Lima', '2023-12-22 18:00:00', 'JetSmart', 90.00, 'A tiempo', 60);
