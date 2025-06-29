-- DDL para la base de datos del CRM de Gestión de Ventas
-- Compatible con MySQL 5.6.43

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS `crm360gemini` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para gestionar las Cuentas (Pessoa Jurídica)
CREATE TABLE IF NOT EXISTS `accounts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `corporate_name` VARCHAR(255) NOT NULL COMMENT 'Razão Social',
    `cnpj` VARCHAR(18) UNIQUE NULL COMMENT 'CNPJ (Cadastro Nacional da Pessoa Jurídica)',
    `address` VARCHAR(255) NULL,
    `city` VARCHAR(100) NULL,
    `state` VARCHAR(100) NULL,
    `zip_code` VARCHAR(20) NULL,
    `sector` VARCHAR(100) NULL COMMENT 'Setor de atuação',
    `phone` VARCHAR(50) NULL,
    `email` VARCHAR(255) NULL,
    `website` VARCHAR(255) NULL,
    `created_by_user_id` BIGINT UNSIGNED NULL COMMENT 'Usuario que creó la cuenta',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para gestionar los Contactos (Pessoa Física)
CREATE TABLE IF NOT EXISTS `contacts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'Nome completo do contato',
    `cpf` VARCHAR(14) UNIQUE NULL COMMENT 'CPF (Cadastro de Pessoas Físicas)',
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `position` VARCHAR(100) NULL COMMENT 'Cargo do contato na empresa',
    `account_id` BIGINT UNSIGNED NULL COMMENT 'Vínculo à Pessoa Jurídica (opcional)',
    `created_by_user_id` BIGINT UNSIGNED NULL COMMENT 'Usuario que creó el contacto',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para definir los estados (etapas) del pipeline de ventas
CREATE TABLE IF NOT EXISTS `opportunity_stages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Nome da etapa (Ex: Qualificação, Proposta, Ganho)',
    `order_index` INT NOT NULL UNIQUE COMMENT 'Ordem de exibição no pipeline',
    `is_final` BOOLEAN DEFAULT FALSE COMMENT 'Indica se é uma etapa final (Ganho/Perdido)',
    `is_won` BOOLEAN DEFAULT FALSE COMMENT 'Indica se a etapa é de "Ganho"',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
    `title` VARCHAR(255) NOT NULL COMMENT 'Título da oportunidade',
    `description` TEXT NULL,
    `estimated_value` DECIMAL(15, 2) NOT NULL COMMENT 'Valor estimado da venda',
    `expected_close_date` DATE NULL COMMENT 'Data prevista de fechamento',
    `actual_close_date` DATE NULL COMMENT 'Data real de fechamento (se ganha/perdida)',
    `probability` INT DEFAULT 0 COMMENT 'Probabilidade de fechamento em percentual (0-100)',
    `stage_id` INT UNSIGNED NOT NULL COMMENT 'Etapa atual do funil de vendas',
    `contact_id` BIGINT UNSIGNED NULL COMMENT 'Contato principal associado à oportunidade',
    `account_id` BIGINT UNSIGNED NULL COMMENT 'Conta principal associada à oportunidade',
    `assigned_to_user_id` BIGINT UNSIGNED NULL COMMENT 'Vendedor responsável pela oportunidade',
    `created_by_user_id` BIGINT UNSIGNED NULL COMMENT 'Usuario que creó la oportunidad',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`stage_id`) REFERENCES `opportunity_stages`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para registrar los leads (clientes potenciales)
CREATE TABLE IF NOT EXISTS `leads` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `source` ENUM('QR Code', 'Site', 'Manual') NOT NULL COMMENT 'Canal de geração do lead',
    `source_detail` VARCHAR(255) NULL COMMENT 'Detalles adicionales sobre el origen (ej: ID del QR, URL específica)',
    `status` ENUM('Novo', 'Qualificado', 'Descartado', 'Convertido') DEFAULT 'Novo' NOT NULL,
    `converted_to_contact_id` BIGINT UNSIGNED NULL COMMENT 'ID do contato se o lead for convertido',
    `created_by_user_id` BIGINT UNSIGNED NULL COMMENT 'Usuario que creó o processó el lead manualmente',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`converted_to_contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para registrar las actividades de ventas (llamadas, reuniones, emails)
CREATE TABLE IF NOT EXISTS `activities` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `type` ENUM('Ligação', 'Reunião', 'Email', 'Outro') NOT NULL COMMENT 'Tipo de atividade',
    `subject` VARCHAR(255) NOT NULL COMMENT 'Assunto da atividade',
    `description` TEXT NULL,
    `activity_date` DATETIME NOT NULL,
    `duration_minutes` INT NULL COMMENT 'Duração da atividade em minutos (para reuniões/ligações)',
    `status` ENUM('Agendada', 'Concluída', 'Cancelada') DEFAULT 'Agendada' NOT NULL,
    `contact_id` BIGINT UNSIGNED NULL COMMENT 'Contato associado à atividade',
    `account_id` BIGINT UNSIGNED NULL COMMENT 'Conta associada à atividade',
    `opportunity_id` BIGINT UNSIGNED NULL COMMENT 'Oportunidade associada à atividade',
    `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'Vendedor que realizou/agendou a atividade',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para la gestión de documentos (propuestas, contratos)
CREATE TABLE IF NOT EXISTS `documents` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'Nome do documento',
    `file_path` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Caminho do arquivo no servidor/storage',
    `file_type` VARCHAR(50) NULL COMMENT 'Tipo de arquivo (PDF, DOCX, JPG, etc.)',
    `file_size` BIGINT NULL COMMENT 'Tamanho do arquivo em bytes',
    `contact_id` BIGINT UNSIGNED NULL COMMENT 'Documento vinculado a um Contato',
    `account_id` BIGINT UNSIGNED NULL COMMENT 'Documento vinculado a uma Conta',
    `opportunity_id` BIGINT UNSIGNED NULL COMMENT 'Documento vinculado a uma Oportunidade',
    `uploaded_by_user_id` BIGINT UNSIGNED NULL COMMENT 'Usuario que carregou o documento',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`contact_id`) REFERENCES `contacts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`uploaded_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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