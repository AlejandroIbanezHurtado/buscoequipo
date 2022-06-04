<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220604175724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo_jugador DROP FOREIGN KEY FK_1F53F93A23BFBED');
        $this->addSql('ALTER TABLE equipo_jugador DROP FOREIGN KEY FK_1F53F93AB8A54D43');
        $this->addSql('ALTER TABLE equipo_jugador ADD CONSTRAINT FK_1F53F93A23BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipo_jugador ADD CONSTRAINT FK_1F53F93AB8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo_jugador DROP FOREIGN KEY FK_1F53F93A23BFBED');
        $this->addSql('ALTER TABLE equipo_jugador DROP FOREIGN KEY FK_1F53F93AB8A54D43');
        $this->addSql('ALTER TABLE equipo_jugador ADD CONSTRAINT FK_1F53F93A23BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE equipo_jugador ADD CONSTRAINT FK_1F53F93AB8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id)');
    }
}
