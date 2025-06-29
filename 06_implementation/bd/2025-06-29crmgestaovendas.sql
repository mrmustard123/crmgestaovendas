/*
Navicat MySQL Data Transfer

Source Server         : crmgestaovendas
Source Server Version : 50643
Source Host           : localhost:3306
Source Database       : crmgestaovendas

Target Server Type    : MYSQL
Target Server Version : 50643
File Encoding         : 65001

Date: 2025-06-29 11:47:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `activity`
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `description` text,
  `activity_date` date NOT NULL,
  `duration_min` tinyint(4) DEFAULT NULL,
  `status` enum('scheduled','performed','canceled','resheduled') DEFAULT 'scheduled',
  `result` enum('positive','negative','neutral') DEFAULT NULL,
  `comments` text,
  `fk_opportunity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`activity_id`),
  KEY `fk_opportunity` (`fk_opportunity`),
  CONSTRAINT `fk_opportunity` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of activity
-- ----------------------------

-- ----------------------------
-- Table structure for `company`
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of company
-- ----------------------------

-- ----------------------------
-- Table structure for `directive`
-- ----------------------------
DROP TABLE IF EXISTS `directive`;
CREATE TABLE `directive` (
  `directive_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `directive` enum('ALLOW','DENY') NOT NULL,
  `id_users_group` tinyint(4) DEFAULT NULL,
  `id_functionality` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`directive_id`),
  KEY `fk_users_group2` (`id_users_group`),
  KEY `fk_functionality1` (`id_functionality`),
  CONSTRAINT `fk_functionality1` FOREIGN KEY (`id_functionality`) REFERENCES `functionality` (`functionality_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_users_group2` FOREIGN KEY (`id_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of directive
-- ----------------------------

-- ----------------------------
-- Table structure for `document`
-- ----------------------------
DROP TABLE IF EXISTS `document`;
CREATE TABLE `document` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(200) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `file_type` varchar(10) DEFAULT NULL,
  `size_bytes` bigint(20) DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_opportunity` int(11) DEFAULT NULL,
  PRIMARY KEY (`document_id`),
  KEY `fk_opportunity1` (`fk_opportunity`),
  CONSTRAINT `fk_opportunity1` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of document
-- ----------------------------

-- ----------------------------
-- Table structure for `functionality`
-- ----------------------------
DROP TABLE IF EXISTS `functionality`;
CREATE TABLE `functionality` (
  `functionality_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `func_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`functionality_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of functionality
-- ----------------------------

-- ----------------------------
-- Table structure for `lead_origin`
-- ----------------------------
DROP TABLE IF EXISTS `lead_origin`;
CREATE TABLE `lead_origin` (
  `lead_origin_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `origin` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`lead_origin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lead_origin
-- ----------------------------
INSERT INTO `lead_origin` VALUES ('1', 'QR', '2025-06-28 12:29:29', '2025-06-28 12:29:29');
INSERT INTO `lead_origin` VALUES ('2', 'Site', '2025-06-28 12:29:35', '2025-06-28 12:29:35');
INSERT INTO `lead_origin` VALUES ('3', 'Manual', '2025-06-28 12:29:39', '2025-06-28 12:29:39');
INSERT INTO `lead_origin` VALUES ('4', 'Outro', '2025-06-28 12:29:44', '2025-06-28 12:29:44');

-- ----------------------------
-- Table structure for `opportunity`
-- ----------------------------
DROP TABLE IF EXISTS `opportunity`;
CREATE TABLE `opportunity` (
  `opportunity_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`opportunity_id`,`priority`),
  KEY `fk_person1` (`fk_person`),
  KEY `fk_vendor1` (`fk_vendor`),
  KEY `fk_lead_origin1` (`lead_origin_id`),
  KEY `fk_op_status1` (`fk_op_status_id`),
  CONSTRAINT `fk_lead_origin1` FOREIGN KEY (`lead_origin_id`) REFERENCES `lead_origin` (`lead_origin_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_op_status1` FOREIGN KEY (`fk_op_status_id`) REFERENCES `opportunity_status` (`opportunity_status_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_person1` FOREIGN KEY (`fk_person`) REFERENCES `person` (`person_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_vendor1` FOREIGN KEY (`fk_vendor`) REFERENCES `vendor` (`vendor_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of opportunity
-- ----------------------------

-- ----------------------------
-- Table structure for `opportunity_status`
-- ----------------------------
DROP TABLE IF EXISTS `opportunity_status`;
CREATE TABLE `opportunity_status` (
  `opportunity_status_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL DEFAULT 'opened',
  PRIMARY KEY (`opportunity_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of opportunity_status
-- ----------------------------
INSERT INTO `opportunity_status` VALUES ('1', 'Aberto');
INSERT INTO `opportunity_status` VALUES ('2', 'Ganho');
INSERT INTO `opportunity_status` VALUES ('3', 'Perdido');
INSERT INTO `opportunity_status` VALUES ('4', 'Cancelado');

-- ----------------------------
-- Table structure for `person`
-- ----------------------------
DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `person_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`person_id`),
  KEY `fk_company1` (`fk_company`),
  KEY `fk_person_role1` (`fk_person_role`),
  CONSTRAINT `fk_company1` FOREIGN KEY (`fk_company`) REFERENCES `company` (`company_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_person_role1` FOREIGN KEY (`fk_person_role`) REFERENCES `person_role` (`person_role_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of person
-- ----------------------------

-- ----------------------------
-- Table structure for `person_role`
-- ----------------------------
DROP TABLE IF EXISTS `person_role`;
CREATE TABLE `person_role` (
  `person_role_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(15) NOT NULL,
  PRIMARY KEY (`person_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of person_role
-- ----------------------------
INSERT INTO `person_role` VALUES ('1', 'Lead');
INSERT INTO `person_role` VALUES ('2', 'Cliente');
INSERT INTO `person_role` VALUES ('3', 'Contato');

-- ----------------------------
-- Table structure for `product_service`
-- ----------------------------
DROP TABLE IF EXISTS `product_service`;
CREATE TABLE `product_service` (
  `product_service_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of product_service
-- ----------------------------

-- ----------------------------
-- Table structure for `prod_serv_opp`
-- ----------------------------
DROP TABLE IF EXISTS `prod_serv_opp`;
CREATE TABLE `prod_serv_opp` (
  `oportunity_id` int(11) NOT NULL,
  `product_service_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`oportunity_id`,`product_service_id`),
  UNIQUE KEY `idx_prodserv_oppt1` (`oportunity_id`,`product_service_id`) USING BTREE,
  KEY `fk_prod_serv1` (`product_service_id`),
  CONSTRAINT `fk:_opportunity5` FOREIGN KEY (`oportunity_id`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_prod_serv1` FOREIGN KEY (`product_service_id`) REFERENCES `product_service` (`product_service_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of prod_serv_opp
-- ----------------------------

-- ----------------------------
-- Table structure for `stage`
-- ----------------------------
DROP TABLE IF EXISTS `stage`;
CREATE TABLE `stage` (
  `stage_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `stage_order` tinyint(4) DEFAULT '0',
  `active` tinyint(4) DEFAULT '1',
  `color_hex` varchar(7) DEFAULT '#007bff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`stage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of stage
-- ----------------------------

-- ----------------------------
-- Table structure for `stage_history`
-- ----------------------------
DROP TABLE IF EXISTS `stage_history`;
CREATE TABLE `stage_history` (
  `won_lost` enum('won','lost') DEFAULT NULL,
  `stage_hist_date` date NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fk_opportunity` int(11) NOT NULL DEFAULT '0',
  `fk_stage` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_opportunity`,`fk_stage`),
  UNIQUE KEY `idx_stage_hist1` (`fk_opportunity`,`fk_stage`) USING BTREE,
  KEY `fk_stage1` (`fk_stage`),
  CONSTRAINT `fk_oportunity4` FOREIGN KEY (`fk_opportunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_stage1` FOREIGN KEY (`fk_stage`) REFERENCES `stage` (`stage_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of stage_history
-- ----------------------------

-- ----------------------------
-- Table structure for `system_config`
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(100) NOT NULL,
  `config_value` text,
  `description` text,
  `type` enum('string','number','boolean','json') DEFAULT 'string',
  `category` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `config_key` (`config_key`),
  KEY `idx_key` (`config_key`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of system_config
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `fk_users_group` tinyint(4) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_username_unique` (`username`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE,
  KEY `fk_users_group` (`fk_users_group`),
  CONSTRAINT `fk_users_group` FOREIGN KEY (`fk_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------

-- ----------------------------
-- Table structure for `users_group`
-- ----------------------------
DROP TABLE IF EXISTS `users_group`;
CREATE TABLE `users_group` (
  `user_group_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of users_group
-- ----------------------------

-- ----------------------------
-- Table structure for `vendor`
-- ----------------------------
DROP TABLE IF EXISTS `vendor`;
CREATE TABLE `vendor` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vendor_id`),
  KEY `fk_user1` (`fk_user`),
  CONSTRAINT `fk_user1` FOREIGN KEY (`fk_user`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of vendor
-- ----------------------------
