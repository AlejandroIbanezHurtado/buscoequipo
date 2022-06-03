<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220603184141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_equipo DROP FOREIGN KEY FK_EB4ED1B0820E47CA');
        $this->addSql('ALTER TABLE partido_equipo CHANGE id_equipo_id id_equipo_id INT NOT NULL');
        $this->addSql('ALTER TABLE partido_equipo ADD CONSTRAINT FK_EB4ED1B0820E47CA FOREIGN KEY (id_equipo_id) REFERENCES equipo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_equipo DROP FOREIGN KEY FK_EB4ED1B0820E47CA');
        $this->addSql('ALTER TABLE partido_equipo CHANGE id_equipo_id id_equipo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partido_equipo ADD CONSTRAINT FK_EB4ED1B0820E47CA FOREIGN KEY (id_equipo_id) REFERENCES equipo (id)');
    }
}
