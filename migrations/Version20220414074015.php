<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414074015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usb_fields (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_groups (id INT AUTO_INCREMENT NOT NULL, phase_en_cours_id INT DEFAULT NULL, name VARCHAR(10) NOT NULL, INDEX IDX_3C387FF18F5AAE64 (phase_en_cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_phases_categories (category_id INT NOT NULL, phase_id INT NOT NULL, INDEX IDX_C8F2D3DF12469DE2 (category_id), INDEX IDX_C8F2D3DF99091188 (phase_id), PRIMARY KEY(category_id, phase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_meets (id INT AUTO_INCREMENT NOT NULL, team_a_id INT DEFAULT NULL, team_b_id INT DEFAULT NULL, field_id INT DEFAULT NULL, phase_id INT NOT NULL, team_forfait_id INT DEFAULT NULL, score_a INT DEFAULT NULL, score_b INT DEFAULT NULL, penalty_a INT DEFAULT NULL, penalty_b INT DEFAULT NULL, poule VARCHAR(20) DEFAULT NULL, tour INT DEFAULT NULL, principal TINYINT(1) DEFAULT NULL, INDEX IDX_3E0AC2DBEA3FA723 (team_a_id), INDEX IDX_3E0AC2DBF88A08CD (team_b_id), INDEX IDX_3E0AC2DB443707B0 (field_id), INDEX IDX_3E0AC2DB99091188 (phase_id), INDEX IDX_3E0AC2DBAEB42DE9 (team_forfait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_phases (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, phase_precente_id INT DEFAULT NULL, name VARCHAR(20) NOT NULL, INDEX IDX_DB5C2F64C54C8C93 (type_id), INDEX IDX_DB5C2F64E125BD32 (phase_precente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_poules (id INT AUTO_INCREMENT NOT NULL, phase_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, principal TINYINT(1) DEFAULT NULL, INDEX IDX_A17DD7F199091188 (phase_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_poules_teams (poule_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_B80EE82726596FD8 (poule_id), INDEX IDX_B80EE827296CD8AE (team_id), PRIMARY KEY(poule_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_teams (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_CFF336EC12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_type_phase (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, team_by_poule INT DEFAULT NULL, format VARCHAR(30) DEFAULT NULL, detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usb_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4DB2B15DF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usb_groups ADD CONSTRAINT FK_3C387FF18F5AAE64 FOREIGN KEY (phase_en_cours_id) REFERENCES usb_phases (id)');
        $this->addSql('ALTER TABLE usb_phases_categories ADD CONSTRAINT FK_C8F2D3DF12469DE2 FOREIGN KEY (category_id) REFERENCES usb_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usb_phases_categories ADD CONSTRAINT FK_C8F2D3DF99091188 FOREIGN KEY (phase_id) REFERENCES usb_phases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DBEA3FA723 FOREIGN KEY (team_a_id) REFERENCES usb_teams (id)');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DBF88A08CD FOREIGN KEY (team_b_id) REFERENCES usb_teams (id)');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DB443707B0 FOREIGN KEY (field_id) REFERENCES usb_fields (id)');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DB99091188 FOREIGN KEY (phase_id) REFERENCES usb_phases (id)');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DBAEB42DE9 FOREIGN KEY (team_forfait_id) REFERENCES usb_teams (id)');
        $this->addSql('ALTER TABLE usb_phases ADD CONSTRAINT FK_DB5C2F64C54C8C93 FOREIGN KEY (type_id) REFERENCES usb_type_phase (id)');
        $this->addSql('ALTER TABLE usb_phases ADD CONSTRAINT FK_DB5C2F64E125BD32 FOREIGN KEY (phase_precente_id) REFERENCES usb_phases (id)');
        $this->addSql('ALTER TABLE usb_poules ADD CONSTRAINT FK_A17DD7F199091188 FOREIGN KEY (phase_id) REFERENCES usb_phases (id)');
        $this->addSql('ALTER TABLE usb_poules_teams ADD CONSTRAINT FK_B80EE82726596FD8 FOREIGN KEY (poule_id) REFERENCES usb_poules (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usb_poules_teams ADD CONSTRAINT FK_B80EE827296CD8AE FOREIGN KEY (team_id) REFERENCES usb_teams (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usb_teams ADD CONSTRAINT FK_CFF336EC12469DE2 FOREIGN KEY (category_id) REFERENCES usb_groups (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DB443707B0');
        $this->addSql('ALTER TABLE usb_phases_categories DROP FOREIGN KEY FK_C8F2D3DF12469DE2');
        $this->addSql('ALTER TABLE usb_teams DROP FOREIGN KEY FK_CFF336EC12469DE2');
        $this->addSql('ALTER TABLE usb_groups DROP FOREIGN KEY FK_3C387FF18F5AAE64');
        $this->addSql('ALTER TABLE usb_phases_categories DROP FOREIGN KEY FK_C8F2D3DF99091188');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DB99091188');
        $this->addSql('ALTER TABLE usb_phases DROP FOREIGN KEY FK_DB5C2F64E125BD32');
        $this->addSql('ALTER TABLE usb_poules DROP FOREIGN KEY FK_A17DD7F199091188');
        $this->addSql('ALTER TABLE usb_poules_teams DROP FOREIGN KEY FK_B80EE82726596FD8');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DBEA3FA723');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DBF88A08CD');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DBAEB42DE9');
        $this->addSql('ALTER TABLE usb_poules_teams DROP FOREIGN KEY FK_B80EE827296CD8AE');
        $this->addSql('ALTER TABLE usb_phases DROP FOREIGN KEY FK_DB5C2F64C54C8C93');
        $this->addSql('DROP TABLE usb_fields');
        $this->addSql('DROP TABLE usb_groups');
        $this->addSql('DROP TABLE usb_phases_categories');
        $this->addSql('DROP TABLE usb_meets');
        $this->addSql('DROP TABLE usb_phases');
        $this->addSql('DROP TABLE usb_poules');
        $this->addSql('DROP TABLE usb_poules_teams');
        $this->addSql('DROP TABLE usb_teams');
        $this->addSql('DROP TABLE usb_type_phase');
        $this->addSql('DROP TABLE usb_users');
    }
}
