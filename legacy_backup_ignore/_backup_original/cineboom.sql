-- ============================================
-- BASE DE DATOS: cineboom
-- Crear en phpMyAdmin de XAMPP
-- ============================================

CREATE DATABASE IF NOT EXISTS cineboom CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cineboom;

-- Tabla de usuarios (admin y usuarios normales)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de películas
CREATE TABLE peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    duracion VARCHAR(20),
    genero VARCHAR(80),
    calificacion TINYINT(1) DEFAULT 3,
    imagen VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de confitería
CREATE TABLE confiteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- DATOS INICIALES
-- ============================================

-- Admin por defecto  (usuario: admin | contraseña: admin123)
INSERT INTO usuarios (nombre, correo, usuario, contrasena, rol) VALUES
('Administrador', 'admin@cineboom.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Nota: el hash corresponde a la contraseña "password"
-- Para usar "admin123" ejecuta en PHP: echo password_hash('admin123', PASSWORD_DEFAULT);
-- O usa la contraseña "password" directamente con el hash de arriba

-- Usuario normal de prueba (usuario: usuario1 | contraseña: password)
INSERT INTO usuarios (nombre, correo, usuario, contrasena, rol) VALUES
('Usuario Demo', 'usuario@cineboom.com', 'usuario1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario');

-- Películas iniciales
INSERT INTO peliculas (titulo, descripcion, duracion, genero, calificacion, imagen) VALUES
('Rocky', 'Un boxeador amateur de Filadelfia tiene una oportunidad inesperada para enfrentarse al campeón del mundo. Una historia de superación personal, pasión y lucha.', '2 horas', 'Deportes / Acción', 5, 'img/pelicularocky.jpeg'),
('Deadpool', 'Wade Wilson se convierte en el antihéroe Deadpool tras un experimento que le otorga poderes de curación. Con humor ácido rompe la cuarta pared buscando venganza.', '1h 48m', 'Comedia / Acción', 3, 'img/peliculadeadpool.jpeg'),
('Dune Part 2', 'Paul Atreides se une a los Fremen para cumplir su destino y enfrentar a las fuerzas que destruyeron a su familia en esta épica continuación.', '2h 46m', 'Ciencia Ficción / Aventura', 4, 'img/peliculadune.jpeg'),
('Misión Imposible', 'Ethan Hunt y su equipo deben detener una inteligencia artificial fuera de control que amenaza al mundo entero en su misión más peligrosa.', '2h 50m', 'Suspenso / Acción', 5, 'img/peliculamisionimposible.jpeg');

-- Confitería inicial
INSERT INTO confiteria (titulo, descripcion, precio, imagen) VALUES
('Pipocas', 'Saladas o dulces, la mejor compañía para tu película.', 25.00, 'img/pipocas.jpeg'),
('Soda', 'Refrescante bebida para disfrutar durante la función.', 15.00, 'img/Soda.jpeg'),
('Nachos', 'Crujientes nachos con salsa, ideales para compartir.', 20.00, 'img/nachos.jpeg'),
('Panchito', 'Delicioso hot dog al estilo CINEBOOM.', 15.00, 'img/pancho.jpeg'),
('M & M', 'Los clásicos chocolates de colores para los más dulceros.', 7.00, 'img/M & M.jpeg'),
('Snickers', 'Barra de chocolate, caramelo y maní para los amantes del dulce.', 5.00, 'img/sni.jpeg');
