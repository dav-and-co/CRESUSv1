<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803203843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, type_demande_id INT NOT NULL, position_demande_id INT NOT NULL, origine_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', adresse1_demande VARCHAR(255) DEFAULT NULL, adresse2_demande VARCHAR(255) DEFAULT NULL, cp_demande INT DEFAULT NULL, ville_demande VARCHAR(255) DEFAULT NULL, situation_logt VARCHAR(255) DEFAULT NULL, nb_enfant INT DEFAULT NULL, patrimoine VARCHAR(255) DEFAULT NULL, complement_origine VARCHAR(255) DEFAULT NULL, cause_besoin VARCHAR(255) DEFAULT NULL, commentaires LONGTEXT DEFAULT NULL, INDEX IDX_2694D7A59DEA883D (type_demande_id), INDEX IDX_2694D7A5AE087298 (position_demande_id), INDEX IDX_2694D7A587998E (origine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A59DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demande (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5AE087298 FOREIGN KEY (position_demande_id) REFERENCES position_demande (id)');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A587998E FOREIGN KEY (origine_id) REFERENCES origine (id)');
        $this->addSql('ALTER TABLE charge ADD demande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA43480E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
        $this->addSql('CREATE INDEX IDX_556BA43480E95E18 ON charge (demande_id)');
        $this->addSql('ALTER TABLE dette ADD demande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC80880E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
        $this->addSql('CREATE INDEX IDX_831BC80880E95E18 ON dette (demande_id)');
        $this->addSql('ALTER TABLE revenu ADD demande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE revenu ADD CONSTRAINT FK_7DA3C04580E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id)');
        $this->addSql('CREATE INDEX IDX_7DA3C04580E95E18 ON revenu (demande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA43480E95E18');
        $this->addSql('ALTER TABLE dette DROP FOREIGN KEY FK_831BC80880E95E18');
        $this->addSql('ALTER TABLE revenu DROP FOREIGN KEY FK_7DA3C04580E95E18');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A59DEA883D');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5AE087298');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A587998E');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP INDEX IDX_556BA43480E95E18 ON charge');
        $this->addSql('ALTER TABLE charge DROP demande_id');
        $this->addSql('DROP INDEX IDX_831BC80880E95E18 ON dette');
        $this->addSql('ALTER TABLE dette DROP demande_id');
        $this->addSql('DROP INDEX IDX_7DA3C04580E95E18 ON revenu');
        $this->addSql('ALTER TABLE revenu DROP demande_id');
    }
}
