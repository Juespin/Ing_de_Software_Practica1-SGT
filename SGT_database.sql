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
(1003, 'Urgencias', 'Sotano', '123-4569'),
(1004, 'Radiología', 'Piso 1 ala este', '123-4570'),
(1005, 'Laboratorio Clínico', 'Piso 1 ala oeste', '123-4571'),
(1006, 'Quirófano', 'Piso 4 ala norte', '123-4572'),
(1007, 'Fisioterapia', 'Piso 2 ala este', '123-4573'),
(1008, 'Ginecología', 'Piso 3 ala sur', '123-4574');

-- --------------------------------------------------------
-- Tabla: responsables
-- --------------------------------------------------------
CREATE TABLE `responsables` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `Documento` varchar(10) NOT NULL,
  `Nombre_y_Apellidos` varchar(50) NOT NULL,
  `Cargo` varchar(30) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `responsables` (`id`, `Documento`, `Nombre_y_Apellidos`, `Cargo`, `Telefono`) VALUES
(456, '12345678', 'Juan Perez', 'Cardiologo', '987-6543'),
(457, '87654321', 'María Gonzales', 'Auxiliar de enfermeria', '987-6544'),
(458, '55566677', 'Carlos Ramirez', 'Radiologo', '987-6545'),
(459, '88899900', 'Ana Torres', 'Jefa de Laboratorio', '987-6546'),
(460, '11122233', 'Sofia Castro', 'Cirujana General', '987-6547'),
(461, '44455566', 'Luis Fernandez', 'Fisioterapeuta', '987-6548'),
(462, '22233344', 'Laura Jimenez', 'Pediatra', '987-6549'),
(463, '66677788', 'Elena Vargas', 'Ginecóloga', '987-6550'),
(464, '99900011', 'Pedro Mendoza', 'Técnico Biomédico', '987-6551'),
(465, '33344455', 'Daniela Rojas', 'Enfermera Jefe', '987-6552');

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
('BIO-1001', 'Monitor de Signos vitales', 'Philips', 'MX450', 1001, 462),
('BIO-1002', 'Ecógrafo', 'GE Healthcare', 'VIVID-E9', 1002, 456),
('BIO-1003', 'Máquina de Rayos X', 'Siemens', 'Multix Impact', 1004, 458),
('BIO-1004', 'Analizador de Hematología', 'Sysmex', 'XN-1000', 1005, 459),
('BIO-1005', 'Mesa de Operaciones', 'Maquet', 'Magnus', 1006, 460),
('BIO-1006', 'Lámpara Cialítica', 'Dräger', 'Polaris 600', 1006, 460),
('BIO-1007', 'Equipo de Ultrasonido Terapéutico', 'Chattanooga', 'Vectra Neo', 1007, 461),
('BIO-1008', 'Incubadora Neonatal', 'GE Healthcare', 'Giraffe OmniBed', 1001, 462),
('BIO-1009', 'Electrocardiógrafo', 'Welch Allyn', 'CP 150', 1002, 456),
('BIO-1010', 'Desfibrilador', 'Zoll', 'R Series', 1003, 465),
('BIO-1011', 'Ventilador Mecánico', 'Hamilton Medical', 'C1', 1003, 465),
('BIO-1012', 'Colposcopio', 'Leisegang', '3ML LED', 1008, 463),
('BIO-1013', 'Ecógrafo Obstétrico', 'Samsung', 'HS60', 1008, 463),
('BIO-1014', 'Bomba de Infusión', 'Baxter', 'Sigma Spectrum', 1001, 462),
('BIO-1015', 'Centrífuga de Laboratorio', 'Eppendorf', '5424 R', 1005, 459),
('BIO-1016', 'Electrobisturí', 'Valleylab', 'ForceTriad', 1006, 460),
('BIO-1017', 'Tomógrafo', 'Toshiba', 'Aquilion ONE', 1004, 458),
('BIO-1018', 'Láser Terapéutico', 'LiteCure', 'LCT-1000', 1007, 461),
('BIO-1019', 'Analizador de Gases', 'Radiometer', 'ABL90 FLEX', 1005, 459),
('BIO-1020', 'Cama Hospitalaria Eléctrica', 'Stryker', 'ProCuity', 1002, 457);