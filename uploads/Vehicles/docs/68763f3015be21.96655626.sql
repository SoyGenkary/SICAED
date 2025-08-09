-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-07-2025 a las 08:38:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `flotavehicularmppe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `id_asignacion` int(11) NOT NULL,
  `id_personal` int(11) DEFAULT NULL,
  `id_vehiculo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clavesmaestras`
--

CREATE TABLE `clavesmaestras` (
  `id_clave` int(11) NOT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clavesmaestras`
--

INSERT INTO `clavesmaestras` (`id_clave`, `clave`) VALUES
(1, '$2a$12$n9Dcg7XyC9wgs6SZP.oE1eNTocVOMYUDBD2mqvukwby.C/2t0ksCm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emails`
--

CREATE TABLE `emails` (
  `id_email` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `emails`
--

INSERT INTO `emails` (`id_email`, `email`) VALUES
(2, '1234@gmail.com'),
(3, 'AnaMaria@gmail.com'),
(4, 'Genkary@gmail.com'),
(1, 'GenkaryX@gmail.com'),
(5, 'mari@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `estado`) VALUES
(1, 'Amazonas'),
(2, 'Anzoátegui'),
(3, 'Apure'),
(4, 'Aragua'),
(5, 'Barinas'),
(6, 'Bolívar'),
(7, 'Carabobo'),
(8, 'Cojedes'),
(9, 'Delta Amacuro'),
(10, 'Distrito Capital'),
(11, 'Falcón'),
(12, 'Guárico'),
(13, 'La Guaira'),
(14, 'Lara'),
(15, 'Mérida'),
(16, 'Miranda'),
(17, 'Monagas'),
(18, 'Nueva Esparta'),
(19, 'Portuguesa'),
(20, 'Sucre'),
(21, 'Táchira'),
(22, 'Trujillo'),
(23, 'Yaracuy'),
(24, 'Zulia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `tipo_mantenimiento` varchar(100) DEFAULT NULL,
  `fecha_mantenimiento` date DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `taller` varchar(150) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ruta_documentos` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` int(11) NOT NULL,
  `municipio` varchar(100) NOT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `municipio`, `id_estado`) VALUES
(1, 'Alto Orinoco', 1),
(2, 'Atabapo', 1),
(3, 'Atures', 1),
(4, 'Autana', 1),
(5, 'Manapiare', 1),
(6, 'Maroa', 1),
(7, 'Río Negro', 1),
(8, 'Anaco', 2),
(9, 'Aragua', 2),
(10, 'Diego Bautista Urbaneja', 2),
(11, 'Fernando Peñalver', 2),
(12, 'Francisco Del Carmen Carvajal', 2),
(13, 'Francisco de Miranda', 2),
(14, 'Guanta', 2),
(15, 'Independencia', 2),
(16, 'José Gregorio Monagas', 2),
(17, 'Juan Antonio Sotillo', 2),
(18, 'Juan Manuel Cajigal', 2),
(19, 'Libertad', 2),
(20, 'Manuel Ezequiel Bruzual', 2),
(21, 'Pedro María Freites', 2),
(22, 'Píritu', 2),
(23, 'San José de Guanipa', 2),
(24, 'San Juan de Capistrano', 2),
(25, 'Santa Ana', 2),
(26, 'Simón Bolívar', 2),
(27, 'Simón Rodríguez', 2),
(28, 'Sir Arthur McGregor', 2),
(29, 'Achaguas', 3),
(30, 'Biruaca', 3),
(31, 'Muñoz', 3),
(32, 'Páez', 3),
(33, 'Pedro Camejo', 3),
(34, 'Rómulo Gallegos', 3),
(35, 'San Fernando', 3),
(36, 'Bolívar', 4),
(37, 'Camatagua', 4),
(38, 'Francisco Linares Alcántara', 4),
(39, 'Girardot', 4),
(40, 'José Ángel Lamas', 4),
(41, 'José Félix Ribas', 4),
(42, 'José Rafael Revenga', 4),
(43, 'Libertador', 4),
(44, 'Mario Briceño Iragorry', 4),
(45, 'Ocumare de la Costa de Oro', 4),
(46, 'San Casimiro', 4),
(47, 'San Sebastián', 4),
(48, 'Santiago Mariño', 4),
(49, 'Santos Michelena', 4),
(50, 'Sucre', 4),
(51, 'Tovar', 4),
(52, 'Urdaneta', 4),
(53, 'Zamora', 4),
(54, 'Alberto Arvelo Torrealba', 5),
(55, 'Andrés Eloy Blanco', 5),
(56, 'Antonio José de Sucre', 5),
(57, 'Arismendi', 5),
(58, 'Barinas', 5),
(59, 'Bolívar', 5),
(60, 'Cruz Paredes', 5),
(61, 'Ezequiel Zamora', 5),
(62, 'Obispos', 5),
(63, 'Pedraza', 5),
(64, 'Rojas', 5),
(65, 'Sosa', 5),
(66, 'Angostura', 6),
(67, 'Caroní', 6),
(68, 'Cedeño', 6),
(69, 'El Callao', 6),
(70, 'Gran Sabana', 6),
(71, 'Heres', 6),
(72, 'Piar', 6),
(73, 'Roscio', 6),
(74, 'Sifontes', 6),
(75, 'Sucre', 6),
(76, 'Padre Pedro Chien', 6),
(77, 'Bejuma', 7),
(78, 'Carlos Arvelo', 7),
(79, 'Diego Ibarra', 7),
(80, 'Guacara', 7),
(81, 'Juan José Mora', 7),
(82, 'Libertador', 7),
(83, 'Los Guayos', 7),
(84, 'Miranda', 7),
(85, 'Montalbán', 7),
(86, 'Naguanagua', 7),
(87, 'Puerto Cabello', 7),
(88, 'San Diego', 7),
(89, 'San Joaquín', 7),
(90, 'Valencia', 7),
(91, 'Anzoátegui', 8),
(92, 'Ezequiel Zamora', 8),
(93, 'Girardot', 8),
(94, 'Lima Blanco', 8),
(95, 'Pao de San Juan Bautista', 8),
(96, 'Ricaurte', 8),
(97, 'Rómulo Gallegos', 8),
(98, 'San Carlos', 8),
(99, 'Tinaco', 8),
(100, 'Antonio Díaz', 9),
(101, 'Casacoima', 9),
(102, 'Pedernales', 9),
(103, 'Tucupita', 9),
(104, 'Libertador', 10),
(105, 'Acosta', 11),
(106, 'Bolívar', 11),
(107, 'Buchivacoa', 11),
(108, 'Cacique Manaure', 11),
(109, 'Carirubana', 11),
(110, 'Colina', 11),
(111, 'Dabajuro', 11),
(112, 'Democracia', 11),
(113, 'Falcón', 11),
(114, 'Federación', 11),
(115, 'Jacura', 11),
(116, 'Los Taques', 11),
(117, 'Mauroa', 11),
(118, 'Miranda', 11),
(119, 'Monseñor Iturriza', 11),
(120, 'Palmasola', 11),
(121, 'Petit', 11),
(122, 'Píritu', 11),
(123, 'San Francisco', 11),
(124, 'Silva', 11),
(125, 'Sucre', 11),
(126, 'Tocópero', 11),
(127, 'Unión', 11),
(128, 'Urumaco', 11),
(129, 'Zamora', 11),
(130, 'Camaguán', 12),
(131, 'Chaguaramas', 12),
(132, 'El Socorro', 12),
(133, 'Francisco de Miranda', 12),
(134, 'José Félix Ribas', 12),
(135, 'José Tadeo Monagas', 12),
(136, 'Juan Germán Roscio', 12),
(137, 'Julián Mellado', 12),
(138, 'Las Mercedes', 12),
(139, 'Leonardo Infante', 12),
(140, 'Ortiz', 12),
(141, 'Pedro Zaraza', 12),
(142, 'San Gerónimo de Guayabal', 12),
(143, 'San José de Guaribe', 12),
(144, 'Santa María de Ipire', 12),
(145, 'Vargas', 13),
(146, 'Andrés Eloy Blanco', 14),
(147, 'Crespo', 14),
(148, 'Iribarren', 14),
(149, 'Jiménez', 14),
(150, 'Morán', 14),
(151, 'Palavecino', 14),
(152, 'Simón Planas', 14),
(153, 'Torres', 14),
(154, 'Urdaneta', 14),
(155, 'Alberto Adriani', 15),
(156, 'Andrés Bello', 15),
(157, 'Antonio Pinto Salinas', 15),
(158, 'Aricagua', 15),
(159, 'Arzobispo Chacón', 15),
(160, 'Campo Elías', 15),
(161, 'Caracciolo Parra Olmedo', 15),
(162, 'Cardenal Quintero', 15),
(163, 'Guaraque', 15),
(164, 'Julio César Salas', 15),
(165, 'Justo Briceño', 15),
(166, 'Libertador', 15),
(167, 'Miranda', 15),
(168, 'Obispo Ramos de Lora', 15),
(169, 'Padre Noguera', 15),
(170, 'Pueblo Llano', 15),
(171, 'Rangel', 15),
(172, 'Rivas Dávila', 15),
(173, 'Santos Marquina', 15),
(174, 'Sucre', 15),
(175, 'Tovar', 15),
(176, 'Tulio Febres Cordero', 15),
(177, 'Zea', 15),
(178, 'Acevedo', 16),
(179, 'Andrés Bello', 16),
(180, 'Baruta', 16),
(181, 'Brión', 16),
(182, 'Buroz', 16),
(183, 'Carrizal', 16),
(184, 'Chacao', 16),
(185, 'Cristóbal Rojas', 16),
(186, 'El Hatillo', 16),
(187, 'Guaicaipuro', 16),
(188, 'Independencia', 16),
(189, 'Lander', 16),
(190, 'Los Salias', 16),
(191, 'Páez', 16),
(192, 'Paz Castillo', 16),
(193, 'Pedro Gual', 16),
(194, 'Plaza', 16),
(195, 'Simón Bolívar', 16),
(196, 'Sucre', 16),
(197, 'Urdaneta', 16),
(198, 'Zamora', 16),
(199, 'Acosta', 17),
(200, 'Aguasay', 17),
(201, 'Bolívar', 17),
(202, 'Caripe', 17),
(203, 'Cedeño', 17),
(204, 'Ezequiel Zamora', 17),
(205, 'Libertador', 17),
(206, 'Maturín', 17),
(207, 'Piar', 17),
(208, 'Punceres', 17),
(209, 'Santa Bárbara', 17),
(210, 'Sotillo', 17),
(211, 'Uracoa', 17),
(212, 'Antolín del Campo', 18),
(213, 'Arismendi', 18),
(214, 'Díaz', 18),
(215, 'García', 18),
(216, 'Gómez', 18),
(217, 'Maneiro', 18),
(218, 'Marcano', 18),
(219, 'Mariño', 18),
(220, 'Península de Macanao', 18),
(221, 'Tubores', 18),
(222, 'Villalba', 18),
(223, 'Agua Blanca', 19),
(224, 'Araure', 19),
(225, 'Esteller', 19),
(226, 'Guanare', 19),
(227, 'Guanarito', 19),
(228, 'Monseñor José Vicente de Unda', 19),
(229, 'Ospino', 19),
(230, 'Páez', 19),
(231, 'Papelón', 19),
(232, 'San Genaro de Boconoíto', 19),
(233, 'San Rafael de Onoto', 19),
(234, 'Santa Rosalía', 19),
(235, 'Sucre', 19),
(236, 'Turén', 19),
(237, 'Andrés Eloy Blanco', 20),
(238, 'Andrés Mata', 20),
(239, 'Arismendi', 20),
(240, 'Benítez', 20),
(241, 'Bermúdez', 20),
(242, 'Bolívar', 20),
(243, 'Cajigal', 20),
(244, 'Cruz Salmerón Acosta', 20),
(245, 'Libertador', 20),
(246, 'Mariño', 20),
(247, 'Mejía', 20),
(248, 'Montes', 20),
(249, 'Ribero', 20),
(250, 'Sucre', 20),
(251, 'Valdez', 20),
(252, 'Andrés Bello', 21),
(253, 'Antonio Rómulo Costa', 21),
(254, 'Ayacucho', 21),
(255, 'Bolívar', 21),
(256, 'Cárdenas', 21),
(257, 'Córdoba', 21),
(258, 'Fernández Feo', 21),
(259, 'Francisco de Miranda', 21),
(260, 'García de Hevia', 21),
(261, 'Guásimos', 21),
(262, 'Independencia', 21),
(263, 'Jáuregui', 21),
(264, 'José María Vargas', 21),
(265, 'Junín', 21),
(266, 'Libertad', 21),
(267, 'Libertador', 21),
(268, 'Lobatera', 21),
(269, 'Michelena', 21),
(270, 'Panamericano', 21),
(271, 'Pedro María Ureña', 21),
(272, 'Rafael Urdaneta', 21),
(273, 'Samuel Dario Maldonado', 21),
(274, 'San Cristóbal', 21),
(275, 'San Judas Tadeo', 21),
(276, 'Seboruco', 21),
(277, 'Simón Rodríguez', 21),
(278, 'Sucre', 21),
(279, 'Torbes', 21),
(280, 'Uribante', 21),
(281, 'Andrés Bello', 22),
(282, 'Boconó', 22),
(283, 'Bolívar', 22),
(284, 'Candelaria', 22),
(285, 'Carache', 22),
(286, 'Escuque', 22),
(287, 'José Felipe Márquez Cañizales', 22),
(288, 'Juan Vicente Campo Elías', 22),
(289, 'La Ceiba', 22),
(290, 'Miranda', 22),
(291, 'Monte Carmelo', 22),
(292, 'Motatán', 22),
(293, 'Pampán', 22),
(294, 'Pampanito', 22),
(295, 'Rafael Rangel', 22),
(296, 'San Rafael de Carvajal', 22),
(297, 'Sucre', 22),
(298, 'Trujillo', 22),
(299, 'Urdaneta', 22),
(300, 'Valera', 22),
(301, 'Arístides Bastidas', 23),
(302, 'Bolívar', 23),
(303, 'Bruzual', 23),
(304, 'Cocorote', 23),
(305, 'Independencia', 23),
(306, 'José Antonio Páez', 23),
(307, 'La Trinidad', 23),
(308, 'Manuel Monge', 23),
(309, 'Nirgua', 23),
(310, 'Peña', 23),
(311, 'San Felipe', 23),
(312, 'Sucre', 23),
(313, 'Urachiche', 23),
(314, 'Veroes', 23),
(315, 'Almirante Padilla', 24),
(316, 'Baralt', 24),
(317, 'Cabimas', 24),
(318, 'Catatumbo', 24),
(319, 'Colón', 24),
(320, 'Francisco Javier Pulgar', 24),
(321, 'Guajira', 24),
(322, 'Jesús Enrique Lossada', 24),
(323, 'Jesús María Semprún', 24),
(324, 'La Cañada de Urdaneta', 24),
(325, 'Lagunillas', 24),
(326, 'Machiques de Perijá', 24),
(327, 'Mara', 24),
(328, 'Maracaibo', 24),
(329, 'Miranda', 24),
(330, 'Rosario de Perijá', 24),
(331, 'San Francisco', 24),
(332, 'Santa Rita', 24),
(333, 'Simón Bolívar', 24),
(334, 'Sucre', 24),
(335, 'Valmore Rodríguez', 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id_personal` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `documento_identidad` varchar(50) NOT NULL,
  `id_municipio` int(11) DEFAULT NULL,
  `id_telefono` int(11) DEFAULT NULL,
  `id_email` int(11) DEFAULT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp(),
  `ruta_img` text DEFAULT NULL,
  `ruta_documentos` text DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registroauditorias`
--

CREATE TABLE `registroauditorias` (
  `id_registro` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(50) DEFAULT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `campo_afectado` varchar(100) DEFAULT NULL,
  `valor_antiguo` text DEFAULT NULL,
  `valor_nuevo` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registroauditorias`
--

INSERT INTO `registroauditorias` (`id_registro`, `fecha`, `id_usuario`, `accion`, `tabla_afectada`, `campo_afectado`, `valor_antiguo`, `valor_nuevo`, `descripcion`) VALUES
(2, '2025-07-03 02:51:08', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(3, '2025-07-03 02:51:10', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-03 04:51:10', 'Ha iniciado sesión correctamente.'),
(4, '2025-07-03 02:51:34', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado la foto de perfil.'),
(5, '2025-07-03 02:53:47', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(6, '2025-07-03 02:56:10', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-03 04:51:10', '2025-07-03 04:56:10', 'Ha iniciado sesión correctamente.'),
(7, '2025-07-05 22:02:54', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-03 04:56:10', '2025-07-06 00:02:54', 'Ha iniciado sesión correctamente.'),
(8, '2025-07-05 22:04:40', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(9, '2025-07-05 22:24:32', NULL, 'VEHICULO:CREAR', 'vehiculos', '*', '', '', 'Ha registrado el vehiculo con la matricula/vin: FALOPA'),
(10, '2025-07-05 22:25:11', NULL, 'VEHICULO:CREAR', 'vehiculos', '*', '', '', 'Ha registrado el vehiculo con la matricula/vin: '),
(11, '2025-07-05 22:26:23', NULL, 'VEHICULO:CREAR', 'vehiculos', '*', '', '', 'TR'),
(12, '2025-07-05 22:27:04', NULL, 'VEHICULO:CREAR', 'vehiculos', '*', '', '', 'Ha registrado el vehiculo con la matricula/vin: PE'),
(13, '2025-07-05 22:28:22', NULL, 'VEHICULO:CREAR', 'vehiculos', '*', '', '', 'Ha registrado el vehiculo con el vin: 1'),
(14, '2025-07-05 22:29:04', NULL, 'VEHICULO:CREAR', 'vehiculos', '*', '', '', 'Ha registrado un vehiculo con la matricula: XD'),
(15, '2025-07-07 00:21:22', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-06 00:02:54', '2025-07-07 02:21:22', 'Ha iniciado sesión correctamente.'),
(16, '2025-07-07 02:32:59', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', 'id_vehiculo', '', '', 'Se eliminó el vehículo con el vin: XD'),
(17, '2025-07-07 02:39:32', NULL, 'VEHICULO:MODIFY', 'vehiculos', 'id_vehiculo', '', '', 'Se actualizó el vehículo con la matricula: FALOPA'),
(18, '2025-07-07 02:48:52', NULL, 'VEHICULO:MODIFY', 'vehiculos', '1', '', '', 'Se actualizó el vehículo con la matricula: GENKARY'),
(19, '2025-07-07 02:53:19', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '1', '', '', 'Se eliminó el documento: [Resumen Anthony Travieso.pdf] del vehiculo con la matricula: GENKARY'),
(20, '2025-07-07 02:55:21', NULL, 'VEHICULO:MODIFY', 'vehiculos', '1', '', '', 'Se actualizó el vehículo con la matricula: GENKARY'),
(21, '2025-07-07 02:55:29', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '1', '', '', 'Se eliminó la imagen extra: [Imagen de WhatsApp 2025-04-03 a las 11.11.36_2eeefb53.jpg] del vehiculo con la matricula: GENKARY'),
(22, '2025-07-07 02:59:01', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '1', '', '', 'Se eliminó la imagen extra: [images.jpg] del vehiculo con la matricula: GENKARY'),
(23, '2025-07-07 03:21:41', NULL, 'PERSONAL:ACTUALIZAR', 'personal', '1', '', '', 'Se actualizó el personal con CI V-31.255.522 para ser Conductor.'),
(24, '2025-07-07 03:22:27', NULL, 'PERSONAL:CREAR', 'personal', '2', '', '', 'Se creó el personal Anthony Alejandro Hernandez Travieso con CI V-11.111.111.'),
(25, '2025-07-07 03:23:11', NULL, 'PERSONAL:CREAR', 'personal', '3', '', '', 'Se creó el personal Ana Maria Gonzalez Perez con CI V-22.222.222.'),
(26, '2025-07-07 03:26:34', NULL, 'PERSONAL:ELIMINAR', 'personal', '3', '', '', 'Se eliminó el personal con CI V-22.222.222.'),
(27, '2025-07-07 03:28:40', NULL, 'PERSONAL:ELIMINAR', 'personal', '2', '', '', 'Se eliminó el personal con CI V-11.111.111.'),
(28, '2025-07-07 03:31:36', NULL, 'PERSONAL:ACTUALIZAR', 'personal', '1', '', '', 'Se actualizaron los datos del personal con CI V-31.255.522.'),
(29, '2025-07-07 03:33:45', NULL, 'PERSONAL:CREAR', 'personal', '4', '', '', 'Se creó el personal Ana Maria Gonzalez Perez con CI V-22.222.222.'),
(30, '2025-07-07 03:37:05', NULL, 'PERSONAL:ACTUALIZAR', 'personal', '1', '', '', 'Se actualizaron los datos del personal con CI V-31.255.522.'),
(31, '2025-07-07 03:37:14', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '1', '', '', 'Se eliminó el documento: [Mi experiencia es acerca de la programaciu00f3n.pdf] del personal: 1'),
(32, '2025-07-07 03:38:59', NULL, 'PERSONAL:ACTUALIZAR', 'personal', '1', '', '', 'Se actualizaron los datos del personal con CI V-31.255.522.'),
(33, '2025-07-07 03:39:05', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '1', '', '', 'Se eliminó el documento: [Mi experiencia es acerca de la programaciu00f3n.pdf] del personal: V-31.255.522'),
(34, '2025-07-07 03:46:51', NULL, 'MANTENIMIENTO:CREAR', 'mantenimientos', '1', '', '', 'Se creó un registro de mantenimiento para el vehículo con matrícula GENKARY.'),
(35, '2025-07-07 03:48:51', NULL, 'MANTENIMIENTO:ELIMINAR', 'mantenimientos', '1', '', '', 'Se eliminó el documento: [Mi experiencia es acerca de la programaciu00f3n.pdf] del registro de mantenimiento de ID: 1'),
(36, '2025-07-07 03:49:20', NULL, 'MANTENIMIENTO:ACTUALIZAR', 'mantenimientos', '1', '', '', 'Se actualizó el mantenimiento ID 1 para el vehículo con matrícula GENKARY.'),
(37, '2025-07-07 03:49:47', NULL, 'MANTENIMIENTO:ELIMINAR', 'mantenimientos', '1', '', '', 'Se eliminó el registro de mantenimiento con ID 1.'),
(38, '2025-07-07 16:38:04', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-07 02:21:22', '2025-07-07 18:38:04', 'Ha iniciado sesión correctamente.'),
(39, '2025-07-07 18:13:59', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(40, '2025-07-07 18:14:00', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-07 18:38:04', '2025-07-07 20:14:00', 'Ha iniciado sesión correctamente.'),
(41, '2025-07-07 22:02:33', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '9', '', '', 'Se eliminó el vehículo con la matricula: FALOPA'),
(42, '2025-07-07 22:02:33', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '1', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(43, '2025-07-07 22:02:50', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '14', '', '', 'Se eliminó el vehículo con la matricula: XD'),
(44, '2025-07-07 22:02:50', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '13', '', '', 'Se eliminó el vehículo con el vin: 1'),
(45, '2025-07-07 22:02:50', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '12', '', '', 'Se eliminó el vehículo con el vin: PE'),
(46, '2025-07-07 22:02:50', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '11', '', '', 'Se eliminó el vehículo con el vin: TR'),
(47, '2025-07-07 22:12:51', NULL, 'PERSONAL:ACTUALIZAR', 'personal', '1', '', '', 'Se actualizaron los datos del personal con CI V-31.255.522.'),
(48, '2025-07-07 22:13:18', NULL, 'PERSONAL:ELIMINAR', 'personal', '1', '', '', 'Se eliminó el personal con CI V-31.255.522.'),
(49, '2025-07-07 22:13:31', NULL, 'PERSONAL:ELIMINAR', 'personal', '4', '', '', 'Se eliminó el personal con CI V-22.222.222.'),
(50, '2025-07-07 22:14:41', NULL, 'VEHICULO:CREAR', 'vehiculos', '', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(51, '2025-07-07 22:14:44', NULL, 'VEHICULO:CREAR', 'vehiculos', '', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(52, '2025-07-07 22:14:51', NULL, 'VEHICULO:CREAR', 'vehiculos', '', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(53, '2025-07-07 22:15:22', NULL, 'VEHICULO:CREAR', 'vehiculos', '', '', '', 'Ha registrado un vehiculo con la matricula: ASD'),
(54, '2025-07-07 22:16:26', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '15', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(55, '2025-07-07 22:16:35', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '18', '', '', 'Se eliminó el vehículo con la matricula: ASD'),
(56, '2025-07-07 22:16:35', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '17', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(57, '2025-07-07 22:16:35', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '16', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(58, '2025-07-07 22:17:27', NULL, 'VEHICULO:CREAR', 'vehiculos', '', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(59, '2025-07-07 22:25:08', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '19', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(60, '2025-07-07 22:25:45', NULL, 'VEHICULO:CREAR', 'vehiculos', '', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(61, '2025-07-07 22:27:00', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '20', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(62, '2025-07-07 22:29:48', NULL, 'VEHICULO:CREAR', 'vehiculos', '21', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(63, '2025-07-07 22:30:48', NULL, 'PERSONAL:CREAR', 'personal', '5', '', '', 'Se creó el personal Anthony Alejandro Travieso Hernandez con CI V-31.255.522.'),
(64, '2025-07-07 22:31:22', NULL, 'VEHICULO:CREAR', 'vehiculos', '22', '', '', 'Ha registrado un vehiculo con la matricula: TESLA'),
(65, '2025-07-07 22:32:20', NULL, 'MANTENIMIENTO:CREAR', 'mantenimientos', '2', '', '', 'Se creó un registro de mantenimiento para el vehículo con matrícula TESLA.'),
(66, '2025-07-07 22:32:27', NULL, 'MANTENIMIENTO:ELIMINAR', 'mantenimientos', '2', '', '', 'Se eliminó el documento: [Mi experiencia es acerca de la programaciu00f3n.pdf] del registro de mantenimiento de ID: 2'),
(67, '2025-07-07 22:32:39', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '22', '', '', 'Se eliminó el vehículo con la matricula: TESLA'),
(68, '2025-07-07 22:32:39', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '21', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(69, '2025-07-07 22:32:39', NULL, 'PERSONAL:ELIMINAR', 'personal', '5', '', '', 'Se eliminó el personal con CI V-31.255.522.'),
(70, '2025-07-07 22:54:16', NULL, 'VEHICULO:CREAR', 'vehiculos', '23', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(71, '2025-07-09 16:45:51', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-07 20:14:00', '2025-07-09 18:45:51', 'Ha iniciado sesión correctamente.'),
(72, '2025-07-09 16:46:45', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '23', '', '', 'Se eliminó la imagen extra: [tara-exterior-big-03.webp] del vehiculo con la matricula: GENKARY'),
(73, '2025-07-09 16:46:53', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '23', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(74, '2025-07-09 16:50:14', NULL, 'VEHICULO:CREAR', 'vehiculos', '24', '', '', 'Ha registrado un vehiculo con la matricula: GENKARY'),
(75, '2025-07-09 17:32:36', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(76, '2025-07-09 17:32:44', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-09 18:45:51', '2025-07-09 19:32:44', 'Ha iniciado sesión correctamente.'),
(77, '2025-07-09 17:34:07', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-09 19:32:44', '2025-07-09 19:34:07', 'Ha iniciado sesión correctamente.'),
(78, '2025-07-09 17:35:22', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-09 19:34:07', '2025-07-09 19:35:22', 'Ha iniciado sesión correctamente.'),
(79, '2025-07-09 17:35:32', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-09 19:35:22', '2025-07-09 19:35:32', 'Ha iniciado sesión correctamente.'),
(80, '2025-07-09 17:43:37', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-09 19:35:32', '2025-07-09 19:43:37', 'Ha iniciado sesión correctamente.'),
(83, '2025-07-09 17:46:42', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(84, '2025-07-09 17:46:43', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 19:46:43', 'Ha iniciado sesión correctamente.'),
(87, '2025-07-09 17:47:59', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(88, '2025-07-09 17:48:00', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 19:48:00', 'Ha iniciado sesión correctamente.'),
(91, '2025-07-09 17:49:18', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(92, '2025-07-09 17:49:19', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 19:49:19', 'Ha iniciado sesión correctamente.'),
(95, '2025-07-09 17:56:18', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(96, '2025-07-09 17:56:20', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 19:56:20', 'Ha iniciado sesión correctamente.'),
(97, '2025-07-09 17:56:32', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado la foto de perfil.'),
(100, '2025-07-09 17:58:40', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(101, '2025-07-09 17:58:41', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 19:58:41', 'Ha iniciado sesión correctamente.'),
(102, '2025-07-09 17:58:53', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado la foto de perfil.'),
(103, '2025-07-09 17:59:09', 1, 'USUARIO:ELIMINAR', 'usuarios', '*', '', '', 'Se ha eliminado permanentemente la cuenta del usuario: Correo: Genkary@gmail.com, Nombre: Anthony Alejandro Travieso Hernández, Cédula: 31.255.522'),
(104, '2025-07-09 18:00:08', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(105, '2025-07-09 18:00:09', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 20:00:09', 'Ha iniciado sesión correctamente.'),
(106, '2025-07-09 18:00:32', 1, 'USUARIO:ELIMINAR', 'usuarios', '*', '', '', 'Se ha eliminado permanentemente la cuenta del usuario: Correo: Genkary@gmail.com, Nombre: Anthony Alejandro Travieso Hernández, Cédula: 31.255.522'),
(107, '2025-07-09 18:01:13', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(108, '2025-07-09 18:01:22', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 20:01:22', 'Ha iniciado sesión correctamente.'),
(109, '2025-07-09 18:01:28', 1, 'USUARIO:ELIMINAR', 'usuarios', '*', '', '', 'Se ha eliminado permanentemente la cuenta del usuario: Correo: Genkary@gmail.com, Nombre: Anthony Alejandro Travieso Hernández, Cédula: 31.255.522'),
(110, '2025-07-09 18:02:11', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(111, '2025-07-09 18:02:15', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 20:02:15', 'Ha iniciado sesión correctamente.'),
(112, '2025-07-09 18:02:22', 1, 'USUARIO:ELIMINAR', 'usuarios', '*', '', '', 'Se ha eliminado permanentemente la cuenta del usuario: Correo: Genkary@gmail.com, Nombre: Anthony Alejandro Travieso Hernández, Cédula: 31.255.522'),
(113, '2025-07-09 18:03:14', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(114, '2025-07-09 18:03:17', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-09 20:03:17', 'Ha iniciado sesión correctamente.'),
(115, '2025-07-09 18:12:17', 11, 'VEHICULO:CREAR', 'vehiculos', '25', '', '', 'Ha registrado un vehiculo con la matricula: FALOPAS'),
(116, '2025-07-09 18:13:57', 11, 'VEHICULO:CREAR', 'vehiculos', '26', '', '', 'Ha registrado un vehiculo con la matricula: FALOPA'),
(117, '2025-07-09 18:17:42', 11, 'VEHICULO:ELIMINAR', 'vehiculos', '25', '', '', 'Se eliminó el vehículo con la matricula: FALOPAS'),
(118, '2025-07-09 18:17:42', 11, 'VEHICULO:ELIMINAR', 'vehiculos', '26', '', '', 'Se eliminó el vehículo con la matricula: FALOPA'),
(119, '2025-07-10 00:15:51', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-09 20:03:17', '2025-07-10 02:15:51', 'Ha iniciado sesión correctamente.'),
(120, '2025-07-10 00:15:56', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(121, '2025-07-10 00:17:09', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-10 02:15:51', '2025-07-10 02:17:09', 'Ha iniciado sesión correctamente.'),
(122, '2025-07-10 02:12:07', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-10 02:17:09', '2025-07-10 04:12:07', 'Ha iniciado sesión correctamente.'),
(123, '2025-07-10 02:12:43', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(124, '2025-07-10 20:04:53', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-10 04:12:07', '2025-07-10 22:04:53', 'Ha iniciado sesión correctamente.'),
(125, '2025-07-10 20:05:06', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(126, '2025-07-10 20:05:25', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-10 22:04:53', '2025-07-10 22:05:25', 'Ha iniciado sesión correctamente.'),
(127, '2025-07-10 20:06:34', 11, 'VEHICULO:ELIMINAR', 'vehiculos', '24', '', '', 'Se eliminó el documento: [Resumen Jesu00fas Sequea.pdf] del vehiculo con la matricula: GENKARY'),
(128, '2025-07-10 20:06:35', 11, 'VEHICULO:ELIMINAR', 'vehiculos', '24', '', '', 'Se eliminó el documento: [Mi experiencia es acerca de la programaciu00f3n.pdf] del vehiculo con la matricula: GENKARY'),
(129, '2025-07-10 20:06:37', 11, 'VEHICULO:ELIMINAR', 'vehiculos', '24', '', '', 'Se eliminó el documento: [Resumen Anthony Travieso.pdf] del vehiculo con la matricula: GENKARY'),
(130, '2025-07-10 21:17:37', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(131, '2025-07-10 21:51:27', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: GenkaryX@gmail.com y Cédula: 31.255.522'),
(132, '2025-07-10 21:52:30', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary7@gmail.com y Cédula: 31.255.522'),
(133, '2025-07-10 22:07:43', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-10 22:05:25', '2025-07-11 00:07:43', 'Ha iniciado sesión correctamente.'),
(134, '2025-07-10 22:07:45', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:07:43', '2025-07-11 00:07:45', 'Ha iniciado sesión correctamente.'),
(135, '2025-07-10 22:10:27', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:07:45', '2025-07-11 00:10:27', 'Ha iniciado sesión correctamente.'),
(136, '2025-07-10 22:10:38', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:10:27', '2025-07-11 00:10:38', 'Ha iniciado sesión correctamente.'),
(137, '2025-07-10 22:10:53', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:10:38', '2025-07-11 00:10:53', 'Ha iniciado sesión correctamente.'),
(138, '2025-07-10 22:11:13', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:10:53', '2025-07-11 00:11:13', 'Ha iniciado sesión correctamente.'),
(139, '2025-07-10 22:11:30', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:11:13', '2025-07-11 00:11:30', 'Ha iniciado sesión correctamente.'),
(140, '2025-07-10 22:12:15', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:11:30', '2025-07-11 00:12:15', 'Ha iniciado sesión correctamente.'),
(141, '2025-07-10 22:12:17', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:12:15', '2025-07-11 00:12:17', 'Ha iniciado sesión correctamente.'),
(142, '2025-07-10 22:13:48', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:12:17', '2025-07-11 00:13:48', 'Ha iniciado sesión correctamente.'),
(143, '2025-07-10 22:17:20', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:13:48', '2025-07-11 00:17:20', 'Ha iniciado sesión correctamente.'),
(144, '2025-07-10 22:17:22', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:20', '2025-07-11 00:17:22', 'Ha iniciado sesión correctamente.'),
(145, '2025-07-10 22:17:25', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:22', '2025-07-11 00:17:25', 'Ha iniciado sesión correctamente.'),
(146, '2025-07-10 22:17:26', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:25', '2025-07-11 00:17:26', 'Ha iniciado sesión correctamente.'),
(147, '2025-07-10 22:17:26', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:26', '2025-07-11 00:17:26', 'Ha iniciado sesión correctamente.'),
(148, '2025-07-10 22:17:26', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:26', '2025-07-11 00:17:26', 'Ha iniciado sesión correctamente.'),
(149, '2025-07-10 22:17:26', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:26', '2025-07-11 00:17:26', 'Ha iniciado sesión correctamente.'),
(150, '2025-07-10 22:17:26', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:26', '2025-07-11 00:17:26', 'Ha iniciado sesión correctamente.'),
(151, '2025-07-10 22:17:29', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:26', '2025-07-11 00:17:29', 'Ha iniciado sesión correctamente.'),
(152, '2025-07-10 22:21:14', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:17:29', '2025-07-11 00:21:14', 'Ha iniciado sesión correctamente.'),
(153, '2025-07-10 22:21:55', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:21:14', '2025-07-11 00:21:55', 'Ha iniciado sesión correctamente.'),
(154, '2025-07-10 22:22:03', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:21:55', '2025-07-11 00:22:03', 'Ha iniciado sesión correctamente.'),
(155, '2025-07-10 22:22:13', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(156, '2025-07-10 23:46:12', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 00:22:03', '2025-07-11 01:46:12', 'Ha iniciado sesión correctamente.'),
(157, '2025-07-10 23:52:14', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(158, '2025-07-10 23:52:41', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: AdminDev@gmail.com y Cédula: 31.255.522'),
(159, '2025-07-10 23:52:48', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-11 01:52:48', 'Ha iniciado sesión correctamente.'),
(160, '2025-07-10 23:57:50', NULL, 'MANTENIMIENTO:CREAR', 'mantenimientos', '3', '', '', 'Se creó un registro de mantenimiento para el vehículo con matrícula GENKARY.'),
(161, '2025-07-11 00:25:46', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(162, '2025-07-11 00:25:48', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 01:46:12', '2025-07-11 02:25:48', 'Ha iniciado sesión correctamente.'),
(163, '2025-07-11 00:33:32', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(164, '2025-07-11 00:33:33', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:25:48', '2025-07-11 02:33:33', 'Ha iniciado sesión correctamente.'),
(165, '2025-07-11 00:33:41', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(166, '2025-07-11 00:33:42', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:33:33', '2025-07-11 02:33:42', 'Ha iniciado sesión correctamente.'),
(167, '2025-07-11 00:35:19', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(168, '2025-07-11 00:35:19', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:33:42', '2025-07-11 02:35:19', 'Ha iniciado sesión correctamente.'),
(169, '2025-07-11 00:36:50', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(170, '2025-07-11 00:36:50', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:35:19', '2025-07-11 02:36:50', 'Ha iniciado sesión correctamente.'),
(171, '2025-07-11 00:37:03', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(172, '2025-07-11 00:37:23', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: 1@gmail.com y Cédula: 22.222.222'),
(173, '2025-07-11 00:37:31', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-11 02:37:31', 'Ha iniciado sesión correctamente.'),
(174, '2025-07-11 00:40:13', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(175, '2025-07-11 00:40:20', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:36:50', '2025-07-11 02:40:20', 'Ha iniciado sesión correctamente.'),
(176, '2025-07-11 00:41:55', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(177, '2025-07-11 00:41:56', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:40:20', '2025-07-11 02:41:56', 'Ha iniciado sesión correctamente.'),
(178, '2025-07-11 00:46:18', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(179, '2025-07-11 00:46:19', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:41:56', '2025-07-11 02:46:19', 'Ha iniciado sesión correctamente.'),
(180, '2025-07-11 00:54:54', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(181, '2025-07-11 00:54:55', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:46:19', '2025-07-11 02:54:55', 'Ha iniciado sesión correctamente.'),
(182, '2025-07-11 00:55:33', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(183, '2025-07-11 00:55:34', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:54:55', '2025-07-11 02:55:34', 'Ha iniciado sesión correctamente.'),
(184, '2025-07-11 00:56:14', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(185, '2025-07-11 00:56:46', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: test1@gmail.com y Cédula: 11.111.111'),
(186, '2025-07-11 00:56:54', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-11 02:56:54', 'Ha iniciado sesión correctamente.'),
(187, '2025-07-11 00:56:59', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(188, '2025-07-11 00:57:17', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: test2@gmail.com y Cédula: 22.222.222'),
(189, '2025-07-11 00:57:18', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:55:34', '2025-07-11 02:57:18', 'Ha iniciado sesión correctamente.'),
(190, '2025-07-11 00:57:24', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(191, '2025-07-11 00:57:30', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-11 02:57:30', 'Ha iniciado sesión correctamente.'),
(192, '2025-07-11 00:58:04', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(193, '2025-07-11 00:58:09', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:57:30', '2025-07-11 02:58:09', 'Ha iniciado sesión correctamente.'),
(194, '2025-07-11 00:58:13', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(195, '2025-07-11 00:58:18', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:56:54', '2025-07-11 02:58:18', 'Ha iniciado sesión correctamente.'),
(196, '2025-07-11 00:58:26', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(197, '2025-07-11 00:58:28', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:57:18', '2025-07-11 02:58:28', 'Ha iniciado sesión correctamente.'),
(198, '2025-07-11 01:17:10', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(199, '2025-07-11 01:17:11', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:58:28', '2025-07-11 03:17:11', 'Ha iniciado sesión correctamente.'),
(200, '2025-07-11 01:30:42', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(201, '2025-07-11 01:30:43', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 03:17:11', '2025-07-11 03:30:43', 'Ha iniciado sesión correctamente.'),
(202, '2025-07-11 01:33:13', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(203, '2025-07-11 01:33:22', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:58:18', '2025-07-11 03:33:22', 'Ha iniciado sesión correctamente.'),
(204, '2025-07-11 01:33:28', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado la foto de perfil.'),
(205, '2025-07-11 01:33:31', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(206, '2025-07-11 01:33:32', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 03:30:43', '2025-07-11 03:33:32', 'Ha iniciado sesión correctamente.'),
(207, '2025-07-11 01:36:32', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(208, '2025-07-11 01:36:33', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 03:33:32', '2025-07-11 03:36:33', 'Ha iniciado sesión correctamente.'),
(209, '2025-07-11 01:37:06', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(210, '2025-07-11 01:37:07', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 03:36:33', '2025-07-11 03:37:07', 'Ha iniciado sesión correctamente.'),
(211, '2025-07-11 02:55:06', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(212, '2025-07-11 02:55:07', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 03:37:07', '2025-07-11 04:55:07', 'Ha iniciado sesión correctamente.'),
(213, '2025-07-11 02:57:08', 11, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(214, '2025-07-11 02:57:13', 11, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado la foto de perfil.'),
(215, '2025-07-11 05:17:29', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(216, '2025-07-11 05:17:39', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 03:33:22', '2025-07-11 07:17:39', 'Ha iniciado sesión correctamente.'),
(217, '2025-07-11 05:17:56', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(218, '2025-07-11 05:18:05', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 02:58:09', '2025-07-11 07:18:05', 'Ha iniciado sesión correctamente.'),
(219, '2025-07-11 05:18:20', NULL, 'VEHICULO:ELIMINAR', 'vehiculos', '24', '', '', 'Se eliminó el vehículo con la matricula: GENKARY'),
(220, '2025-07-11 05:18:24', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(221, '2025-07-11 05:18:25', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 04:55:07', '2025-07-11 07:18:25', 'Ha iniciado sesión correctamente.'),
(222, '2025-07-11 05:43:42', 1, 'USUARIO:ELIMINAR', 'usuarios', '*', '', '', 'Se ha eliminado permanentemente la cuenta del usuario: Correo: test1@gmail.com, Nombre: Test 1, Cédula: 11.111.111'),
(223, '2025-07-11 05:44:12', 11, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado la foto de perfil.'),
(224, '2025-07-11 05:44:15', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(225, '2025-07-11 05:44:34', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: test1@gmail.com y Cédula: 11.111.111'),
(226, '2025-07-11 05:44:44', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-11 07:44:44', 'Ha iniciado sesión correctamente.'),
(227, '2025-07-11 05:44:49', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado la foto de perfil.'),
(228, '2025-07-11 05:44:52', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(229, '2025-07-11 05:44:53', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 07:18:25', '2025-07-11 07:44:53', 'Ha iniciado sesión correctamente.'),
(230, '2025-07-11 05:45:05', 1, 'USUARIO:ELIMINAR', 'usuarios', '*', '', '', 'Se ha eliminado permanentemente la cuenta del usuario: Correo: test1@gmail.com, Nombre: Teat 1, Cédula: 11.111.111'),
(231, '2025-07-11 05:57:00', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 07:44:53', '2025-07-11 07:57:00', 'Ha iniciado sesión correctamente.'),
(232, '2025-07-11 06:04:10', 11, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(233, '2025-07-11 06:05:10', 11, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(234, '2025-07-11 06:08:37', 11, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(235, '2025-07-11 06:17:41', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(236, '2025-07-11 06:18:11', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(237, '2025-07-11 06:21:14', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(238, '2025-07-11 06:22:02', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(239, '2025-07-11 06:24:27', NULL, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.'),
(240, '2025-07-11 06:24:40', 11, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(241, '2025-07-11 06:24:54', NULL, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 07:18:05', '2025-07-11 08:24:54', 'Ha iniciado sesión correctamente.'),
(242, '2025-07-11 06:25:01', NULL, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(243, '2025-07-11 06:25:01', 11, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-11 07:57:00', '2025-07-11 08:25:01', 'Ha iniciado sesión correctamente.'),
(244, '2025-07-11 06:26:32', 1, 'USUARIO:ELIMINAR', 'usuarios', '*', '', '', 'Se ha eliminado permanentemente la cuenta del usuario: Correo: antonio@gmail.com, Nombre: Antonio, Cédula: 11.111.111');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonos`
--

CREATE TABLE `telefonos` (
  `id_telefono` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `telefonos`
--

INSERT INTO `telefonos` (`id_telefono`, `telefono`) VALUES
(2, '0111-1111111'),
(1, '0424-1396136');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contrasenia_hash` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `cedula` varchar(10) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `ruta_img` varchar(255) DEFAULT NULL,
  `rol` text NOT NULL DEFAULT 'Usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email`, `contrasenia_hash`, `nombre`, `cedula`, `telefono`, `fecha_registro`, `ultimo_login`, `ruta_img`, `rol`) VALUES
(1, 'system@sicaed.com', '$2y$10$MBQA/j3mJ2brv3Aiu/GfX.RXF/znjb3oxwnZXQW8UYmdBFCeukFuG', 'SISTEMA', '00.000.000', '0000-0000000', '2025-07-03 02:49:52', NULL, NULL, 'Administrador'),
(11, 'Genkary@gmail.com', '$2y$10$FLGLbb9sKYE4XkQBrM1yQum9Z2tiT0O.4ai9nBmKhDPwAcMha1jRK', 'Anthony Alejandro Travieso Hernández Locuron', '31.255.522', '0424-1396136', '2025-07-09 18:03:14', '2025-07-11 12:25:01', '../uploads/Users/images/6870a4acb6b0b7.62707783.jpg', 'Jefe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_vehiculo` int(11) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `serial_vin` varchar(100) NOT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `kilometraje` float DEFAULT NULL,
  `ruta_img` text DEFAULT NULL,
  `ruta_extras` text NOT NULL,
  `ruta_documentos` text DEFAULT NULL,
  `id_municipio` int(11) DEFAULT NULL,
  `ubicacion` text NOT NULL,
  `sede` varchar(100) DEFAULT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `id_personal` (`id_personal`),
  ADD KEY `id_vehiculo` (`id_vehiculo`);

--
-- Indices de la tabla `clavesmaestras`
--
ALTER TABLE `clavesmaestras`
  ADD PRIMARY KEY (`id_clave`);

--
-- Indices de la tabla `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id_email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `estado` (`estado`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `matricula` (`id_vehiculo`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id_personal`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`),
  ADD KEY `id_municipio` (`id_municipio`),
  ADD KEY `id_telefono` (`id_telefono`),
  ADD KEY `id_email` (`id_email`);

--
-- Indices de la tabla `registroauditorias`
--
ALTER TABLE `registroauditorias`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `telefonos`
--
ALTER TABLE `telefonos`
  ADD PRIMARY KEY (`id_telefono`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_vehiculo`),
  ADD KEY `id_municipio` (`id_municipio`),
  ADD KEY `matricula` (`matricula`),
  ADD KEY `serial_vin` (`serial_vin`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clavesmaestras`
--
ALTER TABLE `clavesmaestras`
  MODIFY `id_clave` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `emails`
--
ALTER TABLE `emails`
  MODIFY `id_email` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registroauditorias`
--
ALTER TABLE `registroauditorias`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT de la tabla `telefonos`
--
ALTER TABLE `telefonos`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asignaciones_conductores` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id_personal`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `fk_id_vehiculo_mantenimientos` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `municipios_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `personal`
--
ALTER TABLE `personal`
  ADD CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `personal_ibfk_2` FOREIGN KEY (`id_telefono`) REFERENCES `telefonos` (`id_telefono`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `personal_ibfk_3` FOREIGN KEY (`id_email`) REFERENCES `emails` (`id_email`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `registroauditorias`
--
ALTER TABLE `registroauditorias`
  ADD CONSTRAINT `registroauditorias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
