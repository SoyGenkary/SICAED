-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-07-2025 a las 02:31:33
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
  `ubicacion` text DEFAULT NULL,
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
(2, '2025-07-13 23:38:27', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(3, '2025-07-13 23:38:31', 1, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-14 01:38:31', 'Ha iniciado sesión correctamente.'),
(4, '2025-07-13 23:38:53', 1, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(5, '2025-07-13 23:38:54', 1, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-14 01:38:31', '2025-07-14 01:38:54', 'Ha iniciado sesión correctamente.'),
(6, '2025-07-13 23:39:18', 1, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(7, '2025-07-13 23:40:12', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: admin@SICAED.com y Cédula: 00.000.000'),
(8, '2025-07-13 23:40:39', 1, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-14 01:40:39', 'Ha iniciado sesión correctamente.'),
(9, '2025-07-13 23:40:42', 1, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(10, '2025-07-13 23:40:59', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: Genkary@gmail.com y Cédula: 31.255.522'),
(11, '2025-07-13 23:41:01', 2, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '', '2025-07-14 01:41:01', 'Ha iniciado sesión correctamente.'),
(12, '2025-07-13 23:41:13', 2, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(13, '2025-07-13 23:41:14', 2, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-14 01:41:01', '2025-07-14 01:41:14', 'Ha iniciado sesión correctamente.'),
(14, '2025-07-14 00:26:41', 2, 'USUARIO:LOGOUT', 'usuarios', '*', '', '', 'Cerró sesión.'),
(15, '2025-07-14 00:26:58', 1, 'USUARIO:CREAR', 'usuarios', '*', '', '', 'Se ha creado un nuevo usuario con correo: admin@gmail.com y Cédula: 00.000.000'),
(16, '2025-07-14 00:27:03', 2, 'USUARIO:LOGIN', 'usuarios', 'ultimo_login', '2025-07-14 01:41:14', '2025-07-14 02:27:03', 'Ha iniciado sesión correctamente.'),
(17, '2025-07-14 00:27:53', 3, 'USUARIO:MODIFY', 'usuarios', '*', '', '', 'Se ha actualizado los datos del perfil.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonos`
--

CREATE TABLE `telefonos` (
  `id_telefono` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'admin@SICAED.com', '$2y$10$EhJArCwK2OaGDcYD7pLeeepb7uyBulndcdcIrTjb.Q8wSjqyIu2Hy', 'SISTEMA SICAED', '00.000.000', '0000-0000000', '2025-07-13 23:40:12', '2025-07-14 05:40:39', NULL, 'Administrador'),
(2, 'Genkary@gmail.com', '$2y$10$NC51rBJmuZQ3t5WX1q6.LuBtmyCjkDQIU/WAUkrxV8SJXzkZNHCt6', 'Anthony Alejandro Travieso Hernández', '31.255.522', '0424-1396136', '2025-07-13 23:40:59', '2025-07-14 06:27:03', NULL, 'Jefe'),
(3, 'admin@gmail.com', '$2y$10$0.ELI16gYuU4vk3zpbhVLeSb9HVurIKLYPLNqhjgqfgX/e4lUE.xe', 'admin', '00.000.000', '0000-0000000', '2025-07-14 00:26:58', NULL, NULL, 'Jefe');

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
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clavesmaestras`
--
ALTER TABLE `clavesmaestras`
  MODIFY `id_clave` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `emails`
--
ALTER TABLE `emails`
  MODIFY `id_email` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id_personal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registroauditorias`
--
ALTER TABLE `registroauditorias`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `telefonos`
--
ALTER TABLE `telefonos`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT;

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
