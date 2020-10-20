-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2020 a las 23:25:37
-- Versión del servidor: 10.4.11-MariaDB
-- Versión de PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `laravel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `actividad` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `actividad`, `created_at`, `updated_at`) VALUES
(1, 'Alimentación', '2020-09-07 14:51:11', '2020-09-07 14:51:11'),
(2, 'Cultivo', '2020-09-07 14:51:11', '2020-09-07 14:51:11'),
(3, 'Encalado', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(4, 'Lavado', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(5, 'Llenado', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(6, 'Pesca', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(7, 'Secado', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(8, 'Siembra', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(9, 'Fumigado', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(10, 'Re-encalado', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(11, 'Recambios de agua', '2020-09-07 14:53:51', '2020-09-07 14:53:51'),
(12, 'Aireación Mecánica', '2020-09-07 14:55:31', '2020-09-07 14:55:31'),
(13, 'Aplicación probiótico', '2020-09-07 14:55:31', '2020-09-07 14:55:31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
