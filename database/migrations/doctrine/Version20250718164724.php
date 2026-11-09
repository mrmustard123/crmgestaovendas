<?php

declare(strict_types=1);

namespace Database\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718164724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (activity_id INT UNSIGNED AUTO_INCREMENT NOT NULL, fk_opportunity INT UNSIGNED DEFAULT NULL, titulo VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, activity_date DATE NOT NULL, duration_min SMALLINT DEFAULT NULL, status VARCHAR(20) DEFAULT \'scheduled\' NOT NULL, result VARCHAR(10) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AC74095AEA7F6FE3 (fk_opportunity), PRIMARY KEY(activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (company_id INT UNSIGNED AUTO_INCREMENT NOT NULL, social_reason VARCHAR(200) NOT NULL, fantasy_name VARCHAR(200) DEFAULT NULL, cnpj VARCHAR(18) DEFAULT NULL, inscricao_estadual VARCHAR(20) DEFAULT NULL, inscricao_municipal VARCHAR(20) DEFAULT NULL, cep VARCHAR(9) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, complement VARCHAR(100) DEFAULT NULL, neighborhood VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, state VARCHAR(2) DEFAULT NULL, country VARCHAR(50) DEFAULT \'Brasil\' NOT NULL, main_phone VARCHAR(20) DEFAULT NULL, main_email VARCHAR(100) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, company_size VARCHAR(10) DEFAULT NULL, status VARCHAR(10) DEFAULT NULL, comments VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(company_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE directive (directive_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, id_users_group SMALLINT UNSIGNED DEFAULT NULL, id_functionality SMALLINT UNSIGNED DEFAULT NULL, directive VARCHAR(5) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3298A12392A52D7B (id_users_group), INDEX IDX_3298A12346148E21 (id_functionality), PRIMARY KEY(directive_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (document_id INT UNSIGNED AUTO_INCREMENT NOT NULL, fk_opportunity INT UNSIGNED NOT NULL, file_name VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, mime_type VARCHAR(100) DEFAULT NULL, file_size BIGINT DEFAULT NULL, description LONGTEXT DEFAULT NULL, uploaded_at DATE DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D8698A76EA7F6FE3 (fk_opportunity), PRIMARY KEY(document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE functionality (functionality_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, func_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(functionality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lead_origin (lead_origin_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, origin VARCHAR(15) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(lead_origin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity (opportunity_id INT UNSIGNED AUTO_INCREMENT NOT NULL, lead_origin_id SMALLINT UNSIGNED DEFAULT NULL, fk_op_status_id SMALLINT UNSIGNED DEFAULT NULL, fk_vendor INT UNSIGNED DEFAULT NULL, fk_person INT UNSIGNED DEFAULT NULL, opportunity_name VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, estimated_sale NUMERIC(12, 2) DEFAULT \'0.00\' NOT NULL, expected_closing_date DATE DEFAULT NULL, currency VARCHAR(3) DEFAULT NULL, open_date DATE DEFAULT NULL, priority VARCHAR(10) DEFAULT \'Low\' NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8389C3D7CF0171F4 (lead_origin_id), INDEX IDX_8389C3D73B0DAC2A (fk_op_status_id), INDEX IDX_8389C3D7D58C5B66 (fk_vendor), INDEX IDX_8389C3D71472B9E6 (fk_person), PRIMARY KEY(opportunity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_status (opportunity_status_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, status VARCHAR(15) DEFAULT \'Opened\' NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(opportunity_status_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (person_id INT UNSIGNED AUTO_INCREMENT NOT NULL, fk_person_role SMALLINT UNSIGNED DEFAULT NULL, fk_company INT UNSIGNED DEFAULT NULL, person_name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, complement VARCHAR(100) DEFAULT NULL, main_phone VARCHAR(20) DEFAULT NULL, main_email VARCHAR(255) DEFAULT NULL, rg VARCHAR(20) DEFAULT NULL, cpf VARCHAR(14) DEFAULT NULL, cep VARCHAR(9) DEFAULT NULL, neighborhood VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, state VARCHAR(2) DEFAULT NULL, country VARCHAR(50) DEFAULT \'Brasil\' NOT NULL, birthdate DATE DEFAULT NULL, sex VARCHAR(10) DEFAULT NULL, marital_status VARCHAR(15) DEFAULT NULL, company_dept VARCHAR(100) DEFAULT NULL, job_position VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_34DCD176B479A275 (fk_person_role), INDEX IDX_34DCD176BF903463 (fk_company), PRIMARY KEY(person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_role (person_role_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, role_name VARCHAR(15) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(person_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prod_serv_opp (opportunity_id INT UNSIGNED NOT NULL, product_service_id INT UNSIGNED NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AFCCBA899A34590F (opportunity_id), INDEX IDX_AFCCBA897E3BF6CD (product_service_id), PRIMARY KEY(opportunity_id, product_service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_service (product_service_id INT UNSIGNED AUTO_INCREMENT NOT NULL, product_name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(7) DEFAULT NULL, category VARCHAR(100) DEFAULT NULL, unit_price NUMERIC(15, 2) DEFAULT NULL, unit VARCHAR(50) DEFAULT NULL, tax_rate NUMERIC(15, 2) DEFAULT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, sku VARCHAR(12) DEFAULT NULL, is_tangible TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(product_service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage (stage_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, stage_name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, stage_order SMALLINT DEFAULT 0 NOT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, color_hex VARCHAR(7) DEFAULT \'#007bff\' NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(stage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stage_history (stage_hist_id INT UNSIGNED AUTO_INCREMENT NOT NULL, fk_opportunity INT UNSIGNED DEFAULT NULL, fk_stage SMALLINT UNSIGNED DEFAULT NULL, won_lost VARCHAR(4) DEFAULT NULL, stage_hist_date DATETIME NOT NULL, comments VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AD433006EA7F6FE3 (fk_opportunity), INDEX fk_stage4 (fk_stage), UNIQUE INDEX stage_history_unique (fk_opportunity, fk_stage), PRIMARY KEY(stage_hist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE system_config (config_id INT UNSIGNED AUTO_INCREMENT NOT NULL, config_key VARCHAR(100) NOT NULL, config_value LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(10) DEFAULT \'string\' NOT NULL, category VARCHAR(50) DEFAULT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_C4049ABD95D1CAA6 (config_key), PRIMARY KEY(config_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (user_id INT UNSIGNED AUTO_INCREMENT NOT NULL, fk_users_group SMALLINT UNSIGNED DEFAULT NULL, username VARCHAR(191) NOT NULL, password VARCHAR(191) NOT NULL, email VARCHAR(191) DEFAULT NULL, email_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', remember_token VARCHAR(100) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9E3414CB8 (fk_users_group), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_group (user_group_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, group_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(user_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vendor (vendor_id INT UNSIGNED AUTO_INCREMENT NOT NULL, fk_user INT UNSIGNED DEFAULT NULL, vendor_name VARCHAR(200) NOT NULL, main_phone VARCHAR(20) DEFAULT NULL, main_email VARCHAR(255) DEFAULT NULL, address VARCHAR(100) DEFAULT NULL, complement VARCHAR(255) DEFAULT NULL, neighborhood VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, state VARCHAR(2) DEFAULT NULL, country VARCHAR(50) DEFAULT \'Brasil\' NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F52233F61AD0877 (fk_user), PRIMARY KEY(vendor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AEA7F6FE3 FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE directive ADD CONSTRAINT FK_3298A12392A52D7B FOREIGN KEY (id_users_group) REFERENCES users_group (user_group_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE directive ADD CONSTRAINT FK_3298A12346148E21 FOREIGN KEY (id_functionality) REFERENCES functionality (functionality_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76EA7F6FE3 FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id)');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7CF0171F4 FOREIGN KEY (lead_origin_id) REFERENCES lead_origin (lead_origin_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D73B0DAC2A FOREIGN KEY (fk_op_status_id) REFERENCES opportunity_status (opportunity_status_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7D58C5B66 FOREIGN KEY (fk_vendor) REFERENCES vendor (vendor_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D71472B9E6 FOREIGN KEY (fk_person) REFERENCES person (person_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176B479A275 FOREIGN KEY (fk_person_role) REFERENCES person_role (person_role_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176BF903463 FOREIGN KEY (fk_company) REFERENCES company (company_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE prod_serv_opp ADD CONSTRAINT FK_AFCCBA899A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (opportunity_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prod_serv_opp ADD CONSTRAINT FK_AFCCBA897E3BF6CD FOREIGN KEY (product_service_id) REFERENCES product_service (product_service_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stage_history ADD CONSTRAINT FK_AD433006EA7F6FE3 FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE stage_history ADD CONSTRAINT FK_AD433006391B01C FOREIGN KEY (fk_stage) REFERENCES stage (stage_id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9E3414CB8 FOREIGN KEY (fk_users_group) REFERENCES users_group (user_group_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE vendor ADD CONSTRAINT FK_F52233F61AD0877 FOREIGN KEY (fk_user) REFERENCES users (user_id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AEA7F6FE3');
        $this->addSql('ALTER TABLE directive DROP FOREIGN KEY FK_3298A12392A52D7B');
        $this->addSql('ALTER TABLE directive DROP FOREIGN KEY FK_3298A12346148E21');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76EA7F6FE3');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7CF0171F4');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D73B0DAC2A');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7D58C5B66');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D71472B9E6');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176B479A275');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176BF903463');
        $this->addSql('ALTER TABLE prod_serv_opp DROP FOREIGN KEY FK_AFCCBA899A34590F');
        $this->addSql('ALTER TABLE prod_serv_opp DROP FOREIGN KEY FK_AFCCBA897E3BF6CD');
        $this->addSql('ALTER TABLE stage_history DROP FOREIGN KEY FK_AD433006EA7F6FE3');
        $this->addSql('ALTER TABLE stage_history DROP FOREIGN KEY FK_AD433006391B01C');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9E3414CB8');
        $this->addSql('ALTER TABLE vendor DROP FOREIGN KEY FK_F52233F61AD0877');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE directive');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE functionality');
        $this->addSql('DROP TABLE lead_origin');
        $this->addSql('DROP TABLE opportunity');
        $this->addSql('DROP TABLE opportunity_status');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_role');
        $this->addSql('DROP TABLE prod_serv_opp');
        $this->addSql('DROP TABLE product_service');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE stage_history');
        $this->addSql('DROP TABLE system_config');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_group');
        $this->addSql('DROP TABLE vendor');
    }
}
