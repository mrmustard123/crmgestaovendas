-- DDL para la base de datos del CRM de Gestión de Ventas
-- Compatible con MySQL 5.6.43

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS `crm360gemini` CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Usar la base de datos
USE `crm360gemini`;

-- Tabla para almacenar los usuarios del sistema (vendedores/personal interno)
CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Tabla para gestionar las Cuentas (Pessoa Jurídica)
CREATE TABLE IF NOT EXISTS `accounts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `corporate_name` VARCHAR(255) NOT NULL,
    `cnpj` VARCHAR(18) UNIQUE NULL,
    `address` VARCHAR(255) NULL,
    `city` VARCHAR(100) NULL,
    `state` VARCHAR(100) NULL,
    `zip_code` VARCHAR(20) NULL,
    `sector` VARCHAR(100) NULL,
    `phone` VARCHAR(50) NULL,
    `email` VARCHAR(255) NULL,
    `website` VARCHAR(255) NULL,
    `created_by_user_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Tabla para gestionar los Contactos (Pessoa Física)
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `cpf` VARCHAR(14) UNIQUE NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `position` VARCHAR(100) NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `created_by_user_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Tabla para definir los estados (etapas) del pipeline de ventas
CREATE TABLE IF NOT EXISTS `opportunity_stages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `order_index` INT NOT NULL UNIQUE,
    `is_final` BOOLEAN DEFAULT FALSE,
    `is_won` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Datos iniciales para `opportunity_stages`
INSERT INTO `opportunity_stages` (`name`, `order_index`, `is_final`, `is_won`) VALUES
('Qualificação', 1, FALSE, FALSE),
('Apresentação', 2, FALSE, FALSE),
('Proposta', 3, FALSE, FALSE),
('Negociação', 4, FALSE, FALSE),
('Ganho', 5, TRUE, TRUE),
('Perdido', 6, TRUE, FALSE);

-- Tabla para gestionar las Oportunidades de Venta
CREATE TABLE IF NOT EXISTS `opportunities` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `estimated_value` DECIMAL(15, 2) NOT NULL,
    `expected_close_date` DATE NULL,
    `actual_close_date` DATE NULL,
    `probability` INT DEFAULT 0,
    `stage_id` INT UNSIGNED NOT NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `assigned_to_user_id` BIGINT UNSIGNED NULL,
    `created_by_user_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`stage_id`) REFERENCES `opportunity_stages`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Tabla para registrar los leads (clientes potenciales)
CREATE TABLE IF NOT EXISTS `leads` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `source` ENUM('QR Code', 'Site', 'Manual') NOT NULL,
    `source_detail` VARCHAR(255) NULL,
    `status` ENUM('Novo', 'Qualificado', 'Descartado', 'Convertido') DEFAULT 'Novo' NOT NULL,
    `converted_to_contact_id` BIGINT UNSIGNED NULL,
    `created_by_user_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`converted_to_contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Tabla para registrar las actividades de ventas (llamadas, reuniones, emails)
CREATE TABLE IF NOT EXISTS `activities` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `type` ENUM('Ligação', 'Reunião', 'Email', 'Outro') NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `activity_date` DATETIME NOT NULL,
    `duration_minutes` INT NULL,
    `status` ENUM('Agendada', 'Concluída', 'Cancelada') DEFAULT 'Agendada' NOT NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `opportunity_id` BIGINT UNSIGNED NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Tabla para la gestión de documentos (propuestas, contratos)
CREATE TABLE IF NOT EXISTS `documents` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL UNIQUE,
    `file_type` VARCHAR(50) NULL,
    `file_size` BIGINT NULL,
    `contact_id` BIGINT UNSIGNED NULL,
    `account_id` BIGINT UNSIGNED NULL,
    `opportunity_id` BIGINT UNSIGNED NULL ,
    `uploaded_by_user_id` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`uploaded_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Índices para mejorar el rendimiento de las consultas
CREATE INDEX idx_accounts_cnpj ON `accounts` (`cnpj`);
CREATE INDEX idx_accounts_corporate_name ON `accounts` (`corporate_name`);
CREATE INDEX idx_contacts_cpf ON `contacts` (`cpf`);
CREATE INDEX idx_contacts_name ON `contacts` (`name`);
CREATE INDEX idx_opportunities_stage_id ON `opportunities` (`stage_id`);
CREATE INDEX idx_opportunities_assigned_to_user_id ON `opportunities` (`assigned_to_user_id`);
CREATE INDEX idx_leads_source ON `leads` (`source`);
CREATE INDEX idx_activities_type ON `activities` (`type`);
CREATE INDEX idx_activities_activity_date ON `activities` (`activity_date`);
CREATE INDEX idx_documents_file_path ON `documents` (`file_path`);