-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-08-2020 a las 19:23:44
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
(1, 'Maxipeces 38% 2,5 mm', 3500, '2020-08-18 21:10:38', '2020-08-18 21:10:38'),
(2, 'Maxipeces 32% 2,5 mm', 3400, '2020-08-18 21:11:11', '2020-08-18 21:11:11'),
(3, 'Maxipeces 32% 4,7mm', 3300, '2020-08-18 21:11:38', '2020-08-18 21:11:38'),
(4, 'Maxipeces 28% 4,7mm', 3200, '2020-08-18 21:12:01', '2020-08-18 21:12:01');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calidad_siembra`
--

CREATE TABLE `calidad_siembra` (
  `id` int(11) NOT NULL,
  `id_calidad_parametros` int(11) NOT NULL,
  `id_contenedor` int(11) NOT NULL,
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
(1, 'Contenedor  1', 4500, 2, '2020-08-18 21:00:14', '2020-08-18 21:15:05'),
(2, 'Contenedor 2', 3500, 2, '2020-08-18 21:00:26', '2020-08-18 21:24:21'),
(3, 'Contenedor 3', 4600, 2, '2020-08-18 21:01:11', '2020-08-18 21:25:54'),
(4, 'Contenedor 4', 5000, 1, '2020-08-18 21:12:43', '2020-08-18 21:12:43'),
(5, 'Contenedor 5', 4500, 1, '2020-08-18 21:12:55', '2020-08-18 21:12:55');

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
(1, 'Cachama', 'lorem lorem lorem lorem lorem lorem lorem lorem', '2020-08-18 21:02:11', '2020-08-18 21:02:11'),
(2, 'Tilapia', 'lorem lorem lorem lorem lorem lorem lorem lorem', '2020-08-18 21:02:16', '2020-08-18 21:02:16'),
(3, 'Salmon', 'lorem lorem lorem lorem lorem lorem lorem lorem', '2020-08-18 21:02:21', '2020-08-18 21:02:21'),
(4, 'Sábalo', 'lorem lorem lorem lorem lorem lorem lorem lorem', '2020-08-18 21:03:27', '2020-08-18 21:03:27');

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

--
-- Volcado de datos para la tabla `especies_siembra`
--

INSERT INTO `especies_siembra` (`id`, `id_siembra`, `lote`, `id_especie`, `cantidad`, `peso_inicial`, `created_at`, `updated_at`, `cant_actual`, `peso_actual`) VALUES
(1, 1, '45EN', 1, 2000, 50, '2020-08-18 21:15:05', '2020-08-18 21:15:05', 2000, 50),
(2, 1, '45ES', 3, 3000, 60, '2020-08-18 21:15:05', '2020-08-18 21:15:05', 3000, 60),
(3, 1, '65ST', 4, 3000, 4000, '2020-08-18 21:15:05', '2020-08-18 21:15:05', 3000, 4000),
(4, 2, 'SO09', 1, 9000, 60, '2020-08-18 21:24:22', '2020-08-18 21:30:24', 8500, 40),
(5, 2, 'JI08', 3, 9000, 100, '2020-08-18 21:24:22', '2020-08-18 21:30:24', 8800, 100),
(6, 3, 'K09L', 3, 3000, 90, '2020-08-18 21:25:54', '2020-08-18 21:25:54', 3000, 90),
(7, 3, 'JK08', 4, 9000, 40, '2020-08-18 21:25:54', '2020-08-18 21:25:54', 9000, 40);

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
(5, 'Probiotico', 'Lt', 40, '2020-08-18 21:09:24', '2020-08-18 21:09:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recursos_necesarios`
--

CREATE TABLE `recursos_necesarios` (
  `id` int(11) NOT NULL,
  `id_recurso` int(11) DEFAULT NULL,
  `id_alimento` int(11) DEFAULT NULL,
  `tipo_actividad` varchar(50) DEFAULT NULL,
  `fecha_ra` date DEFAULT NULL,
  `horas_hombre` int(11) DEFAULT NULL,
  `cant_manana` int(11) DEFAULT NULL,
  `cant_tarde` int(11) DEFAULT NULL,
  `detalles` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `recursos_necesarios`
--

INSERT INTO `recursos_necesarios` (`id`, `id_recurso`, `id_alimento`, `tipo_actividad`, `fecha_ra`, `horas_hombre`, `cant_manana`, `cant_tarde`, `detalles`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Alimentacion', '2020-08-18', 4, NULL, NULL, NULL, '2020-08-18 21:27:24', '2020-08-18 21:27:24'),
(2, 1, 1, 'Alimentacion', '2020-08-20', 4, NULL, NULL, NULL, '2020-08-18 21:28:37', '2020-08-18 21:28:37'),
(3, 1, 1, 'Alimentacion', '2020-08-18', 4, NULL, NULL, NULL, '2020-08-18 21:28:55', '2020-08-18 21:28:55'),
(4, 1, 0, 'Llenado', '2020-08-18', 3, 3, 2, NULL, '2020-08-18 21:35:27', '2020-08-18 21:35:27');

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

--
-- Volcado de datos para la tabla `recursos_siembras`
--

INSERT INTO `recursos_siembras` (`id`, `id_registro`, `id_siembra`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2020-08-18 21:27:24', '2020-08-18 21:27:24'),
(2, 2, 3, '2020-08-18 21:28:37', '2020-08-18 21:28:37'),
(3, 3, 3, '2020-08-18 21:28:56', '2020-08-18 21:28:56'),
(4, 4, 1, '2020-08-18 21:35:27', '2020-08-18 21:35:27'),
(5, 4, 2, '2020-08-18 21:35:28', '2020-08-18 21:35:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `id_siembra` int(11) NOT NULL,
  `id_especie` int(11) NOT NULL,
  `fecha_registro` date NOT NULL,
  `tiempo` int(11) NOT NULL,
  `tipo_registro` int(11) NOT NULL,
  `peso_ganado` double DEFAULT NULL,
  `mortalidad` int(11) DEFAULT NULL,
  `biomasa` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `registros`
--

INSERT INTO `registros` (`id`, `id_siembra`, `id_especie`, `fecha_registro`, `tiempo`, `tipo_registro`, `peso_ganado`, `mortalidad`, `biomasa`, `cantidad`, `estado`, `updated_at`, `created_at`) VALUES
(1, 2, 1, '2020-08-18', 1, 0, 40, 500, NULL, NULL, 1, '2020-08-18 21:30:24', '2020-08-18 21:30:24'),
(2, 2, 3, '2020-08-18', 1, 0, 100, 200, NULL, NULL, 1, '2020-08-18 21:30:24', '2020-08-18 21:30:24');

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

--
-- Volcado de datos para la tabla `siembras`
--

INSERT INTO `siembras` (`id`, `nombre_siembra`, `id_contenedor`, `fecha_inicio`, `ini_descanso`, `estado`, `fin_descanso`, `created_at`, `updated_at`, `fecha_alimento`) VALUES
(1, 'Siembra 1', 1, '2020-08-01', NULL, 1, NULL, '2020-08-18 21:15:04', '2020-08-18 21:15:04', NULL),
(2, 'Siembra Agosto', 2, '2020-08-02', NULL, 1, NULL, '2020-08-18 21:24:21', '2020-08-18 21:24:21', NULL),
(3, 'Siembra Septiembre', 3, '2020-08-05', NULL, 1, NULL, '2020-08-18 21:25:54', '2020-08-18 21:28:56', '2020-08-18');

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
(2, 'Isabella', 'issalazar00@outlook.com', NULL, '$2y$10$SXdLLlbOiMy13006qYM5ze4aqPolr80pvC0vVLWQ6ZRzMm2kOYN/C', NULL, 1, '2020-06-30 01:02:57', '2020-06-30 01:02:57'),
(4, 'andres rojas', 'andres@gmail.com', NULL, '$2y$10$7QFjVQunNyOKdn4SMZJBU.K88hW03sW11Py.kzdOLDCpaP4DjiOc.', NULL, 1, '2020-07-15 02:54:52', '2020-07-15 02:54:52');

--
-- Índices para tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `alimentos`
--
ALTER TABLE `alimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `especies`
--
ALTER TABLE `especies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `especies_siembra`
--
ALTER TABLE `especies_siembra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `recursos_necesarios`
--
ALTER TABLE `recursos_necesarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `recursos_siembras`
--
ALTER TABLE `recursos_siembras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `siembras`
--
ALTER TABLE `siembras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
