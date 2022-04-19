<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419073145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_phase DROP FOREIGN KEY FK_598E998299091188');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DB99091188');
        $this->addSql('CREATE TABLE usb_phases_categories (category_id INT NOT NULL, phase_id INT NOT NULL, INDEX IDX_C8F2D3DF12469DE2 (category_id), INDEX IDX_C8F2D3DF99091188 (phase_id), PRIMARY KEY(category_id, phase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_phases (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, phase_precente_id INT DEFAULT NULL, name VARCHAR(20) NOT NULL, INDEX IDX_DB5C2F64C54C8C93 (type_id), INDEX IDX_DB5C2F64E125BD32 (phase_precente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_poules (id INT AUTO_INCREMENT NOT NULL, phase_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, principal TINYINT(1) DEFAULT NULL, INDEX IDX_A17DD7F199091188 (phase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_poules_teams (poule_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_B80EE82726596FD8 (poule_id), INDEX IDX_B80EE827296CD8AE (team_id), PRIMARY KEY(poule_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_type_phase (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, team_by_poule INT DEFAULT NULL, format VARCHAR(30) DEFAULT NULL, detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4DB2B15DF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usb_phases_categories ADD CONSTRAINT FK_C8F2D3DF12469DE2 FOREIGN KEY (category_id) REFERENCES usb_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usb_phases_categories ADD CONSTRAINT FK_C8F2D3DF99091188 FOREIGN KEY (phase_id) REFERENCES usb_phases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usb_phases ADD CONSTRAINT FK_DB5C2F64C54C8C93 FOREIGN KEY (type_id) REFERENCES usb_type_phase (id)');
        $this->addSql('ALTER TABLE usb_phases ADD CONSTRAINT FK_DB5C2F64E125BD32 FOREIGN KEY (phase_precente_id) REFERENCES usb_phases (id)');
        $this->addSql('ALTER TABLE usb_poules ADD CONSTRAINT FK_A17DD7F199091188 FOREIGN KEY (phase_id) REFERENCES usb_phases (id)');
        $this->addSql('ALTER TABLE usb_poules_teams ADD CONSTRAINT FK_B80EE82726596FD8 FOREIGN KEY (poule_id) REFERENCES usb_poules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usb_poules_teams ADD CONSTRAINT FK_B80EE827296CD8AE FOREIGN KEY (team_id) REFERENCES usb_teams (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE category_phase');
        $this->addSql('DROP TABLE phase');
        $this->addSql('ALTER TABLE usb_groups ADD phase_en_cours_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usb_groups ADD CONSTRAINT FK_3C387FF18F5AAE64 FOREIGN KEY (phase_en_cours_id) REFERENCES usb_phases (id)');
        $this->addSql('CREATE INDEX IDX_3C387FF18F5AAE64 ON usb_groups (phase_en_cours_id)');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DB99091188');
        $this->addSql('ALTER TABLE usb_meets ADD principal TINYINT(1) DEFAULT NULL, CHANGE poule poule VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DB99091188 FOREIGN KEY (phase_id) REFERENCES usb_phases (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usb_groups DROP FOREIGN KEY FK_3C387FF18F5AAE64');
        $this->addSql('ALTER TABLE usb_phases_categories DROP FOREIGN KEY FK_C8F2D3DF99091188');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DB99091188');
        $this->addSql('ALTER TABLE usb_phases DROP FOREIGN KEY FK_DB5C2F64E125BD32');
        $this->addSql('ALTER TABLE usb_poules DROP FOREIGN KEY FK_A17DD7F199091188');
        $this->addSql('ALTER TABLE usb_poules_teams DROP FOREIGN KEY FK_B80EE82726596FD8');
        $this->addSql('ALTER TABLE usb_phases DROP FOREIGN KEY FK_DB5C2F64C54C8C93');
        $this->addSql('CREATE TABLE category_phase (category_id INT NOT NULL, phase_id INT NOT NULL, INDEX IDX_598E998299091188 (phase_id), INDEX IDX_598E998212469DE2 (category_id), PRIMARY KEY(category_id, phase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE phase (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE category_phase ADD CONSTRAINT FK_598E998299091188 FOREIGN KEY (phase_id) REFERENCES phase (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_phase ADD CONSTRAINT FK_598E998212469DE2 FOREIGN KEY (category_id) REFERENCES usb_groups (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE usb_phases_categories');
        $this->addSql('DROP TABLE usb_phases');
        $this->addSql('DROP TABLE usb_poules');
        $this->addSql('DROP TABLE usb_poules_teams');
        $this->addSql('DROP TABLE usb_type_phase');
        $this->addSql('DROP TABLE usb_users');
        $this->addSql('DROP INDEX IDX_3C387FF18F5AAE64 ON usb_groups');
        $this->addSql('ALTER TABLE usb_groups DROP phase_en_cours_id');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DB99091188');
        $this->addSql('ALTER TABLE usb_meets DROP principal, CHANGE poule poule VARCHAR(2) DEFAULT NULL');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DB99091188 FOREIGN KEY (phase_id) REFERENCES phase (id)');
    }
}
