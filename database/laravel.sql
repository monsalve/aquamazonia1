-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2020 a las 01:47:48
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
(1, 'concentrado', 4599, '2020-07-02 20:59:03', '2020-07-04 00:29:40'),
(2, 'Concentrado', 4500, '2020-07-03 02:05:21', '2020-07-03 02:05:21'),
(5, 'Tocino', 330, '2020-07-03 05:05:29', '2020-07-03 05:05:29'),
(10, 'Actualizar', 88888, '2020-07-03 08:28:41', '2020-07-04 00:29:44'),
(12, 'Lechuga', 98989, '2020-07-03 22:32:04', '2020-07-03 22:32:04'),
(13, 'Lechuga', 98989, '2020-07-03 22:32:05', '2020-07-03 22:32:05'),
(14, 'Lechuga', 98989, '2020-07-03 22:32:05', '2020-07-04 00:32:37'),
(15, 'concentrados', 45995, '2020-07-03 22:32:29', '2020-07-04 00:34:58'),
(17, 'concentrados', 459956, '2020-07-03 22:35:28', '2020-07-03 22:35:28'),
(18, 'concentradosx', 45995, '2020-07-04 00:11:23', '2020-07-04 00:11:23'),
(19, 'concentradosxx', 45995, '2020-07-04 00:11:28', '2020-07-04 00:34:56'),
(20, 'conce', 45995, '2020-07-04 00:15:53', '2020-07-04 00:15:53'),
(21, 'conce', 45995, '2020-07-04 00:23:43', '2020-07-04 00:23:43'),
(22, 'Lechuga', 88888, '2020-07-04 00:23:53', '2020-07-04 00:23:53'),
(23, 'Lechugas', 88888, '2020-07-04 00:27:57', '2020-07-04 00:27:57'),
(24, 'Actualizar', 888885, '2020-07-04 00:29:53', '2020-07-04 00:34:52'),
(25, 'Actualizar', 8888858, '2020-07-04 00:30:03', '2020-07-04 00:34:42'),
(26, 'Actualizare', 8888858, '2020-07-04 00:31:04', '2020-07-04 00:31:04'),
(27, 'Lechuga', 989896, '2020-07-04 00:32:44', '2020-07-04 00:34:16'),
(28, 'Lechugas', 989896, '2020-07-04 00:58:14', '2020-07-04 00:58:14'),
(29, 'Lechugasx', 989896, '2020-07-04 00:58:24', '2020-07-04 00:58:24'),
(30, 'alimento', 98989, '2020-07-04 01:02:33', '2020-07-04 01:05:13'),
(31, 'alimento', 45005555, '2020-07-04 01:05:59', '2020-07-04 01:14:35'),
(32, '55555', 2222222, '2020-07-04 01:14:44', '2020-07-04 01:17:28'),
(33, 'hola!', 444445, '2020-07-04 01:17:46', '2020-07-04 01:20:20');

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
(1, 'Contenedor1', 4500, 0, '2020-07-04 02:42:17', '2020-07-04 02:54:39'),
(2, 'Contenedor2', 4500, 1, '2020-07-04 02:55:34', '2020-07-04 02:55:34');

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
(1, 'especie1', 'lorem ipsum lorem ipsum', '2020-07-06 20:09:25', '2020-07-06 20:09:25'),
(2, 'especie2', 'lorem ipsum lorem ipsum loremm ipsum lorem ipsum', '2020-07-07 00:14:03', '2020-07-07 00:14:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especies_siembra`
--

CREATE TABLE `especies_siembra` (
  `id` int(11) NOT NULL,
  `id_siembra` int(11) DEFAULT NULL,
  `id_especie` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `peso_inicial` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cant_actual` int(11) NOT NULL,
  `peso_actual` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `especies_siembra`
--

INSERT INTO `especies_siembra` (`id`, `id_siembra`, `id_especie`, `cantidad`, `peso_inicial`, `created_at`, `updated_at`, `cant_actual`, `peso_actual`) VALUES
(119, 12, 1, 4, 2, '2020-07-08 21:19:30', '2020-07-08 21:19:30', 4, 2),
(120, 13, 2, 3, 1, '2020-07-08 21:37:43', '2020-07-08 21:37:43', 3, 1),
(121, 13, 1, 9, 8, '2020-07-08 21:37:43', '2020-07-08 21:37:43', 9, 8),
(122, 14, 2, 2, 1, '2020-07-08 21:38:49', '2020-07-08 21:38:49', 2, 1);

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
(1, 'Recurso1', 'Lt', 4200, '2020-07-04 03:36:23', '2020-07-04 03:39:49'),
(2, 'Recurso2', 'Lt', 9000, '2020-07-04 03:38:15', '2020-07-04 03:38:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `siembras`
--

CREATE TABLE `siembras` (
  `id` int(11) NOT NULL,
  `id_contenedor` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `ini_descanso` date NOT NULL,
  `estado` int(11) NOT NULL,
  `fin_descanso` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `siembras`
--

INSERT INTO `siembras` (`id`, `id_contenedor`, `fecha_inicio`, `ini_descanso`, `estado`, `fin_descanso`, `created_at`, `updated_at`) VALUES
(12, 2, '2020-07-10', '0000-00-00', 1, NULL, '2020-07-08 21:19:30', '2020-07-08 21:19:30'),
(13, 2, '2020-07-05', '0000-00-00', 1, NULL, '2020-07-08 21:37:42', '2020-07-08 21:37:42'),
(14, 2, '2020-07-05', '0000-00-00', 1, NULL, '2020-07-08 21:38:49', '2020-07-08 21:38:49');

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
(1, 'Cristhiam Camilo', 'ccmonpan@hotmail.com', NULL, '$2y$10$phfcsCm1OzRNdO1uPDVFh./vVrqh5Vc9.gtr70I7Zarb1A9OAgOgC', 'JOsHOViA06d3U2BALWMaPGuRB81cM1y76ul6K6YNRYTUwxBP753iLX8LnBk1', 1, '2020-06-29 23:07:18', '2020-06-29 23:07:18'),
(2, 'Isabella', 'issalazar00@outlook.com', NULL, '$2y$10$SXdLLlbOiMy13006qYM5ze4aqPolr80pvC0vVLWQ6ZRzMm2kOYN/C', NULL, 1, '2020-06-30 01:02:57', '2020-06-30 01:02:57');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alimentos`
--
ALTER TABLE `alimentos`
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
-- AUTO_INCREMENT de la tabla `alimentos`
--
ALTER TABLE `alimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `contenedores`
--
ALTER TABLE `contenedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `especies_siembra`
--
ALTER TABLE `especies_siembra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `siembras`
--
ALTER TABLE `siembras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
