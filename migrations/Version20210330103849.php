<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330103849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa DROP sector');
        $this->addSql('ALTER TABLE empresa ADD CONSTRAINT FK_B8D75A503A909126 FOREIGN KEY (nombre) REFERENCES sector (id)');
        $this->addSql('CREATE INDEX IDX_B8D75A503A909126 ON empresa (nombre)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa DROP FOREIGN KEY FK_B8D75A503A909126');
        $this->addSql('DROP INDEX IDX_B8D75A503A909126 ON empresa');
        $this->addSql('ALTER TABLE empresa ADD sector INT NOT NULL');
    }
}
