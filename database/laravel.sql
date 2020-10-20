-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-10-2020 a las 23:22:10
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimentos`
--

CREATE TABLE `alimentos` (
  `id` int(11) NOT NULL,
  `alimento` varchar(350) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `costo_kg` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `alimentos`
--

INSERT INTO `alimentos` (`id`, `alimento`, `costo_kg`, `created_at`, `updated_at`) VALUES
(1, 'Maxipeces 38% 2,5 mm', 3500, '2020-08-18 21:10:38', '2020-08-18 21:10:38'),
(2, 'Maxipeces 32% 2,5 mm', 3400, '2020-08-18 21:11:11', '2020-08-18 21:11:11'),
(3, 'Maxipeces 32% 4,7mm', 3300, '2020-08-18 21:11:38', '2020-08-18 21:11:38'),
(4, 'Maxipeces 28% 4,7mm', 3200, '2020-08-18 21:12:01', '2020-08-18 21:12:01'),
(37, 'C. TILAPIAS INICIACIÓN H.', 2751, '2020-08-04 02:21:29', '2020-08-04 02:21:37'),
(38, 'C. TILAPIAS INICIACIÓN 1.0 mm E.', 2625, '2020-08-04 02:22:43', '2020-08-04 02:22:43'),
(39, 'C. TILAPIAS INICIACIÓN 1.4 mm E.', 2625, '2020-08-04 02:25:18', '2020-08-04 02:25:18'),
(40, 'C. MAXIPECES 38 E. 1.8 mm.', 2375, '2020-08-04 02:26:05', '2020-08-04 02:26:05'),
(41, 'C. MAXIPECES 38 E. 2.5 mm.', 2375, '2020-08-04 02:28:49', '2020-08-04 02:28:49'),
(42, 'C. MAXIPECES 32 E. 2.5 mm.', 2125, '2020-08-04 02:30:04', '2020-08-04 02:30:04'),
(43, 'C. MAXIPECES 32 E. 3.5 mm.', 2125, '2020-08-04 02:30:23', '2020-08-04 02:30:23'),
(44, 'C. MAXIPECES 32 E. 4.7 mm.', 2125, '2020-08-04 02:30:43', '2020-08-04 02:30:43'),
(45, 'C. MAXIPECES 28 E.', 2050, '2020-08-04 02:31:18', '2020-08-04 02:31:18'),
(46, 'C. MAXIPECES 32 E. 7.0 mm.', 2125, '2020-08-04 02:31:41', '2020-08-04 02:31:41'),
(47, 'F. MOJARRA INICIACIÓN 40% 18 mm', 2250, '2020-08-04 02:32:13', '2020-08-04 02:32:13'),
(48, 'Peces Tropicales 38% Extruida 1.9 mm', 2125, '2020-08-04 02:34:39', '2020-08-04 02:34:39'),
(49, 'Peces Tropicales Juveniles 35% 1.9 mm', 2075, '2020-08-04 02:36:25', '2020-08-04 02:36:25'),
(50, 'Peces Tropicales Juveniles 35% 2.3 mm', 2125, '2020-08-04 02:36:59', '2020-08-04 02:36:59'),
(51, 'Peces Tropicales 32% 2.3 mm', 1975, '2020-08-04 02:38:24', '2020-08-04 02:38:24'),
(52, 'Peces Tropicales 30% 2.8 mm', 1975, '2020-08-04 02:38:24', '2020-08-04 02:38:48'),
(53, 'Peces Tropicales 30% 4.5 mm', 1975, '2020-08-04 02:39:24', '2020-08-04 02:39:35'),
(54, 'Peces Tropicales 30% 7.0 mm', 1975, '2020-08-04 02:39:54', '2020-08-04 02:39:54'),
(55, 'Peces Tropicales 25% 12 mm', 1875, '2020-08-04 02:40:26', '2020-08-04 02:40:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calidad_agua`
--

CREATE TABLE `calidad_agua` (
  `id` int(11) NOT NULL,
  `id_contenedor` int(11) NOT NULL,
  `fecha_parametro` date NOT NULL,
  `12_am` decimal(10,0) DEFAULT NULL,
  `4_am` double DEFAULT NULL,
  `7_am` double DEFAULT NULL,
  `4_pm` double DEFAULT NULL,
  `8_pm` double DEFAULT NULL,
  `temperatura` double DEFAULT NULL,
  `ph` double DEFAULT NULL,
  `amonio` double DEFAULT NULL,
  `nitrito` double DEFAULT NULL,
  `nitrato` double DEFAULT NULL,
  `otros` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenedores`
--

CREATE TABLE `contenedores` (
  `id` int(11) NOT NULL,
  `contenedor` varchar(350) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `capacidad` double NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `contenedores`
--

INSERT INTO `contenedores` (`id`, `contenedor`, `capacidad`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Contenedor 1', 4500, 2, '2020-08-18 21:00:14', '2020-09-10 03:53:06'),
(2, 'Contenedor 2', 3500, 2, '2020-08-18 21:00:26', '2020-08-18 21:24:21'),
(3, 'Contenedor 3', 4600, 2, '2020-08-18 21:01:11', '2020-08-18 21:25:54'),
(4, 'Contenedor 4', 5000, 2, '2020-08-18 21:12:43', '2020-09-08 03:43:11'),
(5, 'Contenedor 5', 4500, 1, '2020-08-18 21:12:55', '2020-08-18 21:12:55'),
(17, 'E19', 15000, 2, '2020-08-04 03:01:25', '2020-08-10 20:22:55'),
(18, 'E80', 1913, 2, '2020-08-07 02:28:19', '2020-08-07 02:30:57'),
(19, 'E37', 1500, 2, '2020-08-08 01:09:27', '2020-08-08 01:10:49'),
(20, 'E1', 315, 2, '2020-08-10 20:20:39', '2020-08-10 22:56:26'),
(21, 'E2', 2000, 2, '2020-08-10 20:21:05', '2020-08-10 23:00:22'),
(22, 'E3', 2642, 1, '2020-08-10 20:21:31', '2020-08-10 20:21:31'),
(23, 'E4', 252, 1, '2020-08-10 20:21:50', '2020-08-10 20:21:50'),
(24, 'E5', 2100, 2, '2020-08-10 20:22:14', '2020-10-20 00:43:29'),
(25, 'E6', 1943, 1, '2020-08-10 20:22:33', '2020-08-10 20:22:33'),
(26, 'E7', 2653, 2, '2020-08-10 20:23:47', '2020-08-10 23:19:35'),
(27, 'E8', 3183, 2, '2020-08-10 20:24:06', '2020-08-10 23:22:21'),
(28, 'E9', 5640, 2, '2020-08-10 20:24:32', '2020-08-10 23:26:37'),
(29, 'E10', 6888, 2, '2020-08-10 20:24:54', '2020-08-10 23:37:38'),
(30, 'E11', 1900, 2, '2020-08-10 20:25:21', '2020-08-10 23:43:34'),
(31, 'E12', 5120, 1, '2020-08-10 20:25:46', '2020-08-10 20:25:46'),
(32, 'E13', 7318, 2, '2020-08-10 20:26:06', '2020-08-10 23:46:32'),
(33, 'E14', 3700, 2, '2020-08-10 20:26:35', '2020-08-11 00:04:07'),
(34, 'E15', 3500, 2, '2020-08-10 20:26:59', '2020-08-11 00:19:19'),
(35, 'E16', 6436, 1, '2020-08-10 20:27:25', '2020-08-10 20:27:25'),
(36, 'E17', 6100, 1, '2020-08-10 20:28:05', '2020-08-10 20:28:05'),
(37, 'E18', 5121, 1, '2020-08-10 20:28:40', '2020-08-10 20:28:40'),
(39, 'E19 Actual', 15000, 2, '2020-08-10 20:30:48', '2020-08-11 00:28:41'),
(40, 'E20', 9342, 2, '2020-08-10 20:31:13', '2020-08-13 00:24:07'),
(41, 'E21', 5728, 2, '2020-08-10 20:31:38', '2020-08-11 00:32:58'),
(42, 'E22', 5853, 2, '2020-08-10 20:32:03', '2020-08-11 00:34:31'),
(43, 'E23', 1170, 2, '2020-08-10 20:32:23', '2020-08-11 00:37:42'),
(44, 'E24', 481, 1, '2020-08-10 20:33:36', '2020-08-10 20:33:36'),
(45, 'E25', 1429, 2, '2020-08-10 20:34:10', '2020-08-11 00:46:04'),
(46, 'E26', 2766, 2, '2020-08-10 20:34:38', '2020-08-11 00:48:09'),
(47, 'E28', 436, 1, '2020-08-10 20:35:12', '2020-08-10 20:35:12'),
(48, 'E29', 980, 2, '2020-08-10 20:35:36', '2020-08-11 00:55:24'),
(49, 'E30', 468, 2, '2020-08-10 20:35:56', '2020-08-11 01:48:35'),
(50, 'E31', 2328, 2, '2020-08-10 20:36:13', '2020-08-11 00:56:58'),
(51, 'E32', 2000, 2, '2020-08-10 21:04:13', '2020-08-11 00:58:03'),
(52, 'E33', 2147, 2, '2020-08-10 21:04:35', '2020-08-11 00:58:57'),
(53, 'E34', 150, 1, '2020-08-10 21:04:59', '2020-08-10 21:04:59'),
(54, 'E37', 1691, 2, '2020-08-10 21:06:16', '2020-08-11 01:01:08'),
(55, 'E38', 462, 1, '2020-08-10 21:06:41', '2020-08-10 21:06:41'),
(56, 'E39', 1712, 2, '2020-08-10 21:07:02', '2020-08-11 01:02:34'),
(57, 'E40', 1700, 1, '2020-08-10 21:11:16', '2020-08-10 21:11:16'),
(58, 'E41', 415, 1, '2020-08-10 21:12:36', '2020-08-10 21:12:36'),
(59, 'E42', 1298, 1, '2020-08-10 21:12:58', '2020-08-10 21:12:58'),
(60, 'E43', 968, 1, '2020-08-10 21:13:24', '2020-08-10 21:13:24'),
(61, 'E44', 524, 1, '2020-08-10 21:13:43', '2020-08-10 21:13:43'),
(62, 'E50', 2400, 1, '2020-08-10 21:16:39', '2020-08-10 21:16:39'),
(63, 'E52', 843, 2, '2020-08-10 21:17:05', '2020-08-11 01:06:52'),
(65, 'E70', 1160, 2, '2020-08-10 21:28:02', '2020-10-09 01:52:53'),
(66, 'E71', 1357, 2, '2020-08-10 21:28:19', '2020-08-11 01:11:13'),
(67, 'E72', 1500, 2, '2020-08-10 21:28:40', '2020-08-11 01:13:37'),
(68, 'E73', 1578, 1, '2020-08-10 21:30:05', '2020-08-10 21:30:05'),
(69, 'E74', 2357, 2, '2020-08-10 21:30:25', '2020-08-11 01:22:04'),
(70, 'E75', 1734, 2, '2020-08-10 21:30:42', '2020-08-11 01:24:16'),
(71, 'E76', 1850, 2, '2020-08-10 21:36:09', '2020-08-11 01:29:02'),
(72, 'E77', 1995, 2, '2020-08-10 21:36:45', '2020-08-11 01:32:22'),
(73, 'E78', 3069, 2, '2020-08-10 21:37:03', '2020-08-11 01:33:26'),
(74, 'E79', 2133, 2, '2020-08-10 21:37:24', '2020-08-11 01:34:38'),
(76, 'E81', 1160, 2, '2020-08-10 21:38:07', '2020-08-11 01:36:16'),
(77, 'R1', 2421, 1, '2020-08-10 21:38:35', '2020-08-10 21:38:35'),
(78, 'R2', 1200, 1, '2020-08-10 21:39:18', '2020-08-10 21:39:18'),
(79, 'R3', 15000, 2, '2020-08-10 21:39:37', '2020-10-13 22:02:12'),
(80, 'R4', 6800, 2, '2020-08-10 21:41:33', '2020-08-11 01:38:31'),
(81, 'RC', 15000, 1, '2020-08-10 21:42:04', '2020-10-19 19:26:59'),
(82, 'Canal Piras', 1500, 2, '2020-08-10 21:42:40', '2020-10-08 10:29:45'),
(83, 'E112', 1938, 2, '2020-08-10 21:43:26', '2020-08-11 01:40:26'),
(84, 'E113', 2166, 2, '2020-08-10 21:43:41', '2020-08-11 01:42:01'),
(85, 'E114', 800, 2, '2020-08-10 21:44:01', '2020-08-11 01:43:23'),
(86, 'E64', 1500, 2, '2020-08-11 01:08:12', '2020-08-11 01:09:21'),
(87, 'E63', 1000, 2, '2020-08-11 21:21:01', '2020-08-11 21:22:01'),
(88, 'E20', 9342, 2, '2020-08-13 00:20:57', '2020-08-16 02:12:54'),
(89, 'E75', 1661, 2, '2020-08-20 21:59:02', '2020-08-20 22:05:13'),
(91, '19 viejo', 15000, 2, '2020-08-21 18:05:24', '2020-08-21 18:10:33'),
(93, '76 2019', 1661, 2, '2020-08-22 01:28:40', '2020-08-22 01:30:08'),
(96, 'CONTENEDOR20', 15000, 2, '2020-10-19 19:27:18', '2020-10-19 19:28:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies`
--

CREATE TABLE `especies` (
  `id` int(11) NOT NULL,
  `especie` varchar(350) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `especies`
--

INSERT INTO `especies` (`id`, `especie`, `descripcion`, `created_at`, `updated_at`) VALUES
(4, 'Sábalo', 'lorem lorem lorem lorem lorem lorem lorem lorem', '2020-08-18 21:03:27', '2020-08-18 21:03:27'),
(10, 'Pirarucu', 'Pirarucu', '2020-07-21 17:29:33', '2020-07-21 17:29:33'),
(11, 'Cachama', 'Blanca', '2020-07-23 20:44:07', '2020-07-23 20:44:07'),
(12, 'Tilapia', 'Roja', '2020-07-23 20:44:32', '2020-07-23 20:44:32'),
(13, 'Sabalo', 'Amazónico', '2020-07-23 20:44:44', '2020-07-23 20:44:44'),
(14, 'Bocachico', 'Amazónico', '2020-07-23 20:45:00', '2020-07-23 20:45:00'),
(15, 'Arawana', 'Plateada', '2020-07-23 20:45:13', '2020-07-23 20:45:13'),
(16, 'Carpa', 'Roja', '2020-07-23 20:45:52', '2020-07-23 20:45:52'),
(17, 'Bagre', 'Rayado', '2020-07-23 20:46:06', '2020-07-23 20:46:06'),
(18, 'Cachama Grande', 'Grande', '2020-08-10 23:48:59', '2020-08-10 23:48:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies_siembra`
--

CREATE TABLE `especies_siembra` (
  `id` int(11) NOT NULL,
  `id_siembra` int(11) DEFAULT NULL,
  `lote` varchar(200) DEFAULT NULL,
  `id_especie` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `peso_inicial` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cant_actual` int(11) NOT NULL,
  `peso_actual` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos`
--

CREATE TABLE `recursos` (
  `id` int(11) NOT NULL,
  `recurso` varchar(350) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `unidad` varchar(250) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `costo` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `recursos`
--

INSERT INTO `recursos` (`id`, `recurso`, `unidad`, `costo`, `created_at`, `updated_at`) VALUES
(1, 'Carbonato', 'Kg', 300, '2020-08-18 21:06:07', '2020-08-18 21:06:07'),
(2, 'Hidroxido', 'Kg', 300, '2020-08-18 21:07:23', '2020-08-18 21:07:23'),
(3, 'Cal viva', 'Kg', 350, '2020-08-18 21:08:36', '2020-08-18 21:08:36'),
(4, 'Herbicida', 'mL', 86, '2020-08-18 21:08:58', '2020-08-18 21:08:58'),
(5, 'Probiotico', 'Lt', 40, '2020-08-18 21:09:24', '2020-08-18 21:09:24'),
(7, 'Cal viva', 'Kilogramos', 400, '2020-08-08 02:40:48', '2020-08-08 02:40:48'),
(8, 'Cal dolomita', 'Kilogramos', 125, '2020-08-08 02:41:17', '2020-08-08 02:41:17'),
(9, 'Carbonato de Calcio', 'Kilogramos', 325, '2020-08-08 02:41:54', '2020-08-08 02:41:54'),
(10, 'Simbiótica Fresh Plus', 'Litros', 115, '2020-08-08 02:47:54', '2020-08-08 02:47:54'),
(11, 'Simbiótica Pitalito', 'Litros', 54, '2020-08-08 02:48:27', '2020-08-08 02:48:27'),
(12, 'Probiótico Oxynova', 'Litros', 121, '2020-08-08 02:49:07', '2020-08-08 02:49:07'),
(13, 'Probiótico Ecopró', 'Litros', 2274, '2020-08-08 02:49:41', '2020-08-08 02:49:41'),
(14, 'Probiótico Fresh Plus', 'Litros', 166, '2020-08-08 02:50:32', '2020-08-08 02:50:32'),
(15, 'Hora hombre', 'Minuto', 5000, '2020-08-08 02:53:31', '2020-08-31 21:40:09'),
(16, 'Aireador splash brasilero', 'Hora', 661, '2020-08-08 02:54:57', '2020-08-08 02:54:57'),
(18, 'Minutos Hombre', 'Minuto', 80, '2020-10-20 22:15:08', '2020-10-20 22:15:08');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos_siembras`
--

CREATE TABLE `recursos_siembras` (
  `id` int(11) NOT NULL,
  `id_registro` int(11) NOT NULL,
  `id_siembra` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `id_siembra` int(11) NOT NULL,
  `id_especie` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `tipo_registro` int(11) NOT NULL,
  `peso_ganado` double DEFAULT NULL,
  `mortalidad` int(11) DEFAULT NULL,
  `biomasa` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `siembras`
--

CREATE TABLE `siembras` (
  `id` int(11) NOT NULL,
  `nombre_siembra` varchar(100) NOT NULL,
  `id_contenedor` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `ini_descanso` date DEFAULT NULL,
  `estado` int(11) NOT NULL,
  `fin_descanso` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fecha_alimento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` int(2) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Cristhiam Camilo', 'ccmonpan@hotmail.com', NULL, '$2y$10$phfcsCm1OzRNdO1uPDVFh./vVrqh5Vc9.gtr70I7Zarb1A9OAgOgC', 'yLjSNqZhlaGpAC7iZ0c4aL9UdFcb7umGZeepVJgM6BGPb4fqJGN4b2GeP6R5', 1, '2020-06-29 23:07:18', '2020-06-29 23:07:18'),
(2, 'Isabella', 'issalazar00@outlook.com', NULL, '$2y$10$SXdLLlbOiMy13006qYM5ze4aqPolr80pvC0vVLWQ6ZRzMm2kOYN/C', NULL, 1, '2020-06-30 01:02:57', '2020-06-30 01:02:57'),
(4, 'andres rojas', 'andres@gmail.com', NULL, '$2y$10$7QFjVQunNyOKdn4SMZJBU.K88hW03sW11Py.kzdOLDCpaP4DjiOc.', NULL, 1, '2020-07-15 02:54:52', '2020-07-15 02:54:52'),
(5, 'richard peña', 'richardpen90@gmail.com', NULL, '$2y$10$PkU9jMgFAiSQI7wrZXSk7./29/qO4PUa48Lt4TS0U8wjyFrAXRe.C', NULL, 1, '2020-07-18 21:57:18', '2020-07-18 21:57:18'),
(6, 'Edison Steve Pecillo', 'estevenup@hotmail.com', NULL, '$2y$10$gDVrSn4.AUooDrx/wuGab.Q9DxsmCD8zSxD/U/kAqJ6Dm3mbdSdz.', NULL, 0, '2020-08-07 02:42:30', '2020-09-14 21:32:09'),
(7, 'Esteban Jurado', 'esteban_5280@hotmail.com', NULL, '$2y$10$jLrpheNFWBa8WIZ9BqZmSOsjvowZiLKj4r5x0nWE9xyBX1som1Baa', NULL, 0, '2020-08-13 00:06:28', '2020-09-14 21:56:20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `alimentos`
--
ALTER TABLE `alimentos`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `contenedores`
--
ALTER TABLE `contenedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `especies`
--
ALTER TABLE `especies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `especies_siembra`
--
ALTER TABLE `especies_siembra`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `recursos`
--
ALTER TABLE `recursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recursos_necesarios`
--
ALTER TABLE `recursos_necesarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recursos_siembras`
--
ALTER TABLE `recursos_siembras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `siembras`
--
ALTER TABLE `siembras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `alimentos`
--
ALTER TABLE `alimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `calidad_agua`
--
ALTER TABLE `calidad_agua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `calidad_siembra`
--
ALTER TABLE `calidad_siembra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contenedores`
--
ALTER TABLE `contenedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `especies_siembra`
--
ALTER TABLE `especies_siembra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `recursos`
--
ALTER TABLE `recursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `recursos_necesarios`
--
ALTER TABLE `recursos_necesarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recursos_siembras`
--
ALTER TABLE `recursos_siembras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `siembras`
--
ALTER TABLE `siembras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
