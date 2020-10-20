-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2020 a las 23:23:07
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
-- Estructura de tabla para la tabla `recursos_necesarios`
--

CREATE TABLE `recursos_necesarios` (
  `id` int(11) NOT NULL,
  `id_recurso` int(11) DEFAULT NULL,
  `id_alimento` int(11) DEFAULT NULL,
  `tipo_actividad` varchar(50) NOT NULL,
  `fecha_ra` date NOT NULL,
  `horas_hombre` double DEFAULT NULL,
  `minutos_hombre` int(11) DEFAULT NULL,
  `cantidad_recurso` double DEFAULT NULL,
  `cant_manana` int(11) DEFAULT NULL,
  `cant_tarde` int(11) DEFAULT NULL,
  `conv_alimenticia` double DEFAULT NULL,
  `detalles` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `recursos_necesarios`
--
ALTER TABLE `recursos_necesarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `recursos_necesarios`
--
ALTER TABLE `recursos_necesarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
