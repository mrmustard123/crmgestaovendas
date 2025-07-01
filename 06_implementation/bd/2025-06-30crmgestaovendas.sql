-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 30-06-2025 a las 15:13:11
-- Versión del servidor: 5.6.43
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
  `activity_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `description` text,
  `activity_date` date NOT NULL,
  `duration_min` tinyint(4) DEFAULT NULL,
  `status` enum('scheduled','performed','canceled','resheduled') DEFAULT 'scheduled',
  `result` enum('positive','negative','neutral') DEFAULT NULL,
  `comments` text,
  `fk_opportunity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
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
  `country` varchar(50) DEFAULT 'Brasil',
  `main_phone` varchar(20) DEFAULT NULL,
  `main_email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `company_size` enum('Big','Medium','Small','Tiny') DEFAULT NULL,
  `status` enum('active','unactive') DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `directive`
--

CREATE TABLE `directive` (
  `directive_id` tinyint(4) NOT NULL,
  `directive` enum('ALLOW','DENY') NOT NULL,
  `id_users_group` tinyint(4) DEFAULT NULL,
  `id_functionality` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document`
--

CREATE TABLE `document` (
  `document_id` int(11) NOT NULL,
  `doc_name` varchar(200) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_type` varchar(10) DEFAULT NULL,
  `size_bytes` bigint(20) DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_opportunity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `functionality`
--

CREATE TABLE `functionality` (
  `functionality_id` tinyint(4) NOT NULL,
  `func_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_origin`
--

CREATE TABLE `lead_origin` (
  `lead_origin_id` tinyint(4) NOT NULL,
  `origin` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lead_origin`
--

INSERT INTO `lead_origin` (`lead_origin_id`, `origin`, `created_at`, `updated_at`) VALUES
(1, 'QR', '2025-06-28 16:29:29', '2025-06-28 16:29:29'),
(2, 'Site', '2025-06-28 16:29:35', '2025-06-28 16:29:35'),
(3, 'Manual', '2025-06-28 16:29:39', '2025-06-28 16:29:39'),
(4, 'Outro', '2025-06-28 16:29:44', '2025-06-28 16:29:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opportunity`
--

CREATE TABLE `opportunity` (
  `opportunity_id` int(11) NOT NULL,
  `opportunity_name` varchar(200) NOT NULL,
  `description` text,
  `estimated_sale` decimal(12,2) DEFAULT '0.00',
  `expected_closing_date` date DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `open_date` date DEFAULT NULL,
  `lead_origin_id` tinyint(4) DEFAULT NULL,
  `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Low',
  `fk_op_status_id` tinyint(4) DEFAULT NULL,
  `fk_vendor` int(11) DEFAULT NULL,
  `fk_person` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opportunity_status`
--

CREATE TABLE `opportunity_status` (
  `opportunity_status_id` tinyint(4) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'opened'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `opportunity_status`
--

INSERT INTO `opportunity_status` (`opportunity_status_id`, `status`) VALUES
(1, 'Aberto'),
(2, 'Ganho'),
(3, 'Perdido'),
(4, 'Cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person`
--

CREATE TABLE `person` (
  `person_id` int(11) NOT NULL,
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
  `country` varchar(50) DEFAULT 'Brasil',
  `birthdate` date DEFAULT NULL,
  `sex` enum('MALE','FEMALE','OTHER') DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widow','stable union') DEFAULT NULL,
  `company_dept` varchar(100) DEFAULT NULL,
  `job_position` varchar(100) DEFAULT NULL,
  `fk_person_role` tinyint(4) DEFAULT NULL,
  `fk_company` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `person_role`
--

CREATE TABLE `person_role` (
  `person_role_id` tinyint(4) NOT NULL,
  `role_name` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `person_role`
--

INSERT INTO `person_role` (`person_role_id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Lead', '2025-06-29 16:03:21', '2025-06-29 16:03:21'),
(2, 'Cliente', '2025-06-29 16:03:21', '2025-06-29 16:03:21'),
(3, 'Contato', '2025-06-29 16:03:21', '2025-06-29 16:03:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_service`
--

CREATE TABLE `product_service` (
  `product_service_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text,
  `type` enum('product','service') DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `tax_rate` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '1',
  `sku` varchar(12) DEFAULT NULL,
  `is_tangible` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prod_serv_opp`
--

CREATE TABLE `prod_serv_opp` (
  `oportunity_id` int(11) NOT NULL,
  `product_service_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stage`
--

CREATE TABLE `stage` (
  `stage_id` tinyint(4) NOT NULL,
  `stage_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `stage_order` tinyint(4) DEFAULT '0',
  `active` tinyint(4) DEFAULT '1',
  `color_hex` varchar(7) DEFAULT '#007bff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stage_history`
--

CREATE TABLE `stage_history` (
  `won_lost` enum('won','lost') DEFAULT NULL,
  `stage_hist_date` date NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_opportunity` int(11) NOT NULL DEFAULT '0',
  `fk_stage` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_config`
--

CREATE TABLE `system_config` (
  `config_id` int(11) NOT NULL,
  `config_key` varchar(100) NOT NULL,
  `config_value` text,
  `description` text,
  `type` enum('string','number','boolean','json') DEFAULT 'string',
  `category` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `fk_users_group` tinyint(4) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_group`
--

CREATE TABLE `users_group` (
  `user_group_id` tinyint(4) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendor`
--

CREATE TABLE `vendor` (
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(200) NOT NULL,
  `main_phone` varchar(20) DEFAULT NULL,
  `main_email` varchar(255) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `complement` varchar(255) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `country` varchar(50) DEFAULT 'Brasil',
  `fk_user` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `fk_opportunity` (`fk_opportunity`);

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
  ADD KEY `fk_users_group2` (`id_users_group`),
  ADD KEY `fk_functionality1` (`id_functionality`);

--
-- Indices de la tabla `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `fk_opportunity1` (`fk_opportunity`);

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
  ADD KEY `fk_person1` (`fk_person`),
  ADD KEY `fk_vendor1` (`fk_vendor`),
  ADD KEY `fk_lead_origin1` (`lead_origin_id`),
  ADD KEY `fk_op_status1` (`fk_op_status_id`);

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
  ADD KEY `fk_company1` (`fk_company`),
  ADD KEY `fk_person_role1` (`fk_person_role`);

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
  ADD PRIMARY KEY (`oportunity_id`,`product_service_id`),
  ADD UNIQUE KEY `idx_prodserv_oppt1` (`oportunity_id`,`product_service_id`) USING BTREE,
  ADD KEY `fk_prod_serv1` (`product_service_id`);

--
-- Indices de la tabla `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`stage_id`);

--
-- Indices de la tabla `stage_history`
--
ALTER TABLE `stage_history`
  ADD PRIMARY KEY (`fk_opportunity`,`fk_stage`),
  ADD UNIQUE KEY `idx_stage_hist1` (`fk_opportunity`,`fk_stage`) USING BTREE,
  ADD KEY `fk_stage1` (`fk_stage`);

--
-- Indices de la tabla `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`config_id`),
  ADD UNIQUE KEY `config_key` (`config_key`),
  ADD KEY `idx_key` (`config_key`),
  ADD KEY `idx_category` (`category`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_username_unique` (`username`) USING BTREE,
  ADD UNIQUE KEY `users_email_unique` (`email`) USING BTREE,
  ADD KEY `fk_users_group` (`fk_users_group`);

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
  ADD KEY `fk_user1` (`fk_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activity`
--
ALTER TABLE `activity`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `directive`
--
ALTER TABLE `directive`
  MODIFY `directive_id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `document`
--
ALTER TABLE `document`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `functionality`
--
ALTER TABLE `functionality`
  MODIFY `functionality_id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lead_origin`
--
ALTER TABLE `lead_origin`
  MODIFY `lead_origin_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `opportunity`
--
ALTER TABLE `opportunity`
  MODIFY `opportunity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opportunity_status`
--
ALTER TABLE `opportunity_status`
  MODIFY `opportunity_status_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `person`
--
ALTER TABLE `person`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `person_role`
--
ALTER TABLE `person_role`
  MODIFY `person_role_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `product_service`
--
ALTER TABLE `product_service`
  MODIFY `product_service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stage`
--
ALTER TABLE `stage`
  MODIFY `stage_id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `system_config`
--
ALTER TABLE `system_config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users_group`
--
ALTER TABLE `users_group`
  MODIFY `user_group_id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `fk_opportunity` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `directive`
--
ALTER TABLE `directive`
  ADD CONSTRAINT `fk_functionality1` FOREIGN KEY (`id_functionality`) REFERENCES `functionality` (`functionality_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_group2` FOREIGN KEY (`id_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `fk_opportunity1` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `opportunity`
--
ALTER TABLE `opportunity`
  ADD CONSTRAINT `fk_lead_origin1` FOREIGN KEY (`lead_origin_id`) REFERENCES `lead_origin` (`lead_origin_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_op_status1` FOREIGN KEY (`fk_op_status_id`) REFERENCES `opportunity_status` (`opportunity_status_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_person1` FOREIGN KEY (`fk_person`) REFERENCES `person` (`person_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vendor1` FOREIGN KEY (`fk_vendor`) REFERENCES `vendor` (`vendor_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `fk_company1` FOREIGN KEY (`fk_company`) REFERENCES `company` (`company_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_person_role1` FOREIGN KEY (`fk_person_role`) REFERENCES `person_role` (`person_role_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `prod_serv_opp`
--
ALTER TABLE `prod_serv_opp`
  ADD CONSTRAINT `fk:_opportunity5` FOREIGN KEY (`oportunity_id`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prod_serv1` FOREIGN KEY (`product_service_id`) REFERENCES `product_service` (`product_service_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `stage_history`
--
ALTER TABLE `stage_history`
  ADD CONSTRAINT `fk_oportunity4` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stage1` FOREIGN KEY (`fk_stage`) REFERENCES `stage` (`stage_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_group` FOREIGN KEY (`fk_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vendor`
--
ALTER TABLE `vendor`
  ADD CONSTRAINT `fk_user1` FOREIGN KEY (`fk_user`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
