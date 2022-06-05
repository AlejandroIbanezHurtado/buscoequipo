<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605123508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo CHANGE nombre nombre VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE jugador CHANGE nombre nombre VARCHAR(30) NOT NULL, CHANGE apellidos apellidos VARCHAR(70) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo CHANGE nombre nombre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE jugador CHANGE nombre nombre VARCHAR(255) NOT NULL, CHANGE apellidos apellidos VARCHAR(255) NOT NULL');
    }
}
