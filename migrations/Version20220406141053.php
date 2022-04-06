<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406141053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usb_meets ADD field_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usb_meets ADD CONSTRAINT FK_3E0AC2DB443707B0 FOREIGN KEY (field_id) REFERENCES usb_fields (id)');
        $this->addSql('CREATE INDEX IDX_3E0AC2DB443707B0 ON usb_meets (field_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usb_meets DROP FOREIGN KEY FK_3E0AC2DB443707B0');
        $this->addSql('DROP INDEX IDX_3E0AC2DB443707B0 ON usb_meets');
        $this->addSql('ALTER TABLE usb_meets DROP field_id');
    }
}
