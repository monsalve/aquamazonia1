-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-08-2020 a las 23:03:16
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
-- Estructura de tabla para la tabla `calidad_agua`
--

CREATE TABLE `calidad_agua` (
  `id` int(11) NOT NULL,
  `fecha_parametro` date NOT NULL,
  `12_am` double NOT NULL,
  `4_am` double NOT NULL,
  `7_am` double NOT NULL,
  `4_pm` double NOT NULL,
  `8_pm` double NOT NULL,
  `temperatura` double NOT NULL,
  `ph` double NOT NULL,
  `amonio` double NOT NULL,
  `nitrito` double NOT NULL,
  `nitrato` double NOT NULL,
  `otros` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `calidad_agua`
--

INSERT INTO `calidad_agua` (`id`, `fecha_parametro`, `12_am`, `4_am`, `7_am`, `4_pm`, `8_pm`, `temperatura`, `ph`, `amonio`, `nitrito`, `nitrato`, `otros`, `created_at`, `updated_at`) VALUES
(1, '2020-08-09', 8, 9, 9, 9, 8, 9, 8, 9, 9, 7, 9, '2020-08-10 02:36:11', '2020-08-10 02:36:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calidad_siembra`
--

CREATE TABLE `calidad_siembra` (
  `id` int(11) NOT NULL,
  `id_calidad_parametros` int(11) NOT NULL,
  `id_siembra` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `calidad_siembra`
--

INSERT INTO `calidad_siembra` (`id`, `id_calidad_parametros`, `id_siembra`, `created_at`, `updated_at`) VALUES
(1, 1, 29, '2020-08-10 02:36:11', '2020-08-10 02:36:11'),
(2, 1, 28, '2020-08-10 02:36:12', '2020-08-10 02:36:12'),
(3, 1, 26, '2020-08-10 02:36:12', '2020-08-10 02:36:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calidad_agua`
--
ALTER TABLE `calidad_agua`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `calidad_siembra`
--
ALTER TABLE `calidad_siembra`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calidad_agua`
--
ALTER TABLE `calidad_agua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `calidad_siembra`
--
ALTER TABLE `calidad_siembra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
