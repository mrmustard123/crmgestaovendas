-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-07-2025 a las 02:29:19
-- Versión del servidor: 8.0.39
-- Versión de PHP: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crmgestaovendas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity`
--

CREATE TABLE `activity` (
  `activity_id` int UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `description` text,
  `activity_date` date NOT NULL,
  `duration_min` tinyint DEFAULT NULL,
  `status` enum('scheduled','performed','canceled','resheduled') NOT NULL DEFAULT 'scheduled',
  `result` enum('positive','negative','neutral') DEFAULT NULL,
  `comments` text,
  `fk_opportunity` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company`
--

CREATE TABLE `company` (
  `company_id` int UNSIGNED NOT NULL,
  `social_reason` varchar(200) NOT NULL,
  `fantasy_name` varchar(200) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `inscricao_estadual` varchar(20) DEFAULT NULL,
  `inscricao_municipal` varchar(20) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `complement` varchar(100) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Brasil',
  `main_phone` varchar(20) DEFAULT NULL,
  `main_email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `company_size` enum('Big','Medium','Small','Tiny') DEFAULT NULL,
  `status` enum('active','unactive') DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directive`
--

CREATE TABLE `directive` (
  `directive_id` tinyint UNSIGNED NOT NULL,
  `directive` enum('ALLOW','DENY') NOT NULL,
  `id_users_group` tinyint UNSIGNED DEFAULT NULL,
  `id_functionality` tinyint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document`
--

CREATE TABLE `document` (
  `document_id` int UNSIGNED NOT NULL,
  `doc_name` varchar(200) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_type` varchar(10) DEFAULT NULL,
  `size_bytes` bigint DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_opportunity` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `functionality`
--

CREATE TABLE `functionality` (
  `functionality_id` tinyint UNSIGNED NOT NULL,
  `func_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_origin`
--

CREATE TABLE `lead_origin` (
  `lead_origin_id` tinyint UNSIGNED NOT NULL,
  `origin` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `lead_origin`
--

INSERT INTO `lead_origin` (`lead_origin_id`, `origin`, `created_at`, `updated_at`) VALUES
(1, 'QR', '2025-07-01 06:38:47', '2025-07-01 06:38:47'),
(2, 'Site', '2025-07-01 06:38:47', '2025-07-01 06:38:47'),
(3, 'Manual', '2025-07-01 06:38:47', '2025-07-01 06:38:47'),
(4, 'Outro', '2025-07-01 06:38:47', '2025-07-01 06:38:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opportunity`
--

CREATE TABLE `opportunity` (
  `opportunity_id` int UNSIGNED NOT NULL,
  `opportunity_name` varchar(200) NOT NULL,
  `description` text,
  `estimated_sale` decimal(12,2) NOT NULL DEFAULT '0.00',
  `expected_closing_date` date DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `open_date` date DEFAULT NULL,
  `lead_origin_id` tinyint UNSIGNED DEFAULT NULL,
  `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Low',
  `fk_op_status_id` tinyint UNSIGNED DEFAULT NULL,
  `fk_vendor` int UNSIGNED DEFAULT NULL,
  `fk_person` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opportunity_status`
--

CREATE TABLE `opportunity_status` (
  `opportunity_status_id` tinyint UNSIGNED NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'Opened',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `opportunity_status`
--

INSERT INTO `opportunity_status` (`opportunity_status_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Aberto', '2025-07-01 06:51:02', '2025-07-01 06:51:02'),
(2, 'Ganho', '2025-07-01 06:51:02', '2025-07-01 06:51:02'),
(3, 'Perdido', '2025-07-01 06:51:02', '2025-07-01 06:51:02'),
(4, 'Cancelado', '2025-07-01 06:51:02', '2025-07-01 06:51:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `person_id` int UNSIGNED NOT NULL,
  `person_name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `complement` varchar(100) DEFAULT NULL,
  `main_phone` varchar(20) DEFAULT NULL,
  `main_email` varchar(255) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Brasil',
  `birthdate` date DEFAULT NULL,
  `sex` enum('MALE','FEMALE','OTHER') DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widow','stable union') DEFAULT NULL,
  `company_dept` varchar(100) DEFAULT NULL,
  `job_position` varchar(100) DEFAULT NULL,
  `fk_person_role` tinyint UNSIGNED DEFAULT NULL,
  `fk_company` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person_role`
--

CREATE TABLE `person_role` (
  `person_role_id` tinyint UNSIGNED NOT NULL,
  `role_name` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `person_role`
--

INSERT INTO `person_role` (`person_role_id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Lead', '2025-07-01 06:12:07', '2025-07-01 06:12:07'),
(2, 'Cliente', '2025-07-01 06:12:07', '2025-07-01 06:12:07'),
(3, 'Contato', '2025-07-01 06:12:07', '2025-07-01 06:12:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_service`
--

CREATE TABLE `product_service` (
  `product_service_id` int UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text,
  `type` enum('product','service') DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `tax_rate` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `sku` varchar(12) DEFAULT NULL,
  `is_tangible` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prod_serv_opp`
--

CREATE TABLE `prod_serv_opp` (
  `opportunity_id` int UNSIGNED NOT NULL,
  `product_service_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stage`
--

CREATE TABLE `stage` (
  `stage_id` tinyint UNSIGNED NOT NULL,
  `stage_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `stage_order` tinyint NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '1',
  `color_hex` varchar(7) NOT NULL DEFAULT '#007bff',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `stage`
--

INSERT INTO `stage` (`stage_id`, `stage_name`, `description`, `stage_order`, `active`, `color_hex`, `created_at`, `updated_at`) VALUES
(1, 'Apresentação', NULL, 0, 1, '#007bff', '2025-07-09 14:07:00', '2025-07-09 14:07:00'),
(2, 'Proposta', NULL, 1, 1, '#007bff', '2025-07-09 14:07:00', '2025-07-09 14:07:00'),
(3, 'Negociação', NULL, 2, 1, '#007bff', '2025-07-09 14:07:00', '2025-07-09 14:07:00'),
(4, 'Ganho/Perdido', NULL, 3, 1, '#007bff', '2025-07-09 14:07:00', '2025-07-09 14:07:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stage_history`
--

CREATE TABLE `stage_history` (
  `stage_hist_id` int UNSIGNED NOT NULL,
  `won_lost` enum('won','lost') DEFAULT NULL,
  `stage_hist_date` datetime NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_opportunity` int UNSIGNED NOT NULL,
  `fk_stage` tinyint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_config`
--

CREATE TABLE `system_config` (
  `config_id` int UNSIGNED NOT NULL,
  `config_key` varchar(100) NOT NULL,
  `config_value` text,
  `description` text,
  `type` enum('string','number','boolean','json') NOT NULL DEFAULT 'string',
  `category` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `fk_users_group` tinyint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_group`
--

CREATE TABLE `users_group` (
  `user_group_id` tinyint UNSIGNED NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `users_group`
--

INSERT INTO `users_group` (`user_group_id`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'Administradores', '2025-07-02 04:26:56', '2025-07-02 04:26:56'),
(3, 'Gerentes', '2025-07-02 04:26:56', '2025-07-02 04:26:56'),
(4, 'Vendedores', '2025-07-02 04:26:56', '2025-07-02 04:26:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendor`
--

CREATE TABLE `vendor` (
  `vendor_id` int UNSIGNED NOT NULL,
  `vendor_name` varchar(200) NOT NULL,
  `main_phone` varchar(20) DEFAULT NULL,
  `main_email` varchar(255) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `complement` varchar(255) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Brasil',
  `fk_user` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `activity_fk_opportunity_foreign` (`fk_opportunity`);

--
-- Indices de la tabla `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indices de la tabla `directive`
--
ALTER TABLE `directive`
  ADD PRIMARY KEY (`directive_id`),
  ADD KEY `directive_id_functionality_foreign` (`id_functionality`),
  ADD KEY `directive_id_users_group_foreign` (`id_users_group`);

--
-- Indices de la tabla `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `document_fk_opportunity_foreign` (`fk_opportunity`);

--
-- Indices de la tabla `functionality`
--
ALTER TABLE `functionality`
  ADD PRIMARY KEY (`functionality_id`);

--
-- Indices de la tabla `lead_origin`
--
ALTER TABLE `lead_origin`
  ADD PRIMARY KEY (`lead_origin_id`);

--
-- Indices de la tabla `opportunity`
--
ALTER TABLE `opportunity`
  ADD PRIMARY KEY (`opportunity_id`),
  ADD KEY `opportunity_lead_origin_id_foreign` (`lead_origin_id`),
  ADD KEY `opportunity_fk_op_status_id_foreign` (`fk_op_status_id`),
  ADD KEY `opportunity_fk_person_foreign` (`fk_person`),
  ADD KEY `opportunity_fk_vendor_foreign` (`fk_vendor`);

--
-- Indices de la tabla `opportunity_status`
--
ALTER TABLE `opportunity_status`
  ADD PRIMARY KEY (`opportunity_status_id`);

--
-- Indices de la tabla `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`person_id`),
  ADD KEY `person_fk_company_foreign` (`fk_company`),
  ADD KEY `person_fk_person_role_foreign` (`fk_person_role`);

--
-- Indices de la tabla `person_role`
--
ALTER TABLE `person_role`
  ADD PRIMARY KEY (`person_role_id`);

--
-- Indices de la tabla `product_service`
--
ALTER TABLE `product_service`
  ADD PRIMARY KEY (`product_service_id`);

--
-- Indices de la tabla `prod_serv_opp`
--
ALTER TABLE `prod_serv_opp`
  ADD PRIMARY KEY (`opportunity_id`,`product_service_id`),
  ADD KEY `prod_serv_opp_product_service_id_foreign` (`product_service_id`);

--
-- Indices de la tabla `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`stage_id`);

--
-- Indices de la tabla `stage_history`
--
ALTER TABLE `stage_history`
  ADD PRIMARY KEY (`stage_hist_id`),
  ADD UNIQUE KEY `stage_history_unique` (`fk_opportunity`,`fk_stage`) USING BTREE,
  ADD KEY `fk_stage4` (`fk_stage`);

--
-- Indices de la tabla `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`config_id`),
  ADD UNIQUE KEY `system_config_config_key_unique` (`config_key`),
  ADD KEY `idx_category` (`category`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_fk_users_group_foreign` (`fk_users_group`);

--
-- Indices de la tabla `users_group`
--
ALTER TABLE `users_group`
  ADD PRIMARY KEY (`user_group_id`);

--
-- Indices de la tabla `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD KEY `vendor_fk_user_foreign` (`fk_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activity`
--
ALTER TABLE `activity`
  MODIFY `activity_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `directive`
--
ALTER TABLE `directive`
  MODIFY `directive_id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `document`
--
ALTER TABLE `document`
  MODIFY `document_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `functionality`
--
ALTER TABLE `functionality`
  MODIFY `functionality_id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lead_origin`
--
ALTER TABLE `lead_origin`
  MODIFY `lead_origin_id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `opportunity`
--
ALTER TABLE `opportunity`
  MODIFY `opportunity_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opportunity_status`
--
ALTER TABLE `opportunity_status`
  MODIFY `opportunity_status_id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `person_role`
--
ALTER TABLE `person_role`
  MODIFY `person_role_id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `product_service`
--
ALTER TABLE `product_service`
  MODIFY `product_service_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stage`
--
ALTER TABLE `stage`
  MODIFY `stage_id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `stage_history`
--
ALTER TABLE `stage_history`
  MODIFY `stage_hist_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `system_config`
--
ALTER TABLE `system_config`
  MODIFY `config_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users_group`
--
ALTER TABLE `users_group`
  MODIFY `user_group_id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendor_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_fk_opportunity_foreign` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `directive`
--
ALTER TABLE `directive`
  ADD CONSTRAINT `directive_id_functionality_foreign` FOREIGN KEY (`id_functionality`) REFERENCES `functionality` (`functionality_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `directive_id_users_group_foreign` FOREIGN KEY (`id_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_fk_opportunity_foreign` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `opportunity`
--
ALTER TABLE `opportunity`
  ADD CONSTRAINT `opportunity_fk_op_status_id_foreign` FOREIGN KEY (`fk_op_status_id`) REFERENCES `opportunity_status` (`opportunity_status_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `opportunity_fk_person_foreign` FOREIGN KEY (`fk_person`) REFERENCES `person` (`person_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `opportunity_fk_vendor_foreign` FOREIGN KEY (`fk_vendor`) REFERENCES `vendor` (`vendor_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `opportunity_lead_origin_id_foreign` FOREIGN KEY (`lead_origin_id`) REFERENCES `lead_origin` (`lead_origin_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_fk_company_foreign` FOREIGN KEY (`fk_company`) REFERENCES `company` (`company_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `person_fk_person_role_foreign` FOREIGN KEY (`fk_person_role`) REFERENCES `person_role` (`person_role_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `prod_serv_opp`
--
ALTER TABLE `prod_serv_opp`
  ADD CONSTRAINT `prod_serv_opp_opportunity_id_foreign` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunity` (`opportunity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prod_serv_opp_product_service_id_foreign` FOREIGN KEY (`product_service_id`) REFERENCES `product_service` (`product_service_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `stage_history`
--
ALTER TABLE `stage_history`
  ADD CONSTRAINT `fk_opportunity4` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stage4` FOREIGN KEY (`fk_stage`) REFERENCES `stage` (`stage_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_fk_users_group_foreign` FOREIGN KEY (`fk_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vendor`
--
ALTER TABLE `vendor`
  ADD CONSTRAINT `vendor_fk_user_foreign` FOREIGN KEY (`fk_user`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
