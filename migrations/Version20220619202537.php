<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619202537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_equipo DROP FOREIGN KEY FK_EB4ED1B0B40FEE63');
        $this->addSql('ALTER TABLE partido_equipo ADD CONSTRAINT FK_EB4ED1B0B40FEE63 FOREIGN KEY (id_partido_id) REFERENCES partido (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_equipo DROP FOREIGN KEY FK_EB4ED1B0B40FEE63');
        $this->addSql('ALTER TABLE partido_equipo ADD CONSTRAINT FK_EB4ED1B0B40FEE63 FOREIGN KEY (id_partido_id) REFERENCES partido (id)');
    }
}
