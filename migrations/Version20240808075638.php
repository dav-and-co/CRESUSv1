<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808075638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beneficiaire_demande (beneficiaire_id INT NOT NULL, demande_id INT NOT NULL, INDEX IDX_AA2A574F5AF81F68 (beneficiaire_id), INDEX IDX_AA2A574F80E95E18 (demande_id), PRIMARY KEY(beneficiaire_id, demande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE beneficiaire_demande ADD CONSTRAINT FK_AA2A574F5AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE beneficiaire_demande ADD CONSTRAINT FK_AA2A574F80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire_demande DROP FOREIGN KEY FK_AA2A574F5AF81F68');
        $this->addSql('ALTER TABLE beneficiaire_demande DROP FOREIGN KEY FK_AA2A574F80E95E18');
        $this->addSql('DROP TABLE beneficiaire_demande');
    }
}
