/*
Navicat MySQL Data Transfer

Source Server         : crmgestaovendas
Source Server Version : 80039
Source Host           : localhost:3306
Source Database       : crmgestaovendas

Target Server Type    : MYSQL
Target Server Version : 80039
File Encoding         : 65001

Date: 2025-07-17 17:20:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `activity`
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `activity_id` int unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `description` text,
  `activity_date` date NOT NULL,
  `duration_min` tinyint DEFAULT NULL,
  `status` enum('scheduled','performed','canceled','resheduled') NOT NULL DEFAULT 'scheduled',
  `result` enum('positive','negative','neutral') DEFAULT NULL,
  `comments` text,
  `fk_opportunity` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `activity_fk_opportunity_foreign` (`fk_opportunity`),
  CONSTRAINT `activity_fk_opportunity_foreign` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of activity
-- ----------------------------
INSERT INTO `activity` VALUES ('1', 'Contato inicial com o Lead: Romer Saucedo', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-05', null, 'scheduled', null, null, '2', null, null);
INSERT INTO `activity` VALUES ('2', 'Contato inicial com o Lead: Sidney Saucedo', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-05', null, 'scheduled', null, null, '3', null, null);
INSERT INTO `activity` VALUES ('3', 'Contato inicial com o Lead: Lorena Tellez', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-07', null, 'scheduled', null, null, '4', null, null);
INSERT INTO `activity` VALUES ('4', 'Contato inicial com o Lead: Sidney Saucedo', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-08', null, 'scheduled', null, null, '5', '2025-07-08 03:54:34', '2025-07-08 03:54:34');
INSERT INTO `activity` VALUES ('5', 'Contato inicial com o Lead: Leonardo Tellez', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-08', null, 'scheduled', null, null, '6', '2025-07-08 03:58:17', '2025-07-08 03:58:17');
INSERT INTO `activity` VALUES ('6', 'Contato inicial com o Lead: Jorge Tellez', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-08', null, 'scheduled', null, null, '7', '2025-07-08 04:11:46', '2025-07-08 04:11:46');
INSERT INTO `activity` VALUES ('7', 'Contato inicial com o Lead: Mariney Saucedo', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-08', null, 'scheduled', null, null, '8', '2025-07-08 04:23:23', '2025-07-08 04:23:23');
INSERT INTO `activity` VALUES ('8', 'Contato inicial com o Lead: Romer Saucedo', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-08', null, 'scheduled', null, null, '9', '2025-07-08 21:24:56', '2025-07-08 21:24:56');
INSERT INTO `activity` VALUES ('9', 'Contato inicial com o Lead: Carla Moron', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-08', null, 'scheduled', null, null, '10', '2025-07-08 21:29:48', '2025-07-08 21:29:48');
INSERT INTO `activity` VALUES ('10', 'Contato inicial com o Lead: Germán Bush', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-09', null, 'scheduled', null, null, '11', '2025-07-09 00:11:37', '2025-07-09 00:11:37');
INSERT INTO `activity` VALUES ('11', 'Contato inicial com o Lead: Virginio Lema', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-09', null, 'scheduled', null, null, '12', '2025-07-09 01:25:45', '2025-07-09 01:25:45');
INSERT INTO `activity` VALUES ('12', 'Contato inicial com o Lead: Carla Moron', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-09', null, 'scheduled', null, null, '13', '2025-07-09 02:32:57', '2025-07-09 02:32:57');
INSERT INTO `activity` VALUES ('13', 'Contato inicial com o Lead: Sidney Saucedo', 'Primeiro contato com o Lead para apresentar os produtos/serviços.', '2025-07-09', null, 'scheduled', null, null, '14', '2025-07-09 02:34:48', '2025-07-09 02:34:48');
INSERT INTO `activity` VALUES ('14', 'Actividad 1 Sr. Rommer', 'Haremos una actividad para la veta de cables.', '2025-07-17', '97', 'scheduled', null, 'Nada.', '2', '2025-07-17 14:38:03', '2025-07-17 14:38:03');
INSERT INTO `activity` VALUES ('15', 'Actividad 2 Sidney Saucedo', 'Segunda actividad con Sidney', '2025-07-24', '97', 'scheduled', null, 'Nada.', '3', '2025-07-17 15:23:37', '2025-07-17 15:23:37');

-- ----------------------------
-- Table structure for `company`
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `company_id` int unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of company
-- ----------------------------
INSERT INTO `company` VALUES ('7', 'Cooperativa Rural de Electrificación', 'CRE', '65654654', '654654', '67664654', null, 'Av. Bush #10', null, 'Palermo', 'Santa Cruz de la Sierra', 'SC', 'Bolivia', '65468468', 'cre@cre.com', null, null, 'unactive', 'Cooperativa', '2025-07-05 20:19:23', '2025-07-05 20:19:23');

-- ----------------------------
-- Table structure for `directive`
-- ----------------------------
DROP TABLE IF EXISTS `directive`;
CREATE TABLE `directive` (
  `directive_id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `directive` enum('ALLOW','DENY') NOT NULL,
  `id_users_group` tinyint unsigned DEFAULT NULL,
  `id_functionality` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`directive_id`),
  KEY `directive_id_functionality_foreign` (`id_functionality`),
  KEY `directive_id_users_group_foreign` (`id_users_group`),
  CONSTRAINT `directive_id_functionality_foreign` FOREIGN KEY (`id_functionality`) REFERENCES `functionality` (`functionality_id`) ON UPDATE CASCADE,
  CONSTRAINT `directive_id_users_group_foreign` FOREIGN KEY (`id_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of directive
-- ----------------------------
INSERT INTO `directive` VALUES ('1', 'ALLOW', '1', '1', null, null);
INSERT INTO `directive` VALUES ('2', 'ALLOW', '1', '2', null, null);

-- ----------------------------
-- Table structure for `document`
-- ----------------------------
DROP TABLE IF EXISTS `document`;
CREATE TABLE `document` (
  `docuent_id` int unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` bigint DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text,
  `uploaded_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` time DEFAULT NULL,
  `fk_opportunity` int unsigned DEFAULT NULL,
  PRIMARY KEY (`docuent_id`),
  KEY `fk_opportunity1` (`fk_opportunity`),
  CONSTRAINT `fk_opportunity1` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of document
-- ----------------------------
INSERT INTO `document` VALUES ('1', 'Merwin_L_Bohan_Oral_History_Interview_ Harry_S_Truman.pdf', 'application/pdf', '1493560', 'documents/2/NX8lkl0TusBWvdizr8Yg1AWBikvaVHCnZuDPQsAh.pdf', 'jhgfjfjgf', '2025-07-17 00:00:00', '2025-07-17 19:51:26', '19:51:26', '2');

-- ----------------------------
-- Table structure for `failed_jobs`
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for `functionality`
-- ----------------------------
DROP TABLE IF EXISTS `functionality`;
CREATE TABLE `functionality` (
  `functionality_id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `func_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`functionality_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of functionality
-- ----------------------------
INSERT INTO `functionality` VALUES ('1', 'Cadstrar usuário do sistema', null, null);
INSERT INTO `functionality` VALUES ('2', 'Cadastrar Produtos-Serviços', null, null);

-- ----------------------------
-- Table structure for `lead_origin`
-- ----------------------------
DROP TABLE IF EXISTS `lead_origin`;
CREATE TABLE `lead_origin` (
  `lead_origin_id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `origin` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lead_origin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of lead_origin
-- ----------------------------
INSERT INTO `lead_origin` VALUES ('1', 'QR', '2025-07-01 02:38:47', '2025-07-01 02:38:47');
INSERT INTO `lead_origin` VALUES ('2', 'Site', '2025-07-01 02:38:47', '2025-07-01 02:38:47');
INSERT INTO `lead_origin` VALUES ('3', 'Manual', '2025-07-01 02:38:47', '2025-07-01 02:38:47');
INSERT INTO `lead_origin` VALUES ('4', 'Outro', '2025-07-01 02:38:47', '2025-07-01 02:38:47');

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_100000_create_password_reset_tokens_table', '1');
INSERT INTO `migrations` VALUES ('2', '2019_08_19_000000_create_failed_jobs_table', '1');
INSERT INTO `migrations` VALUES ('3', '2019_12_14_000001_create_personal_access_tokens_table', '1');
INSERT INTO `migrations` VALUES ('4', '2025_06_30_210202_create_users_group_table', '1');
INSERT INTO `migrations` VALUES ('5', '2014_10_12_000000_create_users_table', '2');
INSERT INTO `migrations` VALUES ('6', '2025_06_30_232524_create_functionality_table', '3');
INSERT INTO `migrations` VALUES ('7', '2025_06_30_232831_create_directive_table', '4');
INSERT INTO `migrations` VALUES ('8', '2025_06_30_233329_create_vendor_table', '5');
INSERT INTO `migrations` VALUES ('9', '2025_06_30_233651_create_company_table', '6');
INSERT INTO `migrations` VALUES ('10', '2025_06_30_234058_create_person_role_table', '7');
INSERT INTO `migrations` VALUES ('11', '2025_07_01_021609_create_person_table', '8');
INSERT INTO `migrations` VALUES ('12', '2025_07_01_021943_create_lead_origin_table', '9');
INSERT INTO `migrations` VALUES ('13', '2025_07_01_024028_create_opportunity_status', '10');
INSERT INTO `migrations` VALUES ('14', '2025_07_01_025253_create_opportunity_table', '11');
INSERT INTO `migrations` VALUES ('15', '2025_07_01_025808_create_document_table', '12');
INSERT INTO `migrations` VALUES ('16', '2025_07_01_030237_create_product_service_table', '13');
INSERT INTO `migrations` VALUES ('17', '2025_07_01_030605_create_prod_serv_opp', '14');
INSERT INTO `migrations` VALUES ('18', '2025_07_01_030953_create_activity_table', '15');
INSERT INTO `migrations` VALUES ('19', '2025_07_01_031318_create_stage_table', '16');
INSERT INTO `migrations` VALUES ('20', '2025_07_01_031948_create_stage_history_table', '17');
INSERT INTO `migrations` VALUES ('21', '2025_07_01_032355_create_system_config_table', '18');

-- ----------------------------
-- Table structure for `opportunity`
-- ----------------------------
DROP TABLE IF EXISTS `opportunity`;
CREATE TABLE `opportunity` (
  `opportunity_id` int unsigned NOT NULL AUTO_INCREMENT,
  `opportunity_name` varchar(200) NOT NULL,
  `description` text,
  `estimated_sale` decimal(12,2) NOT NULL DEFAULT '0.00',
  `expected_closing_date` date DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `open_date` date DEFAULT NULL,
  `lead_origin_id` tinyint unsigned DEFAULT NULL,
  `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Low',
  `fk_op_status_id` tinyint unsigned DEFAULT NULL,
  `fk_vendor` int unsigned DEFAULT NULL,
  `fk_person` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`opportunity_id`),
  KEY `opportunity_lead_origin_id_foreign` (`lead_origin_id`),
  KEY `opportunity_fk_op_status_id_foreign` (`fk_op_status_id`),
  KEY `opportunity_fk_person_foreign` (`fk_person`),
  KEY `opportunity_fk_vendor_foreign` (`fk_vendor`),
  CONSTRAINT `opportunity_fk_op_status_id_foreign` FOREIGN KEY (`fk_op_status_id`) REFERENCES `opportunity_status` (`opportunity_status_id`) ON UPDATE CASCADE,
  CONSTRAINT `opportunity_fk_person_foreign` FOREIGN KEY (`fk_person`) REFERENCES `person` (`person_id`) ON UPDATE CASCADE,
  CONSTRAINT `opportunity_fk_vendor_foreign` FOREIGN KEY (`fk_vendor`) REFERENCES `vendor` (`vendor_id`) ON UPDATE CASCADE,
  CONSTRAINT `opportunity_lead_origin_id_foreign` FOREIGN KEY (`lead_origin_id`) REFERENCES `lead_origin` (`lead_origin_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of opportunity
-- ----------------------------
INSERT INTO `opportunity` VALUES ('2', 'Oportunidad de venta de cables a CRE', 'Venta de cables', '1000.00', '2025-07-08', 'BRL', null, '1', 'Low', '2', '1', '5', '2025-07-05 20:19:23', '2025-07-17 19:51:26');
INSERT INTO `opportunity` VALUES ('3', 'Oportunidad Sidney Saucedo', 'Venta de publicidad', '1500.00', '2025-07-07', 'BRL', null, '1', 'High', '1', '1', '6', '2025-07-05 22:24:26', '2025-07-17 15:23:37');
INSERT INTO `opportunity` VALUES ('4', 'Oportunidad de venta de maní', 'Venta de maní', '22.25', '2025-07-28', 'BRL', null, '2', 'Low', '3', '1', '7', '2025-07-07 14:04:47', '2025-07-11 22:57:07');
INSERT INTO `opportunity` VALUES ('5', 'Oportunidad nueva de negocio con Sidney', 'Nuevo negocio con Sidney', '62331.00', '2025-07-14', 'BRL', null, '1', 'Low', '1', '1', '6', '2025-07-08 03:54:34', '2025-07-10 14:20:26');
INSERT INTO `opportunity` VALUES ('6', 'Oportunidad de venta de pollos', 'Venta de pollos', '10005.00', '2025-07-15', 'BRL', null, '2', 'Low', '1', '1', '8', '2025-07-08 03:58:17', '2025-07-08 03:58:17');
INSERT INTO `opportunity` VALUES ('7', 'Oportunidad de venta de vino', 'Venta de vino', '2561.00', '2025-07-15', 'BRL', null, '3', 'Low', '1', '1', '9', '2025-07-08 04:11:46', '2025-07-08 04:11:46');
INSERT INTO `opportunity` VALUES ('8', 'Oportunidad de venta de harina integral', 'Venta de harina integral', '2563.00', '2025-07-29', 'BRL', null, '1', 'Low', '1', '1', '10', '2025-07-08 04:23:23', '2025-07-08 04:23:23');
INSERT INTO `opportunity` VALUES ('9', 'Oportunidad de venta de muebles al Sr. Romer', 'Venta de muebles', '11112.00', '2025-07-22', 'BRL', null, '1', 'Low', '2', '1', '5', '2025-07-08 21:24:56', '2025-07-17 02:11:21');
INSERT INTO `opportunity` VALUES ('10', 'Oportunidad de venta de cosméticos', 'Venta de cosméticos', '50.25', '2025-07-14', 'BRL', null, '1', 'Low', '3', '1', '11', '2025-07-08 21:29:48', '2025-07-16 20:44:01');
INSERT INTO `opportunity` VALUES ('11', 'Oportunidad de venta de armas', 'Venta de armas', '100005.00', '2025-07-15', 'BRL', null, '2', 'Low', '3', '1', '12', '2025-07-09 00:11:37', '2025-07-11 22:50:10');
INSERT INTO `opportunity` VALUES ('12', 'Oportunidad de venta de servicio de streaming', 'Venta de servicio de streaming', '1000.50', '2025-07-22', 'BRL', null, '2', 'Low', '3', '1', '13', '2025-07-09 01:25:24', '2025-07-11 22:54:46');
INSERT INTO `opportunity` VALUES ('13', 'Oportunidad de venta de más cosméticos', 'Venta de más cosméticos', '1000.25', '2025-07-21', 'BRL', null, '4', 'Low', '3', '1', '11', '2025-07-09 02:32:57', '2025-07-17 02:10:03');
INSERT INTO `opportunity` VALUES ('14', 'Oportunidad de venta de servicios de publicidad', 'Venta de servicios de publicidad', '1234.56', '2025-07-23', 'BRL', null, '2', 'Low', '1', '1', '6', '2025-07-09 02:34:48', '2025-07-09 02:34:48');

-- ----------------------------
-- Table structure for `opportunity_status`
-- ----------------------------
DROP TABLE IF EXISTS `opportunity_status`;
CREATE TABLE `opportunity_status` (
  `opportunity_status_id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL DEFAULT 'Opened',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`opportunity_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of opportunity_status
-- ----------------------------
INSERT INTO `opportunity_status` VALUES ('1', 'Aberto', '2025-07-01 02:51:02', '2025-07-01 02:51:02');
INSERT INTO `opportunity_status` VALUES ('2', 'Ganho', '2025-07-01 02:51:02', '2025-07-01 02:51:02');
INSERT INTO `opportunity_status` VALUES ('3', 'Perdido', '2025-07-01 02:51:02', '2025-07-01 02:51:02');
INSERT INTO `opportunity_status` VALUES ('4', 'Cancelado', '2025-07-01 02:51:02', '2025-07-01 02:51:02');

-- ----------------------------
-- Table structure for `password_reset_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for `person`
-- ----------------------------
DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `person_id` int unsigned NOT NULL AUTO_INCREMENT,
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
  `fk_person_role` tinyint unsigned DEFAULT NULL,
  `fk_company` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`person_id`),
  KEY `person_fk_company_foreign` (`fk_company`),
  KEY `person_fk_person_role_foreign` (`fk_person_role`),
  CONSTRAINT `person_fk_company_foreign` FOREIGN KEY (`fk_company`) REFERENCES `company` (`company_id`) ON UPDATE CASCADE,
  CONSTRAINT `person_fk_person_role_foreign` FOREIGN KEY (`fk_person_role`) REFERENCES `person_role` (`person_role_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of person
-- ----------------------------
INSERT INTO `person` VALUES ('5', 'Romer Saucedo', 'Av. Guaitú #10', null, '654654654', 'romer@cre.com', null, null, null, 'Santa Bárbara', 'Buenavista', 'SC', 'Bolivia', null, null, null, null, 'Gerente', '1', '7', '2025-07-05 20:19:23', '2025-07-05 20:19:23');
INSERT INTO `person` VALUES ('6', 'Sidney Saucedo', 'Av. Guaitú #10', null, '654654654', 'romer@cre.com', null, null, null, 'Santa Bárbara', 'Buenavista', 'SC', 'Bolivia', null, null, null, null, 'Gerente', '1', '7', '2025-07-05 22:24:26', '2025-07-09 02:34:48');
INSERT INTO `person` VALUES ('7', 'Lorena Tellez', 'Calle Sauce #34', null, '654657468', 'lorena@crm.com', null, null, null, 'Sevilla Pinatar', 'Santa Cruz de la Sierra', 'SC', 'Bolivia', null, null, null, null, 'Gerenta', '1', null, '2025-07-07 14:04:47', '2025-07-07 14:04:47');
INSERT INTO `person` VALUES ('8', 'Leonardo Tellez', 'Calle Surutú #10', null, '78642247', 'leonardo616@gmail.com', null, null, null, 'Palermo', 'Buenavista', 'SC', 'Bolivia', null, null, null, null, 'Gerente', '1', null, '2025-07-08 03:58:17', '2025-07-08 03:58:17');
INSERT INTO `person` VALUES ('9', 'Jorge Tellez', 'Calle Surutú #10', null, '87635734', 'jorge@crm.com', null, null, null, 'Santa Ana', 'Buenavista', 'SC', 'Bolivia', null, null, null, null, 'Gerente', '1', null, '2025-07-08 04:11:46', '2025-07-08 04:11:46');
INSERT INTO `person` VALUES ('10', 'Mariney Saucedo', 'Calle Surutú #10', null, '35435435', 'mariney@crm.com', null, null, null, 'Barrio Santa Ana', 'Buenavista', 'SC', 'Bolivia', null, null, null, null, 'Gerenta', '1', null, '2025-07-08 04:23:23', '2025-07-08 04:23:23');
INSERT INTO `person` VALUES ('11', 'Carla Moron', 'Calle Vallegrande #10', null, '654646631', 'carla@crm.com', null, null, null, 'El Pari', 'Santa Cruz de la Sierra', 'SC', 'Bolivia', null, null, null, null, 'Gerenta', '1', '7', '2025-07-08 21:29:48', '2025-07-08 21:29:48');
INSERT INTO `person` VALUES ('12', 'Germán Bush', 'Av. Busch #10', null, '646468', 'german@crm.com', null, null, null, 'Palermo', 'Santa Cruz de la Sierra', 'SC', 'Bolivia', null, null, null, null, 'Coronel', '1', '7', '2025-07-09 00:11:37', '2025-07-09 00:11:37');
INSERT INTO `person` VALUES ('13', 'Virginio Lema', 'Calle Tarija #10', null, '67645645', 'virginio@crm.com', null, null, null, 'San Lorenzo', 'Tarija', 'TJ', 'Bolivia', null, null, null, null, 'Gerente', '1', '7', '2025-07-09 01:25:05', '2025-07-09 01:25:05');

-- ----------------------------
-- Table structure for `personal_access_tokens`
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for `person_role`
-- ----------------------------
DROP TABLE IF EXISTS `person_role`;
CREATE TABLE `person_role` (
  `person_role_id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`person_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of person_role
-- ----------------------------
INSERT INTO `person_role` VALUES ('1', 'Lead', '2025-07-01 02:12:07', '2025-07-01 02:12:07');
INSERT INTO `person_role` VALUES ('2', 'Cliente', '2025-07-01 02:12:07', '2025-07-01 02:12:07');
INSERT INTO `person_role` VALUES ('3', 'Contato', '2025-07-01 02:12:07', '2025-07-01 02:12:07');

-- ----------------------------
-- Table structure for `product_service`
-- ----------------------------
DROP TABLE IF EXISTS `product_service`;
CREATE TABLE `product_service` (
  `product_service_id` int unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of product_service
-- ----------------------------
INSERT INTO `product_service` VALUES ('1', 'Suzuki Samurai', 'Todo terreno Suzuki Samurai', 'product', 'General', '10000.00', 'Unit', '0.00', '1', null, '1', '2025-07-04 04:16:49', '2025-07-05 22:26:15');
INSERT INTO `product_service` VALUES ('2', 'Dzire', 'Auto Suzuki Dzire', 'product', 'Autos', '15000.00', '1', '100.00', '1', '123456789', '1', '2025-07-04 13:49:10', '2025-07-04 13:49:10');
INSERT INTO `product_service` VALUES ('3', 'Starlet', 'Auto Toyota Starlet', 'product', 'Autos', '895435.00', '1', '100.00', '1', '654835485', '1', '2025-07-04 14:04:51', '2025-07-04 14:04:51');
INSERT INTO `product_service` VALUES ('4', 'Producto1', 'Producto1', 'product', 'Categoria1', '6545325.00', '1', '100.00', '1', '65468785', '1', '2025-07-04 14:05:54', '2025-07-09 02:34:48');
INSERT INTO `product_service` VALUES ('5', 'Producto2', 'Producto2', 'product', 'Categoria1', '8945.25', '1', '50.00', '1', '98765465', '1', '2025-07-04 14:07:36', '2025-07-09 02:32:57');
INSERT INTO `product_service` VALUES ('6', 'Producto3', 'Producto3', 'product', 'Categoria1', '1234.56', '1', '100.00', '1', '32165486', '1', '2025-07-04 14:09:04', '2025-07-08 21:24:56');
INSERT INTO `product_service` VALUES ('7', 'Producto4', 'Producto4', 'product', 'Categoria1', '6432.27', '1', '55.00', '1', '654872456', '1', '2025-07-04 14:10:02', '2025-07-08 21:29:48');
INSERT INTO `product_service` VALUES ('8', 'Producto5', 'Producto5', 'product', 'Categoria1', '789425.00', '1', '10.00', '1', '65467987', '1', '2025-07-04 14:10:55', '2025-07-04 14:10:55');
INSERT INTO `product_service` VALUES ('9', 'Jarra', 'Una jarra de plastico', 'product', 'Cocina', '10.12', '1', '10.00', '1', '654654', '1', '2025-07-05 01:25:00', '2025-07-05 01:25:00');
INSERT INTO `product_service` VALUES ('10', 'Paraguas', 'Para el agua', 'product', 'Parguas', '100.25', '1', '10.00', '1', '987654654', '1', '2025-07-05 01:26:17', '2025-07-05 01:26:17');

-- ----------------------------
-- Table structure for `prod_serv_opp`
-- ----------------------------
DROP TABLE IF EXISTS `prod_serv_opp`;
CREATE TABLE `prod_serv_opp` (
  `opportunity_id` int unsigned NOT NULL,
  `product_service_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`opportunity_id`,`product_service_id`),
  KEY `prod_serv_opp_product_service_id_foreign` (`product_service_id`),
  CONSTRAINT `prod_serv_opp_opportunity_id_foreign` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunity` (`opportunity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `prod_serv_opp_product_service_id_foreign` FOREIGN KEY (`product_service_id`) REFERENCES `product_service` (`product_service_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of prod_serv_opp
-- ----------------------------
INSERT INTO `prod_serv_opp` VALUES ('3', '1', '2025-07-05 22:26:01', '2025-07-05 22:26:01');
INSERT INTO `prod_serv_opp` VALUES ('3', '4', '2025-07-05 22:26:11', '2025-07-05 22:26:11');
INSERT INTO `prod_serv_opp` VALUES ('3', '5', '2025-07-05 22:26:06', '2025-07-05 22:26:06');
INSERT INTO `prod_serv_opp` VALUES ('4', '4', '2025-07-07 14:04:47', '2025-07-07 14:04:47');
INSERT INTO `prod_serv_opp` VALUES ('5', '6', '2025-07-08 03:54:34', '2025-07-08 03:54:34');
INSERT INTO `prod_serv_opp` VALUES ('6', '4', '2025-07-08 03:58:17', '2025-07-08 03:58:17');
INSERT INTO `prod_serv_opp` VALUES ('7', '5', '2025-07-08 04:11:46', '2025-07-08 04:11:46');
INSERT INTO `prod_serv_opp` VALUES ('8', '7', '2025-07-08 04:23:23', '2025-07-08 04:23:23');
INSERT INTO `prod_serv_opp` VALUES ('9', '6', '2025-07-08 21:24:56', '2025-07-08 21:24:56');
INSERT INTO `prod_serv_opp` VALUES ('10', '7', '2025-07-08 21:29:48', '2025-07-08 21:29:48');
INSERT INTO `prod_serv_opp` VALUES ('11', '4', '2025-07-09 00:11:37', '2025-07-09 00:11:37');
INSERT INTO `prod_serv_opp` VALUES ('12', '4', '2025-07-09 01:25:45', '2025-07-09 01:25:45');
INSERT INTO `prod_serv_opp` VALUES ('13', '5', '2025-07-09 02:32:57', '2025-07-09 02:32:57');
INSERT INTO `prod_serv_opp` VALUES ('14', '4', '2025-07-09 02:34:48', '2025-07-09 02:34:48');

-- ----------------------------
-- Table structure for `stage`
-- ----------------------------
DROP TABLE IF EXISTS `stage`;
CREATE TABLE `stage` (
  `stage_id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `stage_order` tinyint NOT NULL DEFAULT '0',
  `active` tinyint NOT NULL DEFAULT '1',
  `color_hex` varchar(7) NOT NULL DEFAULT '#007bff',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of stage
-- ----------------------------
INSERT INTO `stage` VALUES ('1', 'Apresentação', null, '0', '1', '#d1fae5', '2025-07-09 10:07:00', '2025-07-09 10:07:00');
INSERT INTO `stage` VALUES ('2', 'Proposta', null, '1', '1', '#fef3c7', '2025-07-09 10:07:00', '2025-07-09 10:07:00');
INSERT INTO `stage` VALUES ('3', 'Negociação', null, '2', '1', '#ede9fe', '2025-07-09 10:07:00', '2025-07-09 10:07:00');

-- ----------------------------
-- Table structure for `stage_history`
-- ----------------------------
DROP TABLE IF EXISTS `stage_history`;
CREATE TABLE `stage_history` (
  `stage_hist_id` int unsigned NOT NULL AUTO_INCREMENT,
  `won_lost` enum('won','lost') DEFAULT NULL,
  `stage_hist_date` datetime NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fk_opportunity` int unsigned NOT NULL,
  `fk_stage` tinyint unsigned NOT NULL,
  PRIMARY KEY (`stage_hist_id`),
  UNIQUE KEY `stage_history_unique` (`fk_opportunity`,`fk_stage`) USING BTREE,
  KEY `fk_stage4` (`fk_stage`),
  CONSTRAINT `fk_opportunity4` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_stage4` FOREIGN KEY (`fk_stage`) REFERENCES `stage` (`stage_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of stage_history
-- ----------------------------
INSERT INTO `stage_history` VALUES ('1', null, '2025-07-05 01:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-05 20:19:23', '2025-07-11 22:20:30', '2', '1');
INSERT INTO `stage_history` VALUES ('2', 'won', '2025-07-05 02:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-05 22:24:26', '2025-07-11 22:48:20', '2', '2');
INSERT INTO `stage_history` VALUES ('3', null, '2025-07-07 03:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-07 14:04:47', '2025-07-07 14:04:47', '3', '1');
INSERT INTO `stage_history` VALUES ('4', null, '2025-07-08 04:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-08 03:54:34', '2025-07-08 03:54:34', '3', '2');
INSERT INTO `stage_history` VALUES ('5', null, '2025-07-08 05:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-08 03:58:17', '2025-07-08 03:58:17', '4', '1');
INSERT INTO `stage_history` VALUES ('6', null, '2025-07-08 06:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-08 04:11:46', '2025-07-08 04:11:46', '4', '2');
INSERT INTO `stage_history` VALUES ('7', 'won', '2025-07-08 07:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-08 04:23:23', '2025-07-11 22:57:07', '4', '3');
INSERT INTO `stage_history` VALUES ('8', null, '2025-07-08 08:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-08 21:24:56', '2025-07-08 21:24:56', '9', '1');
INSERT INTO `stage_history` VALUES ('9', null, '2025-07-08 09:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-08 21:29:48', '2025-07-08 21:29:48', '10', '1');
INSERT INTO `stage_history` VALUES ('10', 'won', '2025-07-09 10:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-09 00:11:37', '2025-07-11 22:50:10', '11', '1');
INSERT INTO `stage_history` VALUES ('11', 'won', '2025-07-09 11:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-09 01:25:34', '2025-07-11 22:54:46', '12', '1');
INSERT INTO `stage_history` VALUES ('12', 'lost', '2025-07-09 12:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-09 02:32:57', '2025-07-17 02:10:03', '13', '1');
INSERT INTO `stage_history` VALUES ('13', null, '2025-07-09 13:00:00', 'Oportunidade criada, estágio inicial \'Apresentação\'.', '2025-07-09 02:34:48', '2025-07-09 02:34:48', '14', '1');
INSERT INTO `stage_history` VALUES ('14', null, '2025-07-11 14:00:00', 'Oportunidade movida para novo estagio', '2025-07-11 15:11:06', '2025-07-11 15:11:06', '9', '2');
INSERT INTO `stage_history` VALUES ('16', 'lost', '2025-07-16 20:43:03', 'Oportunidade movida para novo estagio', '2025-07-16 20:43:03', '2025-07-16 20:44:01', '10', '3');
INSERT INTO `stage_history` VALUES ('17', 'won', '2025-07-17 02:00:15', 'Oportunidade movida para novo estagio', '2025-07-17 02:00:15', '2025-07-17 02:11:21', '9', '3');

-- ----------------------------
-- Table structure for `system_config`
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config` (
  `config_id` int unsigned NOT NULL AUTO_INCREMENT,
  `config_key` varchar(100) NOT NULL,
  `config_value` text,
  `description` text,
  `type` enum('string','number','boolean','json') NOT NULL DEFAULT 'string',
  `category` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `system_config_config_key_unique` (`config_key`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of system_config
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `fk_users_group` tinyint unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_fk_users_group_foreign` (`fk_users_group`),
  CONSTRAINT `users_fk_users_group_foreign` FOREIGN KEY (`fk_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'user1', '$2y$10$rDM2Q1XQZUDnxA0msv3DNuw0LDsiaMTZrf6PgTR3erjtiTacU7g6i', 'leonardo616@gmail.com', null, '1', null, null, null);
INSERT INTO `users` VALUES ('2', 'user2', '$2y$12$p5dM0dWn8cHv.Tl22oeun.6m31dJe8e8/.IOnTnAicsFY.WVIyJvK', 'user2@crm.com', null, '1', null, '2025-07-04 03:33:37', '2025-07-04 03:33:37');
INSERT INTO `users` VALUES ('3', 'user3', '$2y$12$eKsR.qQzyfqriTiLmZGhEeYzRYuq4R89ONnfKNnDr0pGXeSECT8SO', null, null, '4', null, '2025-07-04 15:02:55', '2025-07-04 15:02:55');
INSERT INTO `users` VALUES ('4', 'user4', '$2y$12$qY6AWPym8MiNBbvlEbrfweWQv7z3dn73WqU/BNR6szzIOzUi4jicu', 'user4@crm.com', null, '3', null, '2025-07-05 00:07:54', '2025-07-05 00:07:54');

-- ----------------------------
-- Table structure for `users_group`
-- ----------------------------
DROP TABLE IF EXISTS `users_group`;
CREATE TABLE `users_group` (
  `user_group_id` tinyint unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of users_group
-- ----------------------------
INSERT INTO `users_group` VALUES ('1', 'Administradores', '2025-07-02 00:26:56', '2025-07-02 00:26:56');
INSERT INTO `users_group` VALUES ('3', 'Gerentes', '2025-07-02 00:26:56', '2025-07-02 00:26:56');
INSERT INTO `users_group` VALUES ('4', 'Vendedores', '2025-07-02 00:26:56', '2025-07-02 00:26:56');

-- ----------------------------
-- Table structure for `vendor`
-- ----------------------------
DROP TABLE IF EXISTS `vendor`;
CREATE TABLE `vendor` (
  `vendor_id` int unsigned NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(200) NOT NULL,
  `main_phone` varchar(20) DEFAULT NULL,
  `main_email` varchar(255) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `complement` varchar(255) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `country` varchar(50) NOT NULL DEFAULT 'Brasil',
  `fk_user` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`vendor_id`),
  KEY `vendor_fk_user_foreign` (`fk_user`),
  CONSTRAINT `vendor_fk_user_foreign` FOREIGN KEY (`fk_user`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of vendor
-- ----------------------------
INSERT INTO `vendor` VALUES ('1', 'Leonardo Gabriel Téllez Saucedo', '78642247', 'leonardo616@gmail.com', 'Calle Álamo # 15', 'Condominio Sevilla Pinatar', 'Sevilla', 'Santa Cruz de la Sierra', 'SC', 'Bolivia', '3', '2025-07-04 15:02:55', '2025-07-04 15:02:55');
