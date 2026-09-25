-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2024 a las 22:10:51
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cotizacionesm9`
--
CREATE DATABASE IF NOT EXISTS `cotizacionesm9` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cotizacionesm9`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos_roles`
--

CREATE TABLE `accesos_roles` (
  `id_acceso` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `usuarios` int(1) DEFAULT 0,
  `mecanicos` int(1) DEFAULT 0,
  `servicios` int(1) DEFAULT 0,
  `cotizaciones` int(1) DEFAULT 0,
  `repuestos` int(1) DEFAULT 0,
  `reportes` int(1) DEFAULT 0,
  `clientes` int(1) DEFAULT 0,
  `vehiculos` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `accesos_roles`
--

INSERT INTO `accesos_roles` (`id_acceso`, `id_rol`, `usuarios`, `mecanicos`, `servicios`, `cotizaciones`, `repuestos`, `reportes`, `clientes`, `vehiculos`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 3, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 2, 1, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `correo` varchar(65) NOT NULL,
  `direccion` varchar(255) DEFAULT '"Sin Información"'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombres`, `apellidos`, `telefono`, `correo`, `direccion`) VALUES
(1, 'Juan', 'Pérez', '5555-0123', 'maganaexabi@gmail.com', 'Calle Falsa 123, Ciudad A'),
(2, 'María', 'Gómez', '5555-4567', 'maria.gomez@gmail.com', 'Av. Siempre Viva 742, Ciudad B'),
(3, 'Luis', 'Rodríguez', '5555-8901', 'luis.rodriguez@gmail.com', 'Calle Principal 45, Ciudad C'),
(4, 'Ana', 'López', '5555-2345', 'ana.lopez@gmail.com', 'Calle Secundaria 12, Ciudad D'),
(5, 'Carlos', 'Martínez', '5555-6789', 'carlos.martinez@gmail.com', 'Paseo de la Reforma 1, Ciudad E'),
(6, 'Laura', 'Hernández', '5555-3456', 'laura.hernandez@gmail.com', 'Calle del Sol 90, Ciudad F'),
(7, 'Javier', 'Jiménez', '5555-7890', 'javier.jimenez@gmail.com', 'Calle del Mar 7, Ciudad G'),
(8, 'Claudia', 'Torres', '5555-1234', 'claudia.torres@gmail.com', 'Calle del Bosque 16, Ciudad H'),
(9, 'Diego', 'Ríos', '5555-5678', 'diego.rios@gmail.com', 'Calle de la Luna 32, Ciudad I'),
(10, 'Sofía', 'Vargas', '5555-9012', 'sofia.vargas@gmail.com', 'Calle del Río 78, Ciudad J'),
(11, 'Andrés', 'Fernández', '5555-2340', 'andres.fernandez@gmail.com', 'Calle de la Paz 56, Ciudad K'),
(12, 'Isabel', 'Mendoza', '5555-6781', 'isabel.mendoza@gmail.com', 'Calle de la Esperanza 34, Ciudad L'),
(13, 'Ricardo', 'Gutiérrez', '5555-9013', 'ricardo.gutierrez@gmail.com', 'Calle de la Libertad 21, Ciudad M'),
(14, 'Patricia', 'Castillo', '5555-3457', 'patricia.castillo@gmail.com', 'Calle del Norte 18, Ciudad N'),
(15, 'Fernando', 'Cordero', '5555-7891', 'fernando.cordero@gmail.com', 'Calle del Este 15, Ciudad O'),
(16, 'Verónica', 'Morales', '5555-0124', 'veronica.morales@gmail.com', 'Calle del Oeste 13, Ciudad P'),
(17, 'Samuel', 'Rivas', '5555-4568', 'samuel.rivas@gmail.com', 'Calle de la Historia 8, Ciudad Q'),
(18, 'Gabriela', 'Márquez', '5555-8902', 'gabriela.marquez@gmail.com', 'Calle del Futuro 77, Ciudad R'),
(19, 'Oscar', 'Salas', '5555-2341', 'oscar.salas@gmail.com', 'Calle del Recuerdo 43, Ciudad S'),
(20, 'Natalia', 'Sánchez', '5555-6782', 'natalia.sanchez@gmail.com', 'Calle de la Amistad 99, Ciudad T');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE `cotizaciones` (
  `id_cotizacion` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_mecánico` int(11) DEFAULT NULL,
  `fecha_pedido` date NOT NULL,
  `fecha_expiración` date DEFAULT NULL,
  `estado` enum('pendiente','en proceso','completado','cancelado','parcialmente') DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cotizaciones`
--

INSERT INTO `cotizaciones` (`id_cotizacion`, `id_vehiculo`, `id_mecánico`, `fecha_pedido`, `fecha_expiración`, `estado`, `total`) VALUES
(1, 1, 2, '2024-10-28', '2024-11-07', 'pendiente', 84.24),
(2, 2, 5, '2024-10-28', '2024-11-07', 'en proceso', 95.00),
(3, 3, 5, '2024-10-28', '2024-11-07', 'completado', 154.54),
(4, 10, 5, '2024-10-28', '2024-11-07', 'cancelado', 264.96),
(5, 13, 8, '2024-10-28', '2024-11-07', 'parcialmente', 220.40),
(6, 6, 5, '2024-10-28', '2024-11-07', 'completado', 30.50),
(7, 10, 6, '2024-10-28', '2024-11-07', 'completado', 55.60);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cotizacionesserviciosvista`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cotizacionesserviciosvista` (
`id_cotizacion` int(11)
,`fecha_pedido` date
,`fecha_expiración` date
,`total` decimal(10,2)
,`estado` enum('pendiente','en proceso','completado','cancelado','parcialmente')
,`nombres` varchar(100)
,`apellidos` varchar(50)
,`id_cliente` int(11)
,`marca` varchar(50)
,`modelo` varchar(50)
,`año` int(4)
,`color` varchar(50)
,`placas` varchar(8)
,`id_vehiculo` int(11)
,`mecánico` varchar(100)
,`id_mecánico` int(11)
,`id_servicio` int(11)
,`nombre_servicio` varchar(50)
,`descripcion_servicio` text
,`precio_servicio` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cotizacionesvista`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cotizacionesvista` (
`id_cotizacion` int(11)
,`fecha_pedido` date
,`fecha_expiración` date
,`total` decimal(10,2)
,`estado` enum('pendiente','en proceso','completado','cancelado','parcialmente')
,`nombres` varchar(100)
,`apellidos` varchar(50)
,`id_cliente` int(11)
,`marca` varchar(50)
,`modelo` varchar(50)
,`año` int(4)
,`color` varchar(50)
,`placas` varchar(8)
,`id_vehiculo` int(11)
,`mecánico` varchar(100)
,`id_mecánico` int(11)
,`id_repuesto` int(11)
,`nombre_repuesto` varchar(75)
,`marca_repuesto` varchar(50)
,`precio_unitario` decimal(10,2)
,`proveedor` varchar(50)
,`cantidad_repuesto` int(4)
,`repuesto_para` varchar(75)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `celular` varchar(9) DEFAULT '0000-0000',
  `fijo` varchar(9) DEFAULT '0000-0000',
  `direccion` text NOT NULL DEFAULT 'Sin Información',
  `logo` text DEFAULT '../img/logos/logo.png',
  `correo` varchar(75) DEFAULT 'correo@gmail.com',
  `slogan` text DEFAULT 'Sin Información'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `nombre`, `celular`, `fijo`, `direccion`, `logo`, `correo`, `slogan`) VALUES
(1, 'Taller', '6748-5820', '2000-2000', 'En Santa Ana', '../img/logos/logo.png', 'Sin Información', 'Sin Información');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mecánicos`
--

CREATE TABLE `mecánicos` (
  `id_mecánico` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(9) NOT NULL,
  `especialidad` varchar(65) DEFAULT '''Ninguna''',
  `fecha_nacimiento` date NOT NULL,
  `fecha_contrato` date NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `foto` text DEFAULT '../img/subidas/mecanicos/mecanico.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mecánicos`
--

INSERT INTO `mecánicos` (`id_mecánico`, `nombre`, `telefono`, `especialidad`, `fecha_nacimiento`, `fecha_contrato`, `estado`, `foto`) VALUES
(1, 'Ernesto de la Cruz', '6142-3467', 'Ninguna', '2003-12-23', '2023-03-12', 'INACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(2, 'Armando Mesas', '6890-8908', 'Ninguna', '2003-02-02', '2020-12-12', 'ACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(3, 'Andrés Martínez', '7890-4890', 'Ninguna', '2000-02-23', '2022-05-23', 'INACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(4, 'José Peréz', '7845-6789', 'Ninguna', '1995-12-12', '2019-02-23', 'ACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(5, 'Carlos Álvarez', '7234-5234', 'Ninguna', '1976-03-12', '2015-03-12', 'ACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(6, 'Mario López', '1234-5678', 'Ninguna', '1999-03-12', '2019-02-02', 'ACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(7, 'Eduardo Flores', '1010-1010', 'Ninguna', '2000-01-01', '2024-08-12', 'ACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(8, 'Bryan Moreno', '6788-9543', 'Ninguna', '2024-10-18', '2024-10-19', 'ACTIVO', '../img/subidas/mecanicos/mecanico.jpg'),
(9, 'Oscar Rosales', '1234-1234', 'Ninguna', '1987-10-16', '2017-05-12', 'ACTIVO', '../img/subidas/mecanicos/mecanico.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repuestos`
--

CREATE TABLE `repuestos` (
  `id_repuesto` int(11) NOT NULL,
  `nombre_repuesto` varchar(75) NOT NULL,
  `proveedor` varchar(50) DEFAULT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `para` varchar(75) DEFAULT 'Cualquiera'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `repuestos`
--

INSERT INTO `repuestos` (`id_repuesto`, `nombre_repuesto`, `proveedor`, `precio_unitario`, `marca`, `para`) VALUES
(1, 'Filtro de aceite', 'Proveedores S.A.', 15.99, 'Marca A', 'Toyota'),
(2, 'Bujías', 'Repuestos Rápidos', 7.50, 'Marca B', 'Honda'),
(3, 'Pastillas de freno', 'AutoParts Co.', 20.00, 'Marca C', 'Ford'),
(4, 'Aceite de motor', 'Lubricantes XYZ', 12.75, 'Marca D', 'Chevrolet'),
(5, 'Correa de distribución', 'Repuestos Globales', 45.00, 'Marca E', 'Nissan'),
(6, 'Amortiguador', 'AutoAccesorios', 50.25, 'Marca F', 'Hyundai'),
(7, 'Faro delantero', 'Repuestos del Sur', 35.60, 'Marca G', 'Kia'),
(8, 'Radiador', 'Clima Frío', 120.99, 'Marca H', 'Cualquiera'),
(9, 'Filtro de aire', 'Filtros y Más', 10.30, 'Marca I', 'Mazda'),
(10, 'Batería', 'Energía Total', 85.40, 'Marca J', 'Subaru'),
(11, 'Turbocompresor', 'TurboPro', 320.00, 'Marca K', 'Audi'),
(12, 'Cables de bujía', 'Cables y Más', 9.99, 'Marca L', 'Mercedes-Benz'),
(13, 'Disco de freno', 'Frenos Eficientes', 30.00, 'Marca M', 'BMW'),
(14, 'Luz trasera', 'Repuestos Innovadores', 22.15, 'Marca N', 'Peugeot'),
(15, 'Limpiaparabrisas', 'Acessorios Clarios', 5.75, 'Marca O', 'Cualquiera'),
(16, 'Tapa de motor', 'Partes y Servicio', 60.50, 'Marca P', 'Land Rover'),
(17, 'Sensor de oxígeno', 'Tecnología Automotriz', 35.80, 'Marca Q', 'Volvo'),
(18, 'Termostato', 'Repuestos del Norte', 18.00, 'Marca R', 'Jaguar'),
(19, 'Manguera de radiador', 'Mangueras y Más', 12.60, 'Marca S', 'Chrysler'),
(20, 'Juego de anclaje', 'AutoAnclajes', 25.90, 'Marca T', 'Renault');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repuestos_cotizaciones`
--

CREATE TABLE `repuestos_cotizaciones` (
  `id_detalle` int(11) NOT NULL,
  `id_cotizacion` int(11) DEFAULT NULL,
  `id_repuesto` int(11) NOT NULL,
  `cantidad` int(4) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `estado_item` enum('pendiente','aprobado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `repuestos_cotizaciones`
--

INSERT INTO `repuestos_cotizaciones` (`id_detalle`, `id_cotizacion`, `id_repuesto`, `cantidad`, `subtotal`, `estado_item`) VALUES
(1, 1, 1, 1, 15.99, 'pendiente'),
(2, 1, 4, 1, 12.75, 'pendiente'),
(3, 1, 3, 1, 20.00, 'pendiente'),
(4, 3, 1, 1, 15.99, 'pendiente'),
(5, 3, 9, 1, 10.30, 'pendiente'),
(6, 3, 4, 1, 12.75, 'pendiente'),
(7, 3, 3, 1, 20.00, 'pendiente'),
(8, 4, 12, 4, 39.96, 'pendiente'),
(9, 5, 10, 1, 85.40, 'pendiente'),
(10, 5, 2, 4, 30.00, 'pendiente'),
(11, 7, 7, 1, 35.60, '');

--
-- Disparadores `repuestos_cotizaciones`
--
DELIMITER $$
CREATE TRIGGER `actualizar_total_repuestos_cotizaciones` AFTER INSERT ON `repuestos_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el nuevo total de la cotización sumando los precios de los servicios y subtotales de los repuestos
    UPDATE cotizaciones
    SET total = (
        SELECT COALESCE((
            SELECT SUM(s.precio)
            FROM servicios_cotizaciones AS sc
            JOIN servicios AS s ON sc.id_servicio = s.id_servicio
            WHERE sc.id_cotizacion = NEW.id_cotizacion
        ), 0) + COALESCE(SUM(r.subtotal), 0)
        FROM repuestos_cotizaciones AS r
        WHERE r.id_cotizacion = NEW.id_cotizacion
    )
    WHERE id_cotizacion = NEW.id_cotizacion;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_total_repuestos_cotizaciones_after_delete` AFTER DELETE ON `repuestos_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el nuevo total de la cotización sumando los precios de los servicios y subtotales de los repuestos
    UPDATE cotizaciones
    SET total = (
        SELECT COALESCE((
            SELECT SUM(s.precio)
            FROM servicios_cotizaciones AS sc
            JOIN servicios AS s ON sc.id_servicio = s.id_servicio
            WHERE sc.id_cotizacion = OLD.id_cotizacion
        ), 0) + COALESCE(SUM(r.subtotal), 0)
        FROM repuestos_cotizaciones AS r
        WHERE r.id_cotizacion = OLD.id_cotizacion
    )
    WHERE id_cotizacion = OLD.id_cotizacion;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_total_repuestos_cotizaciones_after_update` AFTER UPDATE ON `repuestos_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el nuevo total de la cotización sumando los precios de los servicios y subtotales de los repuestos
    UPDATE cotizaciones
    SET total = (
        SELECT COALESCE((
            SELECT SUM(s.precio)
            FROM servicios_cotizaciones AS sc
            JOIN servicios AS s ON sc.id_servicio = s.id_servicio
            WHERE sc.id_cotizacion = NEW.id_cotizacion
        ), 0) + COALESCE(SUM(r.subtotal), 0)
        FROM repuestos_cotizaciones AS r
        WHERE r.id_cotizacion = NEW.id_cotizacion
    )
    WHERE id_cotizacion = NEW.id_cotizacion;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calcular_subtotal_repuestos_cotizaciones` BEFORE INSERT ON `repuestos_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el subtotal (precio * cantidad) y asignarlo directamente al nuevo registro
    SET NEW.subtotal = NEW.cantidad * (
        SELECT repuestos.precio_unitario 
        FROM repuestos 
        WHERE id_repuesto = NEW.id_repuesto
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calcular_subtotal_repuestos_cotizaciones_after_update` BEFORE UPDATE ON `repuestos_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el subtotal (precio * cantidad) y asignarlo directamente al registro actualizado
    SET NEW.subtotal = NEW.cantidad * (
        SELECT repuestos.precio_unitario 
        FROM repuestos 
        WHERE id_repuesto = NEW.id_repuesto
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `estado` enum('HABILITADO','DESHABILITADO') NOT NULL DEFAULT 'HABILITADO',
  `nivel` enum('1','2','3','4','5') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `estado`, `nivel`) VALUES
(1, 'ADMINISTRADOR', 'HABILITADO', '5'),
(2, 'VICE', 'HABILITADO', '3'),
(3, 'USUARIO', 'HABILITADO', '1');

--
-- Disparadores `roles`
--
DELIMITER $$
CREATE TRIGGER `deshabilitar_usuarios` AFTER UPDATE ON `roles` FOR EACH ROW BEGIN
    IF NEW.estado = 'DESHABILITADO' THEN
        UPDATE usuarios
        SET estado = 'INACTIVO'
        WHERE id_rol = NEW.id_rol;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre_servicio` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre_servicio`, `descripcion`, `precio`) VALUES
(1, 'Cambio de aceite', 'Cambio de aceite y filtro para todo tipo de vehículos.', 5.00),
(2, 'Revisión de frenos', 'Inspección y ajuste del sistema de frenos.', 30.50),
(3, 'Alineación de dirección', 'Ajuste de dirección y alineación de ruedas.', 60.00),
(4, 'Cambio de bujías', 'Reemplazo de bujías en motores de gasolina.', 25.00),
(5, 'Reemplazo de batería', 'Sustitución de batería por una nueva.', 80.00),
(6, 'Servicio de lavado', 'Lavado exterior e interior del vehículo.', 15.00),
(7, 'Reemplazo de pastillas de freno', 'Sustitución de pastillas de freno.', 40.00),
(8, 'Cambio de filtros', 'Reemplazo de filtros de aire y aceite.', 20.00),
(9, 'Inspección de suspensión', 'Revisión del sistema de suspensión y amortiguadores.', 35.00),
(10, 'Cambio de neumáticos', 'Reemplazo de neumáticos y alineación.', 200.00),
(11, 'Servicio de climatización', 'Revisión y reparación del sistema de aire acondicionado.', 75.00),
(12, 'Reparación de motor', 'Diagnóstico y reparación de problemas del motor.', 250.00),
(13, 'Inspección de escape', 'Revisión del sistema de escape y emisiones.', 40.00),
(14, 'Cambio de mangueras', 'Reemplazo de mangueras de radiador y refrigerante.', 30.00),
(15, 'Reparación de transmisión', 'Diagnóstico y reparación de transmisión automática.', 300.00),
(16, 'Servicio de electricista', 'Revisión del sistema eléctrico del vehículo.', 50.00),
(17, 'Servicio de afinación', 'Ajuste y mantenimiento del motor para un mejor rendimiento.', 90.00),
(18, 'Reparación de luces', 'Sustitución de luces delanteras y traseras.', 20.00),
(19, 'Mantenimiento preventivo', 'Revisión general del vehículo y mantenimiento preventivo.', 100.00),
(20, 'Reparación de carrocería', 'Reparación de daños en la carrocería del vehículo.', 150.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_cotizaciones`
--

CREATE TABLE `servicios_cotizaciones` (
  `id_serviciosPedidos` int(11) NOT NULL,
  `id_cotizacion` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `estado` enum('pendiente','aprobado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios_cotizaciones`
--

INSERT INTO `servicios_cotizaciones` (`id_serviciosPedidos`, `id_cotizacion`, `id_servicio`, `estado`) VALUES
(1, 1, 1, 'pendiente'),
(2, 1, 2, 'pendiente'),
(3, 2, 3, 'pendiente'),
(4, 2, 9, 'pendiente'),
(5, 3, 2, 'pendiente'),
(6, 3, 1, 'pendiente'),
(7, 3, 7, 'pendiente'),
(8, 3, 8, 'pendiente'),
(9, 4, 10, 'pendiente'),
(10, 4, 4, 'pendiente'),
(11, 5, 5, 'pendiente'),
(12, 5, 4, 'pendiente'),
(13, 6, 2, 'pendiente'),
(14, 7, 18, '');

--
-- Disparadores `servicios_cotizaciones`
--
DELIMITER $$
CREATE TRIGGER `actualizar_total_cotizacion_servicios` AFTER INSERT ON `servicios_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el nuevo total de la cotización sumando los precios de los servicios y subtotales de los repuestos
    UPDATE cotizaciones
    SET total = (
        SELECT COALESCE(SUM(s.precio), 0) + COALESCE((
            SELECT SUM(r.subtotal)
            FROM repuestos_cotizaciones AS r
            WHERE r.id_cotizacion = NEW.id_cotizacion
        ), 0)
        FROM servicios AS s
        JOIN servicios_cotizaciones AS sc ON s.id_servicio = sc.id_servicio
        WHERE sc.id_cotizacion = NEW.id_cotizacion
    )
    WHERE id_cotizacion = NEW.id_cotizacion;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_total_cotizacion_servicios_after_delete` AFTER DELETE ON `servicios_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el nuevo total de la cotización sumando los precios de los servicios y subtotales de los repuestos
    UPDATE cotizaciones
    SET total = (
        SELECT COALESCE(SUM(s.precio), 0) + COALESCE((
            SELECT SUM(r.subtotal)
            FROM repuestos_cotizaciones AS r
            WHERE r.id_cotizacion = OLD.id_cotizacion
        ), 0)
        FROM servicios AS s
        JOIN servicios_cotizaciones AS sc ON s.id_servicio = sc.id_servicio
        WHERE sc.id_cotizacion = OLD.id_cotizacion
    )
    WHERE id_cotizacion = OLD.id_cotizacion;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_total_cotizacion_servicios_after_update` AFTER UPDATE ON `servicios_cotizaciones` FOR EACH ROW BEGIN
    -- Calcular el nuevo total de la cotización sumando los precios de los servicios y subtotales de los repuestos
    UPDATE cotizaciones
    SET total = (
        SELECT COALESCE(SUM(s.precio), 0) + COALESCE((
            SELECT SUM(r.subtotal)
            FROM repuestos_cotizaciones AS r
            WHERE r.id_cotizacion = NEW.id_cotizacion
        ), 0)
        FROM servicios AS s
        JOIN servicios_cotizaciones AS sc ON s.id_servicio = sc.id_servicio
        WHERE sc.id_cotizacion = NEW.id_cotizacion
    )
    WHERE id_cotizacion = NEW.id_cotizacion;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `foto` text DEFAULT '../img/subidas/usuario.jpg',
  `id_rol` int(11) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `generico` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `correo`, `contraseña`, `foto`, `id_rol`, `estado`, `generico`) VALUES
(1, 'admin123', 'admin@gmail.com', 'itca321@', '../img/subidas/usuario.jpg', 1, 'ACTIVO', NULL),
(2, 'vice123', 'vice@gmail.com', 'itca321@', '../img/subidas/usuario.jpg', 2, 'ACTIVO', NULL),
(3, 'user123', 'user@gmail.com', 'itca321@', '../img/subidas/usuario.jpg', 3, 'ACTIVO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_vehiculo` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `año` int(4) NOT NULL,
  `color` varchar(50) NOT NULL DEFAULT 'Indefinido',
  `placas` varchar(8) NOT NULL,
  `chasis` varchar(25) NOT NULL,
  `motor` varchar(25) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `vin` varchar(30) DEFAULT NULL,
  `odometro` int(10) DEFAULT NULL,
  `unidad_medida` enum('KM','MI') DEFAULT 'KM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_vehiculo`, `id_cliente`, `marca`, `modelo`, `año`, `color`, `placas`, `chasis`, `motor`, `tipo`, `vin`, `odometro`, `unidad_medida`) VALUES
(1, 1, 'Toyota', 'Corolla', 2020, 'Blanco', 'PAB1234', '1HGBH41JXMN109186', 'ABC123456', 'Sedán', 'JTDBL40E0B0078886', 15000, 'KM'),
(2, 2, 'Honda', 'Civic', 2019, 'Negro', 'PBC2345', '1HGBH41JXMN109187', 'DEF234567', 'Sedán', '2HGEJ6612YH539647', 20000, 'KM'),
(3, 3, 'Ford', 'Focus', 2021, 'Rojo', 'PCD3456', '1HGBH41JXMN109188', 'GHI345678', 'Hatchback', '1FAHP3F26CL162093', 12000, 'KM'),
(4, 4, 'Chevrolet', 'Malibu', 2018, 'Azul', 'PED4567', '1HGBH41JXMN109189', 'JKL456789', 'Sedán', '1G1ZB5E06JF150301', 30000, 'KM'),
(5, 5, 'Nissan', 'Altima', 2022, 'Gris', 'PFE5678', '1HGBH41JXMN109190', 'MNO567890', 'Sedán', '1N4BL4BV6JC160018', 8000, 'KM'),
(6, 6, 'Hyundai', 'Elantra', 2020, 'Verde', 'PGH6789', '1HGBH41JXMN109191', 'PQR678901', 'Sedán', '5NPD84LF0LH351645', 18000, 'KM'),
(7, 7, 'Kia', 'Forte', 2019, 'Naranja', 'PHI7890', '1HGBH41JXMN109192', 'STU789012', 'Sedán', 'KNAFZ4A2XK5181920', 25000, 'KM'),
(8, 8, 'Volkswagen', 'Jetta', 2021, 'Morado', 'PJK8901', '1HGBH41JXMN109193', 'VWX890123', 'Sedán', '3VWD67AJ1HM228850', 9000, 'KM'),
(9, 9, 'Subaru', 'Impreza', 2018, 'Amarillo', 'PLK9012', '1HGBH41JXMN109194', 'YZA901234', 'Sedán', 'JF1GJAA66JH279920', 35000, 'KM'),
(10, 10, 'Mazda', '3', 2022, 'Blanco', 'PMN0123', '1HGBH41JXMN109195', 'BCD012345', 'Hatchback', 'JM1BN1K8XJ1612367', 11000, 'KM'),
(11, 11, 'Chrysler', '300', 2019, 'Negro', 'PQR1234', '1HGBH41JXMN109196', 'EFG123456', 'Sedán', '2C3CCACG5KH507895', 28000, 'KM'),
(12, 12, 'Dodge', 'Charger', 2020, 'Rojo', 'PSA2345', '1HGBH41JXMN109197', 'HIJ234567', 'Sedán', '2C3CDXBG1KH510283', 17000, 'KM'),
(13, 13, 'Tesla', 'Model 3', 2022, 'Gris', 'PTB3456', '1HGBH41JXMN109198', 'KLM345678', 'Sedán', '5YJ3E1EA2KF292349', 5000, 'KM'),
(14, 14, 'Jeep', 'Cherokee', 2021, 'Verde', 'PUC4567', '1HGBH41JXMN109199', 'NOP456789', 'SUV', '1C4PJMCX8JD536527', 12000, 'KM'),
(15, 15, 'GMC', 'Sierra', 2020, 'Azul', 'PVE5678', '1HGBH41JXMN109200', 'QRS567890', 'Camioneta', '1GTU9CED1KZ169170', 16000, 'KM'),
(16, 16, 'Ford', 'Explorer', 2019, 'Naranja', 'PWF6789', '1HGBH41JXMN109201', 'TUV678901', 'SUV', '1FM5K8D80KGA00541', 22000, 'KM'),
(17, 17, 'Toyota', 'RAV4', 2021, 'Morado', 'PXG7890', '1HGBH41JXMN109202', 'WXY789012', 'SUV', '2T3BFREV7HW108437', 14000, 'KM'),
(18, 18, 'Honda', 'Pilot', 2020, 'Amarillo', 'PYH8901', '1HGBH41JXMN109203', 'ZAB890123', 'SUV', '5FNYF6H13LB060455', 19000, 'KM'),
(19, 19, 'Chevrolet', 'Tahoe', 2022, 'Blanco', 'PZI9012', '1HGBH41JXMN109204', 'CDE901234', 'SUV', '1GNSKBKC5JR212312', 6000, 'KM'),
(20, 20, 'Nissan', 'Rogue', 2019, 'Negro', 'PAA0123', '1HGBH41JXMN109205', 'FGH012345', 'SUV', '5N1AT2MV1KC874598', 23000, 'KM'),
(21, 6, 'Chevrolet', 'Sonic', 2018, 'Rojo', 'PGA2345', '1G1JC5SH6J4101234', 'L3B124567', 'Sedán', '1G1JC5SH6J4101234', 60000, 'KM'),
(22, 7, 'Toyota', 'Corolla', 2019, 'Negro', 'PHB3456', '5YFBURHE8KP123456', 'V6G312345', 'Sedán', '5YFBURHE8KP123456', 45000, 'KM'),
(23, 8, 'Ford', 'Focus', 2020, 'Blanco', 'PCI4567', '1FADP3F27JL123456', 'S4M456789', 'Hatchback', '1FADP3F27JL123456', 30000, 'KM'),
(24, 9, 'Nissan', 'Versa', 2021, 'Azul', 'PDA5678', '3N1CN7AP6ML123456', 'H5N789012', 'Sedán', '3N1CN7AP6ML123456', 25000, 'KM'),
(25, 10, 'Hyundai', 'Elantra', 2022, 'Gris', 'PEA6789', '5NPD84LF3NH123456', 'J7M123456', 'Sedán', '5NPD84LF3NH123456', 10000, 'KM');

-- --------------------------------------------------------

--
-- Estructura para la vista `cotizacionesserviciosvista`
--
DROP TABLE IF EXISTS `cotizacionesserviciosvista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cotizacionesserviciosvista`  AS SELECT `ct`.`id_cotizacion` AS `id_cotizacion`, `ct`.`fecha_pedido` AS `fecha_pedido`, `ct`.`fecha_expiración` AS `fecha_expiración`, `ct`.`total` AS `total`, `ct`.`estado` AS `estado`, `cl`.`nombres` AS `nombres`, `cl`.`apellidos` AS `apellidos`, `cl`.`id_cliente` AS `id_cliente`, `vh`.`marca` AS `marca`, `vh`.`modelo` AS `modelo`, `vh`.`año` AS `año`, `vh`.`color` AS `color`, `vh`.`placas` AS `placas`, `vh`.`id_vehiculo` AS `id_vehiculo`, `mc`.`nombre` AS `mecánico`, `mc`.`id_mecánico` AS `id_mecánico`, `sv`.`id_servicio` AS `id_servicio`, `sv`.`nombre_servicio` AS `nombre_servicio`, `sv`.`descripcion` AS `descripcion_servicio`, `sv`.`precio` AS `precio_servicio` FROM (((((`cotizaciones` `ct` join `mecánicos` `mc`) join `vehiculos` `vh`) join `clientes` `cl`) join `servicios_cotizaciones` `sc`) join `servicios` `sv`) WHERE `mc`.`id_mecánico` = `ct`.`id_mecánico` AND `ct`.`id_vehiculo` = `vh`.`id_vehiculo` AND `vh`.`id_cliente` = `cl`.`id_cliente` AND `sc`.`id_cotizacion` = `ct`.`id_cotizacion` AND `sc`.`id_servicio` = `sv`.`id_servicio` GROUP BY `ct`.`id_cotizacion`, `sc`.`id_servicio` ORDER BY `vh`.`id_vehiculo` ASC, `ct`.`fecha_pedido` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cotizacionesvista`
--
DROP TABLE IF EXISTS `cotizacionesvista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cotizacionesvista`  AS SELECT `ct`.`id_cotizacion` AS `id_cotizacion`, `ct`.`fecha_pedido` AS `fecha_pedido`, `ct`.`fecha_expiración` AS `fecha_expiración`, `ct`.`total` AS `total`, `ct`.`estado` AS `estado`, `cl`.`nombres` AS `nombres`, `cl`.`apellidos` AS `apellidos`, `cl`.`id_cliente` AS `id_cliente`, `vh`.`marca` AS `marca`, `vh`.`modelo` AS `modelo`, `vh`.`año` AS `año`, `vh`.`color` AS `color`, `vh`.`placas` AS `placas`, `vh`.`id_vehiculo` AS `id_vehiculo`, `mc`.`nombre` AS `mecánico`, `mc`.`id_mecánico` AS `id_mecánico`, `rp`.`id_repuesto` AS `id_repuesto`, `rp`.`nombre_repuesto` AS `nombre_repuesto`, `rp`.`marca` AS `marca_repuesto`, `rp`.`precio_unitario` AS `precio_unitario`, `rp`.`proveedor` AS `proveedor`, `rc`.`cantidad` AS `cantidad_repuesto`, `rp`.`para` AS `repuesto_para` FROM (((((`cotizaciones` `ct` join `mecánicos` `mc`) join `vehiculos` `vh`) join `clientes` `cl`) join `repuestos_cotizaciones` `rc`) join `repuestos` `rp`) WHERE `mc`.`id_mecánico` = `ct`.`id_mecánico` AND `ct`.`id_vehiculo` = `vh`.`id_vehiculo` AND `vh`.`id_cliente` = `cl`.`id_cliente` AND `rc`.`id_cotizacion` = `ct`.`id_cotizacion` AND `rc`.`id_repuesto` = `rp`.`id_repuesto` GROUP BY `ct`.`id_cotizacion`, `rc`.`id_repuesto` ORDER BY `vh`.`id_vehiculo` ASC, `ct`.`fecha_pedido` ASC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos_roles`
--
ALTER TABLE `accesos_roles`
  ADD PRIMARY KEY (`id_acceso`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD KEY `id_mecánico` (`id_mecánico`),
  ADD KEY `cotizacion_ibfk_1` (`id_vehiculo`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`);

--
-- Indices de la tabla `mecánicos`
--
ALTER TABLE `mecánicos`
  ADD PRIMARY KEY (`id_mecánico`);

--
-- Indices de la tabla `repuestos`
--
ALTER TABLE `repuestos`
  ADD PRIMARY KEY (`id_repuesto`),
  ADD KEY `id_proveedor` (`proveedor`);

--
-- Indices de la tabla `repuestos_cotizaciones`
--
ALTER TABLE `repuestos_cotizaciones`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_pedido` (`id_cotizacion`),
  ADD KEY `repuestos_pedido_ibfk_2` (`id_repuesto`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `servicios_cotizaciones`
--
ALTER TABLE `servicios_cotizaciones`
  ADD PRIMARY KEY (`id_serviciosPedidos`),
  ADD KEY `servicios_pedidos_ibfk_2` (`id_cotizacion`),
  ADD KEY `servicios_cotizaciones_ibfk_2` (`id_servicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `usuarios_ibfk_1` (`id_rol`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_vehiculo`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos_roles`
--
ALTER TABLE `accesos_roles`
  MODIFY `id_acceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mecánicos`
--
ALTER TABLE `mecánicos`
  MODIFY `id_mecánico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `repuestos`
--
ALTER TABLE `repuestos`
  MODIFY `id_repuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `repuestos_cotizaciones`
--
ALTER TABLE `repuestos_cotizaciones`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `servicios_cotizaciones`
--
ALTER TABLE `servicios_cotizaciones`
  MODIFY `id_serviciosPedidos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesos_roles`
--
ALTER TABLE `accesos_roles`
  ADD CONSTRAINT `accesos_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD CONSTRAINT `cotizaciones_ibfk_1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`),
  ADD CONSTRAINT `cotizaciones_ibfk_2` FOREIGN KEY (`id_mecánico`) REFERENCES `mecánicos` (`id_mecánico`);

--
-- Filtros para la tabla `repuestos_cotizaciones`
--
ALTER TABLE `repuestos_cotizaciones`
  ADD CONSTRAINT `repuestos_cotizaciones_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizaciones` (`id_cotizacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `repuestos_cotizaciones_ibfk_2` FOREIGN KEY (`id_repuesto`) REFERENCES `repuestos` (`id_repuesto`);

--
-- Filtros para la tabla `servicios_cotizaciones`
--
ALTER TABLE `servicios_cotizaciones`
  ADD CONSTRAINT `servicios_cotizaciones_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizaciones` (`id_cotizacion`) ON DELETE CASCADE,
  ADD CONSTRAINT `servicios_cotizaciones_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;