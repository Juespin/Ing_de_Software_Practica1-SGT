-- Base de datos: sgt
CREATE DATABASE IF NOT EXISTS sgt;
USE sgt;

-- --------------------------------------------------------
-- Tabla: ubicaciones
-- --------------------------------------------------------
CREATE TABLE `ubicaciones` (
  `Codigo` int(4) NOT NULL AUTO_INCREMENT,
  `Servicio` varchar(30) NOT NULL,
  `Ubicacion` varchar(30) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  PRIMARY KEY (`Codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ubicaciones` (`Codigo`, `Servicio`, `Ubicacion`, `Telefono`) VALUES
(1001, 'Pediatría', 'Piso 3 ala norte', '123-4567'),
(1002, 'Cardiología', 'Piso 2 ala sur', '123-4568'),
(1003, 'Urgencias', 'Sotano', '123-4569');

-- --------------------------------------------------------
-- Tabla: responsables
-- --------------------------------------------------------
CREATE TABLE `responsables` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `Documento` varchar(10) NOT NULL,
  `Nombre_y_Apellidos` varchar(50) NOT NULL,
  `Cargo` varchar(20) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `responsables` (`id`, `Documento`, `Nombre_y_Apellidos`, `Cargo`, `Telefono`) VALUES
(456, '12345678', 'Juan Perez', 'Cardiologo', '987-6543'),
(457, '87654321', 'María Gonzales', 'Auxiliar de enfermer', '987-6544');

-- --------------------------------------------------------
-- Tabla: equipos_medicos
-- --------------------------------------------------------
CREATE TABLE `equipos_medicos` (
  `Numero_Activo` varchar(8) NOT NULL,
  `Equipo` varchar(50) NOT NULL,
  `Marca` varchar(20) NOT NULL,
  `Modelo` varchar(20) NOT NULL,
  `Codigo_Ubicacion` int(4) NOT NULL,
  `Codigo_Responsable` int(4) NOT NULL,
  PRIMARY KEY (`Numero_Activo`),
  KEY `idx_ubicacion` (`Codigo_Ubicacion`),
  KEY `idx_responsable` (`Codigo_Responsable`),
  CONSTRAINT `fk_ubicacion` FOREIGN KEY (`Codigo_Ubicacion`) REFERENCES `ubicaciones` (`Codigo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_responsable` FOREIGN KEY (`Codigo_Responsable`) REFERENCES `responsables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `equipos_medicos` (`Numero_Activo`, `Equipo`, `Marca`, `Modelo`, `Codigo_Ubicacion`, `Codigo_Responsable`) VALUES
('BIO-1001', 'Monito de Signos vitales', 'Philips', 'MX450', 1001, 456),
('BIO-1002', 'Ecógrafo', 'GE Healthcare', 'VIVID-E9', 1002, 457);
