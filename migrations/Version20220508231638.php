<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220508231638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alerta (id INT AUTO_INCREMENT NOT NULL, equipo_id INT NOT NULL, mensaje VARCHAR(255) DEFAULT NULL, INDEX IDX_4C3B12323BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipo (id INT AUTO_INCREMENT NOT NULL, capitan_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, permanente TINYINT(1) NOT NULL, escudo VARCHAR(255) DEFAULT NULL, INDEX IDX_C49C530B5624577C (capitan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, imagen VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_527D6F18E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partido (id INT AUTO_INCREMENT NOT NULL, equipo1_id INT NOT NULL, equipo2_id INT DEFAULT NULL, pista_id INT NOT NULL, fecha_ini DATETIME NOT NULL, fecha_fin DATETIME NOT NULL, INDEX IDX_4E79750B8D588AD (equipo1_id), INDEX IDX_4E79750B1A602743 (equipo2_id), INDEX IDX_4E79750B4C22F2EB (pista_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneo (id INT AUTO_INCREMENT NOT NULL, equipo_creador_id INT NOT NULL, equipos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', nombre VARCHAR(50) NOT NULL, partidos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_7CEB63FEA2F0BEAD (equipo_creador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valoracion (id INT AUTO_INCREMENT NOT NULL, jugador_id INT NOT NULL, partido_id INT NOT NULL, puntuacion INT NOT NULL, comentario VARCHAR(255) DEFAULT NULL, INDEX IDX_6D3DE0F4B8A54D43 (jugador_id), INDEX IDX_6D3DE0F411856EB4 (partido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alerta ADD CONSTRAINT FK_4C3B12323BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE equipo ADD CONSTRAINT FK_C49C530B5624577C FOREIGN KEY (capitan_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B8D588AD FOREIGN KEY (equipo1_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B1A602743 FOREIGN KEY (equipo2_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE partido ADD CONSTRAINT FK_4E79750B4C22F2EB FOREIGN KEY (pista_id) REFERENCES pista (id)');
        $this->addSql('ALTER TABLE torneo ADD CONSTRAINT FK_7CEB63FEA2F0BEAD FOREIGN KEY (equipo_creador_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F4B8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE valoracion ADD CONSTRAINT FK_6D3DE0F411856EB4 FOREIGN KEY (partido_id) REFERENCES partido (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alerta DROP FOREIGN KEY FK_4C3B12323BFBED');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B8D588AD');
        $this->addSql('ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B1A602743');
        $this->addSql('ALTER TABLE torneo DROP FOREIGN KEY FK_7CEB63FEA2F0BEAD');
        $this->addSql('ALTER TABLE equipo DROP FOREIGN KEY FK_C49C530B5624577C');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F4B8A54D43');
        $this->addSql('ALTER TABLE valoracion DROP FOREIGN KEY FK_6D3DE0F411856EB4');
        $this->addSql('DROP TABLE alerta');
        $this->addSql('DROP TABLE equipo');
        $this->addSql('DROP TABLE jugador');
        $this->addSql('DROP TABLE partido');
        $this->addSql('DROP TABLE torneo');
        $this->addSql('DROP TABLE valoracion');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pista CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE coordenadas coordenadas VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE imagen imagen VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
