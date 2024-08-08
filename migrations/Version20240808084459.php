<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808084459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historique_avct (id INT AUTO_INCREMENT NOT NULL, demande_id INT NOT NULL, avancement_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', commentaires_avct LONGTEXT DEFAULT NULL, INDEX IDX_6F43F13B80E95E18 (demande_id), INDEX IDX_6F43F13B5DF05EC1 (avancement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historique_avct ADD CONSTRAINT FK_6F43F13B80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
        $this->addSql('ALTER TABLE historique_avct ADD CONSTRAINT FK_6F43F13B5DF05EC1 FOREIGN KEY (avancement_id) REFERENCES avancement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_avct DROP FOREIGN KEY FK_6F43F13B80E95E18');
        $this->addSql('ALTER TABLE historique_avct DROP FOREIGN KEY FK_6F43F13B5DF05EC1');
        $this->addSql('DROP TABLE historique_avct');
    }
}
