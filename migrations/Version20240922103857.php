<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922103857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande ADD site_initial_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5CE0A8961 FOREIGN KEY (site_initial_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_2694D7A5CE0A8961 ON demande (site_initial_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5CE0A8961');
        $this->addSql('DROP INDEX IDX_2694D7A5CE0A8961 ON demande');
        $this->addSql('ALTER TABLE demande DROP site_initial_id');
    }
}
