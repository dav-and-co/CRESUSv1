<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240914093815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge ADD commentaires VARCHAR(255) DEFAULT NULL, ADD is_bdf TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE demande ADD garde_alternee INT DEFAULT NULL, ADD droit_visite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dette ADD commenaires VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE revenu ADD commentaires VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge DROP commentaires, DROP is_bdf');
        $this->addSql('ALTER TABLE demande DROP garde_alternee, DROP droit_visite');
        $this->addSql('ALTER TABLE dette DROP commenaires');
        $this->addSql('ALTER TABLE revenu DROP commentaires');
    }
}
