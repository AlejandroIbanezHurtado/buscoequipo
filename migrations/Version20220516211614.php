<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516211614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE torneo_equipo (id INT AUTO_INCREMENT NOT NULL, id_torneo_id INT NOT NULL, id_equipo_id INT NOT NULL, INDEX IDX_F963611220262425 (id_torneo_id), INDEX IDX_F9636112820E47CA (id_equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE torneo_partido (id INT AUTO_INCREMENT NOT NULL, id_torneo_id INT NOT NULL, id_partido_id INT NOT NULL, INDEX IDX_2A2F22F920262425 (id_torneo_id), INDEX IDX_2A2F22F9B40FEE63 (id_partido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE torneo_equipo ADD CONSTRAINT FK_F963611220262425 FOREIGN KEY (id_torneo_id) REFERENCES torneo (id)');
        $this->addSql('ALTER TABLE torneo_equipo ADD CONSTRAINT FK_F9636112820E47CA FOREIGN KEY (id_equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE torneo_partido ADD CONSTRAINT FK_2A2F22F920262425 FOREIGN KEY (id_torneo_id) REFERENCES torneo (id)');
        $this->addSql('ALTER TABLE torneo_partido ADD CONSTRAINT FK_2A2F22F9B40FEE63 FOREIGN KEY (id_partido_id) REFERENCES partido (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE torneo_equipo');
        $this->addSql('DROP TABLE torneo_partido');
        $this->addSql('ALTER TABLE alerta CHANGE mensaje mensaje VARCHAR(500) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE detalle_partido CHANGE color color VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE equipo CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE escudo escudo VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE jugador CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE apellidos apellidos VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE imagen imagen VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pista CHANGE nombre nombre VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE descripcion descripcion VARCHAR(500) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE coordenadas coordenadas VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE imagen imagen VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE torneo CHANGE nombre nombre VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE valoracion CHANGE comentario comentario VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
