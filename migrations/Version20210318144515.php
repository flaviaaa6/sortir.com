<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318144515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant ADD pseudo VARCHAR(50) NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3A2C806AC');
        $this->addSql('DROP INDEX IDX_43C3D9C3A2C806AC ON ville');
        $this->addSql('ALTER TABLE ville DROP lieux_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP pseudo, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE ville ADD lieux_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3A2C806AC FOREIGN KEY (lieux_id) REFERENCES lieu (id)');
        $this->addSql('CREATE INDEX IDX_43C3D9C3A2C806AC ON ville (lieux_id)');
    }
}
