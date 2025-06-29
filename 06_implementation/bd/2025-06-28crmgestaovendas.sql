/*
Navicat MySQL Data Transfer

Source Server         : crmgestaovendas
Source Server Version : 50643
Source Host           : localhost:3306
Source Database       : crmgestaovendas

Target Server Type    : MYSQL
Target Server Version : 50643
File Encoding         : 65001

Date: 2025-06-28 10:12:27
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
-- Table structure for `opportunity`
-- ----------------------------
DROP TABLE IF EXISTS `opportunity`;
CREATE TABLE `opportunity` (
  `opportunity_id` int(11) NOT NULL AUTO_INCREMENT,
  `opportunity_name` varchar(200) NOT NULL,
  `description` text,
  `estimated_sale` decimal(12,0) DEFAULT '0',
  `expected_closing_date` date DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `open_date` date DEFAULT NULL,
  `lead_origin` enum('qr_code','site','manual','indicacao','evento','outro') DEFAULT NULL,
  `priority` enum('Low','Medium','High','Critical') DEFAULT NULL,
  `status` enum('opened','won','lost','canceled') DEFAULT NULL,
  `fk_vendor` int(11) DEFAULT NULL,
  `fk_person` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`opportunity_id`),
  KEY `fk_person1` (`fk_person`),
  KEY `fk_vendor1` (`fk_vendor`),
  CONSTRAINT `fk_person1` FOREIGN KEY (`fk_person`) REFERENCES `person` (`person_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_vendor1` FOREIGN KEY (`fk_vendor`) REFERENCES `vendor` (`vendor_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of opportunity
-- ----------------------------

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
  `role` enum('Lead','Client','Contact') DEFAULT NULL,
  `fk_company` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`person_id`),
  KEY `fk_company1` (`fk_company`),
  CONSTRAINT `fk_company1` FOREIGN KEY (`fk_company`) REFERENCES `company` (`company_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of person
-- ----------------------------

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
  `order` tinyint(4) DEFAULT '0',
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
  `fk_opporntunity` int(11) NOT NULL DEFAULT '0',
  `fk_stage` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_opporntunity`,`fk_stage`),
  UNIQUE KEY `idx_stage_hist1` (`fk_opporntunity`,`fk_stage`) USING BTREE,
  KEY `fk_stage1` (`fk_stage`),
  CONSTRAINT `fk_oportunity4` FOREIGN KEY (`fk_opporntunity`) REFERENCES `opportunity` (`opportunity_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_stage1` FOREIGN KEY (`fk_stage`) REFERENCES `stage` (`stage_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of stage_history
-- ----------------------------

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fk_users_group` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `fk_users_group` (`fk_users_group`),
  CONSTRAINT `fk_users_group` FOREIGN KEY (`fk_users_group`) REFERENCES `users_group` (`user_group_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of user
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
  CONSTRAINT `fk_user1` FOREIGN KEY (`fk_user`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of vendor
-- ----------------------------
