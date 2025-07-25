<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250718021552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE password_reset_tokens');
        $this->addSql('DROP TABLE personal_access_tokens');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY activity_fk_opportunity_foreign');
        $this->addSql('ALTER TABLE activity CHANGE description description LONGTEXT DEFAULT NULL, CHANGE duration_min duration_min SMALLINT DEFAULT NULL, CHANGE status status VARCHAR(20) DEFAULT \'scheduled\' NOT NULL, CHANGE result result VARCHAR(10) DEFAULT NULL, CHANGE comments comments LONGTEXT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AEA7F6FE3 FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE activity RENAME INDEX activity_fk_opportunity_foreign TO IDX_AC74095AEA7F6FE3');
        $this->addSql('ALTER TABLE company CHANGE company_size company_size VARCHAR(10) DEFAULT NULL, CHANGE status status VARCHAR(10) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE directive DROP FOREIGN KEY directive_id_functionality_foreign');
        $this->addSql('ALTER TABLE directive DROP FOREIGN KEY directive_id_users_group_foreign');
        $this->addSql('ALTER TABLE directive CHANGE directive_id directive_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE id_users_group id_users_group SMALLINT UNSIGNED DEFAULT NULL, CHANGE id_functionality id_functionality SMALLINT UNSIGNED DEFAULT NULL, CHANGE directive directive VARCHAR(5) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE directive ADD CONSTRAINT FK_3298A12392A52D7B FOREIGN KEY (id_users_group) REFERENCES users_group (user_group_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE directive ADD CONSTRAINT FK_3298A12346148E21 FOREIGN KEY (id_functionality) REFERENCES functionality (functionality_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE directive RENAME INDEX directive_id_users_group_foreign TO IDX_3298A12392A52D7B');
        $this->addSql('ALTER TABLE directive RENAME INDEX directive_id_functionality_foreign TO IDX_3298A12346148E21');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY document_fk_opportunity_foreign');
        $this->addSql('ALTER TABLE document CHANGE fk_opportunity fk_opportunity INT UNSIGNED NOT NULL, CHANGE file_size file_size BIGINT DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE uploaded_at uploaded_at DATE DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76EA7F6FE3 FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id)');
        $this->addSql('ALTER TABLE document RENAME INDEX document_fk_opportunity_foreign TO IDX_D8698A76EA7F6FE3');
        $this->addSql('ALTER TABLE functionality CHANGE functionality_id functionality_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE lead_origin CHANGE lead_origin_id lead_origin_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY opportunity_fk_op_status_id_foreign');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY opportunity_fk_person_foreign');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY opportunity_fk_vendor_foreign');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY opportunity_lead_origin_id_foreign');
        $this->addSql('ALTER TABLE opportunity CHANGE lead_origin_id lead_origin_id SMALLINT UNSIGNED DEFAULT NULL, CHANGE fk_op_status_id fk_op_status_id SMALLINT UNSIGNED DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE priority priority VARCHAR(10) DEFAULT \'Low\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7CF0171F4 FOREIGN KEY (lead_origin_id) REFERENCES lead_origin (lead_origin_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D73B0DAC2A FOREIGN KEY (fk_op_status_id) REFERENCES opportunity_status (opportunity_status_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7D58C5B66 FOREIGN KEY (fk_vendor) REFERENCES vendor (vendor_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D71472B9E6 FOREIGN KEY (fk_person) REFERENCES person (person_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX opportunity_lead_origin_id_foreign TO IDX_8389C3D7CF0171F4');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX opportunity_fk_op_status_id_foreign TO IDX_8389C3D73B0DAC2A');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX opportunity_fk_vendor_foreign TO IDX_8389C3D7D58C5B66');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX opportunity_fk_person_foreign TO IDX_8389C3D71472B9E6');
        $this->addSql('ALTER TABLE opportunity_status CHANGE opportunity_status_id opportunity_status_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY person_fk_company_foreign');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY person_fk_person_role_foreign');
        $this->addSql('ALTER TABLE person CHANGE fk_person_role fk_person_role SMALLINT UNSIGNED DEFAULT NULL, CHANGE sex sex VARCHAR(10) DEFAULT NULL, CHANGE marital_status marital_status VARCHAR(15) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176B479A275 FOREIGN KEY (fk_person_role) REFERENCES person_role (person_role_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176BF903463 FOREIGN KEY (fk_company) REFERENCES company (company_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE person RENAME INDEX person_fk_person_role_foreign TO IDX_34DCD176B479A275');
        $this->addSql('ALTER TABLE person RENAME INDEX person_fk_company_foreign TO IDX_34DCD176BF903463');
        $this->addSql('ALTER TABLE person_role CHANGE person_role_id person_role_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prod_serv_opp DROP FOREIGN KEY prod_serv_opp_opportunity_id_foreign');
        $this->addSql('ALTER TABLE prod_serv_opp DROP FOREIGN KEY prod_serv_opp_product_service_id_foreign');
        $this->addSql('ALTER TABLE prod_serv_opp CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prod_serv_opp ADD CONSTRAINT FK_AFCCBA899A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (opportunity_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prod_serv_opp ADD CONSTRAINT FK_AFCCBA897E3BF6CD FOREIGN KEY (product_service_id) REFERENCES product_service (product_service_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prod_serv_opp RENAME INDEX prod_serv_opp_product_service_id_foreign TO IDX_AFCCBA897E3BF6CD');
        $this->addSql('ALTER TABLE product_service CHANGE description description LONGTEXT DEFAULT NULL, CHANGE type type VARCHAR(7) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE stage CHANGE stage_id stage_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE stage_order stage_order SMALLINT DEFAULT 0 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE stage_history DROP FOREIGN KEY fk_opportunity4');
        $this->addSql('ALTER TABLE stage_history DROP FOREIGN KEY fk_stage4');
        $this->addSql('ALTER TABLE stage_history CHANGE fk_opportunity fk_opportunity INT UNSIGNED DEFAULT NULL, CHANGE fk_stage fk_stage SMALLINT UNSIGNED DEFAULT NULL, CHANGE won_lost won_lost VARCHAR(4) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE stage_history ADD CONSTRAINT FK_AD433006EA7F6FE3 FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id) ON DELETE RESTRICT');
        $this->addSql('ALTER TABLE stage_history ADD CONSTRAINT FK_AD433006391B01C FOREIGN KEY (fk_stage) REFERENCES stage (stage_id) ON DELETE RESTRICT');
        $this->addSql('DROP INDEX idx_category ON system_config');
        $this->addSql('ALTER TABLE system_config CHANGE config_value config_value LONGTEXT DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE type type VARCHAR(10) DEFAULT \'string\' NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE system_config RENAME INDEX system_config_config_key_unique TO UNIQ_C4049ABD95D1CAA6');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY users_fk_users_group_foreign');
        $this->addSql('ALTER TABLE users CHANGE fk_users_group fk_users_group SMALLINT UNSIGNED DEFAULT NULL, CHANGE email_verified_at email_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9E3414CB8 FOREIGN KEY (fk_users_group) REFERENCES users_group (user_group_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE users RENAME INDEX users_username_unique TO UNIQ_1483A5E9F85E0677');
        $this->addSql('ALTER TABLE users RENAME INDEX users_email_unique TO UNIQ_1483A5E9E7927C74');
        $this->addSql('ALTER TABLE users RENAME INDEX users_fk_users_group_foreign TO IDX_1483A5E9E3414CB8');
        $this->addSql('ALTER TABLE users_group CHANGE user_group_id user_group_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vendor DROP FOREIGN KEY vendor_fk_user_foreign');
        $this->addSql('ALTER TABLE vendor CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vendor ADD CONSTRAINT FK_F52233F61AD0877 FOREIGN KEY (fk_user) REFERENCES users (user_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE vendor RENAME INDEX vendor_fk_user_foreign TO IDX_F52233F61AD0877');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE password_reset_tokens (email VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, token VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME DEFAULT NULL, PRIMARY KEY(email)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE personal_access_tokens (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, tokenable_type VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, tokenable_id BIGINT UNSIGNED NOT NULL, name VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, token VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, abilities TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_0900_ai_ci`, last_used_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX personal_access_tokens_token_unique (token), INDEX personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AEA7F6FE3');
        $this->addSql('ALTER TABLE activity CHANGE description description TEXT DEFAULT NULL, CHANGE duration_min duration_min TINYINT(1) DEFAULT NULL, CHANGE status status VARCHAR(0) DEFAULT \'scheduled\' NOT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE result result VARCHAR(0) DEFAULT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE comments comments TEXT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT activity_fk_opportunity_foreign FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE activity RENAME INDEX idx_ac74095aea7f6fe3 TO activity_fk_opportunity_foreign');
        $this->addSql('ALTER TABLE company CHANGE company_size company_size VARCHAR(0) DEFAULT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE status status VARCHAR(0) DEFAULT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE directive DROP FOREIGN KEY FK_3298A12392A52D7B');
        $this->addSql('ALTER TABLE directive DROP FOREIGN KEY FK_3298A12346148E21');
        $this->addSql('ALTER TABLE directive CHANGE directive_id directive_id TINYINT(1) NOT NULL, CHANGE id_users_group id_users_group TINYINT(1) DEFAULT NULL, CHANGE id_functionality id_functionality TINYINT(1) DEFAULT NULL, CHANGE directive directive VARCHAR(0) NOT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE directive ADD CONSTRAINT directive_id_functionality_foreign FOREIGN KEY (id_functionality) REFERENCES functionality (functionality_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE directive ADD CONSTRAINT directive_id_users_group_foreign FOREIGN KEY (id_users_group) REFERENCES users_group (user_group_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE directive RENAME INDEX idx_3298a12346148e21 TO directive_id_functionality_foreign');
        $this->addSql('ALTER TABLE directive RENAME INDEX idx_3298a12392a52d7b TO directive_id_users_group_foreign');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76EA7F6FE3');
        $this->addSql('ALTER TABLE document CHANGE fk_opportunity fk_opportunity INT UNSIGNED DEFAULT NULL, CHANGE file_size file_size BIGINT UNSIGNED DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE uploaded_at uploaded_at DATETIME DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT document_fk_opportunity_foreign FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE document RENAME INDEX idx_d8698a76ea7f6fe3 TO document_fk_opportunity_foreign');
        $this->addSql('ALTER TABLE functionality CHANGE functionality_id functionality_id TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE lead_origin CHANGE lead_origin_id lead_origin_id TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7CF0171F4');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D73B0DAC2A');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7D58C5B66');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D71472B9E6');
        $this->addSql('ALTER TABLE opportunity CHANGE lead_origin_id lead_origin_id TINYINT(1) DEFAULT NULL, CHANGE fk_op_status_id fk_op_status_id TINYINT(1) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE priority priority VARCHAR(0) DEFAULT \'Low\' NOT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT opportunity_fk_op_status_id_foreign FOREIGN KEY (fk_op_status_id) REFERENCES opportunity_status (opportunity_status_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT opportunity_fk_person_foreign FOREIGN KEY (fk_person) REFERENCES person (person_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT opportunity_fk_vendor_foreign FOREIGN KEY (fk_vendor) REFERENCES vendor (vendor_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT opportunity_lead_origin_id_foreign FOREIGN KEY (lead_origin_id) REFERENCES lead_origin (lead_origin_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX idx_8389c3d7cf0171f4 TO opportunity_lead_origin_id_foreign');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX idx_8389c3d73b0dac2a TO opportunity_fk_op_status_id_foreign');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX idx_8389c3d71472b9e6 TO opportunity_fk_person_foreign');
        $this->addSql('ALTER TABLE opportunity RENAME INDEX idx_8389c3d7d58c5b66 TO opportunity_fk_vendor_foreign');
        $this->addSql('ALTER TABLE opportunity_status CHANGE opportunity_status_id opportunity_status_id TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176B479A275');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176BF903463');
        $this->addSql('ALTER TABLE person CHANGE fk_person_role fk_person_role TINYINT(1) DEFAULT NULL, CHANGE sex sex VARCHAR(0) DEFAULT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE marital_status marital_status VARCHAR(0) DEFAULT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT person_fk_company_foreign FOREIGN KEY (fk_company) REFERENCES company (company_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT person_fk_person_role_foreign FOREIGN KEY (fk_person_role) REFERENCES person_role (person_role_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE person RENAME INDEX idx_34dcd176bf903463 TO person_fk_company_foreign');
        $this->addSql('ALTER TABLE person RENAME INDEX idx_34dcd176b479a275 TO person_fk_person_role_foreign');
        $this->addSql('ALTER TABLE person_role CHANGE person_role_id person_role_id TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE prod_serv_opp DROP FOREIGN KEY FK_AFCCBA899A34590F');
        $this->addSql('ALTER TABLE prod_serv_opp DROP FOREIGN KEY FK_AFCCBA897E3BF6CD');
        $this->addSql('ALTER TABLE prod_serv_opp CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE prod_serv_opp ADD CONSTRAINT prod_serv_opp_opportunity_id_foreign FOREIGN KEY (opportunity_id) REFERENCES opportunity (opportunity_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prod_serv_opp ADD CONSTRAINT prod_serv_opp_product_service_id_foreign FOREIGN KEY (product_service_id) REFERENCES product_service (product_service_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prod_serv_opp RENAME INDEX idx_afccba897e3bf6cd TO prod_serv_opp_product_service_id_foreign');
        $this->addSql('ALTER TABLE product_service CHANGE description description TEXT DEFAULT NULL, CHANGE type type VARCHAR(0) DEFAULT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE stage CHANGE stage_id stage_id TINYINT(1) NOT NULL, CHANGE stage_order stage_order TINYINT(1) DEFAULT 0 NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE stage_history DROP FOREIGN KEY FK_AD433006EA7F6FE3');
        $this->addSql('ALTER TABLE stage_history DROP FOREIGN KEY FK_AD433006391B01C');
        $this->addSql('ALTER TABLE stage_history CHANGE fk_opportunity fk_opportunity INT UNSIGNED NOT NULL, CHANGE fk_stage fk_stage TINYINT(1) NOT NULL, CHANGE won_lost won_lost VARCHAR(0) DEFAULT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE stage_history ADD CONSTRAINT fk_opportunity4 FOREIGN KEY (fk_opportunity) REFERENCES opportunity (opportunity_id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE stage_history ADD CONSTRAINT fk_stage4 FOREIGN KEY (fk_stage) REFERENCES stage (stage_id) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE system_config CHANGE config_value config_value TEXT DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE type type VARCHAR(0) DEFAULT \'string\' NOT NULL COMMENT \'(DC2Type:my_enum)\', CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('CREATE INDEX idx_category ON system_config (category)');
        $this->addSql('ALTER TABLE system_config RENAME INDEX uniq_c4049abd95d1caa6 TO system_config_config_key_unique');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9E3414CB8');
        $this->addSql('ALTER TABLE users CHANGE fk_users_group fk_users_group TINYINT(1) DEFAULT NULL, CHANGE email_verified_at email_verified_at DATETIME DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT users_fk_users_group_foreign FOREIGN KEY (fk_users_group) REFERENCES users_group (user_group_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_1483a5e9f85e0677 TO users_username_unique');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_1483a5e9e7927c74 TO users_email_unique');
        $this->addSql('ALTER TABLE users RENAME INDEX idx_1483a5e9e3414cb8 TO users_fk_users_group_foreign');
        $this->addSql('ALTER TABLE users_group CHANGE user_group_id user_group_id TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE vendor DROP FOREIGN KEY FK_F52233F61AD0877');
        $this->addSql('ALTER TABLE vendor CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE vendor ADD CONSTRAINT vendor_fk_user_foreign FOREIGN KEY (fk_user) REFERENCES users (user_id) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE vendor RENAME INDEX idx_f52233f61ad0877 TO vendor_fk_user_foreign');
    }
}
