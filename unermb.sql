-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-06-2026 a las 16:30:31
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
-- Base de datos: `unermb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `fecha_creacion` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `ID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`nombre`, `descripcion`, `contraseña`, `fecha_creacion`, `ID`) VALUES
('Gabriel', 'deben bañar al perro a las 4 de la tarde con champu', 'Gabriel22/', '2026-06-14 20:33:22.402357', 2),
('Gabriel', 'hola para la proxima actividad deben traer herramientas de cocina para este jueves', 'Gabriel22//', '2026-06-15 20:47:03.031624', 7),
('Gabriel', 'Hola para este viernes traer mesas de trabajo con herramientas de tratado de carros automaticos', 'Gabriel22//', '2026-06-15 20:48:30.336858', 8),
('Gabriel', 'monitorear las contancias de estudios ingresadas el martes pasado por favor', 'Gabriel22//', '2026-06-16 20:26:26.127064', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `cedula` varchar(10) NOT NULL,
  `fecha` date NOT NULL,
  `entrada` time(6) DEFAULT NULL,
  `salida` time(6) DEFAULT NULL,
  `ID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`cedula`, `fecha`, `entrada`, `salida`, `ID`) VALUES
('30880463', '2026-06-18', '21:19:27.000000', '21:19:37.000000', 13),
('30437719', '2026-06-19', '22:43:53.000000', '22:44:04.000000', 15),
('30880463', '2026-06-20', '20:02:51.000000', '20:08:14.000000', 16),
('30437719', '2026-06-22', '12:04:43.000000', '12:04:54.000000', 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encargado`
--

CREATE TABLE `encargado` (
  `nombre` varchar(30) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `ID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encargado`
--

INSERT INTO `encargado` (`nombre`, `cedula`, `correo`, `usuario`, `contraseña`, `ID`) VALUES
('Gabriel', '30437719', 'clothdios@gmail.com', 'Gabriel', 'Gabriel22//', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `justificativos`
--

CREATE TABLE `justificativos` (
  `nombre` varchar(30) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `comprobante` varchar(30) NOT NULL,
  `motivo` text NOT NULL,
  `fecha_creacion` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `ID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `justificativos`
--

INSERT INTO `justificativos` (`nombre`, `cedula`, `comprobante`, `motivo`, `fecha_creacion`, `ID`) VALUES
('Gabriel Villegas', '30437719', 'uploads/1781545169_6a3038d1dfc', 'me dolia la cabeza muy fuerte', '2026-06-15 17:39:29.964585', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `usuario` varchar(30) NOT NULL,
  `asunto` varchar(50) NOT NULL,
  `noticia` text NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `fecha_creacion` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `ID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`usuario`, `asunto`, `noticia`, `contraseña`, `fecha_creacion`, `ID`) VALUES
('Gabriel', 'verificar el arroz donde', 'verificar el arroz donde', 'Gabriel22/', '2026-06-14 23:06:42.562029', 1),
('Gabriel', 'Revisar los recibos del arroz para el jueves', 'deben verificar los recibos del arroz para el juves, viernes a las 8', 'Gabriel22//', '2026-06-15 17:39:08.128665', 2),
('Gabriel', 'hola saunto sobre el cafe de lecha', 'cafe de leche comprar este jueves a las 5 de la tarde', 'Gabriel22//', '2026-06-16 20:23:43.320953', 3),
('Gabriel', 'Verificar la contraseña de la noche anterior', 'verificar la contraseña anterior del sistema para priorizar la seguridad', 'Gabriel22//', '2026-06-19 00:59:08.489927', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `cargo` varchar(30) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `rol` varchar(30) NOT NULL,
  `ID` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`nombre`, `apellido`, `cedula`, `cargo`, `correo`, `usuario`, `contraseña`, `rol`, `ID`) VALUES
('Juan', 'Avila', '30880463', 'Obrero', 'orlando@gmail.com', 'Juan', 'Juan123/5.', 'empleado', 9),
('Alejandro', 'Delgado', '30437719', 'Administrativo', 'clothdios@gmail.com', 'Alejandro', 'Alejandro22//', 'empleado', 10),
('Alberto', 'Villegas', '23435678', 'Administrativo', 'del@gmail.com', 'Alberto', 'Alberto22//', 'empleado', 11);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `encargado`
--
ALTER TABLE `encargado`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `justificativos`
--
ALTER TABLE `justificativos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `encargado`
--
ALTER TABLE `encargado`
  MODIFY `ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `justificativos`
--
ALTER TABLE `justificativos`
  MODIFY `ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
