<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406140809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usb_meets ADD team_a_id INT DEFAULT NULL, ADD team_b_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DBEA3FA723 FOREIGN KEY (team_a_id) REFERENCES usb_teams (id)');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DBF88A08CD FOREIGN KEY (team_b_id) REFERENCES usb_teams (id)');
        $this->addSql('CREATE INDEX IDX_3E0AC2DBEA3FA723 ON usb_meets (team_a_id)');
        $this->addSql('CREATE INDEX IDX_3E0AC2DBF88A08CD ON usb_meets (team_b_id)');
        $this->addSql('ALTER TABLE usb_teams ADD CONSTRAINT FK_CFF336EC12469DE2 FOREIGN KEY (category_id) REFERENCES usb_groups (id)');
        $this->addSql('CREATE INDEX IDX_CFF336EC12469DE2 ON usb_teams (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DBEA3FA723');
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DBF88A08CD');
        $this->addSql('DROP INDEX IDX_3E0AC2DBEA3FA723 ON usb_meets');
        $this->addSql('DROP INDEX IDX_3E0AC2DBF88A08CD ON usb_meets');
        $this->addSql('ALTER TABLE usb_meets DROP team_a_id, DROP team_b_id');
        $this->addSql('ALTER TABLE usb_teams DROP FOREIGN KEY FK_CFF336EC12469DE2');
        $this->addSql('DROP INDEX IDX_CFF336EC12469DE2 ON usb_teams');
    }
}
