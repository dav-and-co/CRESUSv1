<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803194518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE revenu (id INT AUTO_INCREMENT NOT NULL, type_renevu_id INT NOT NULL, beneficiaire_id INT NOT NULL, montant_mensuel INT NOT NULL, INDEX IDX_7DA3C0452452E080 (type_renevu_id), INDEX IDX_7DA3C0455AF81F68 (beneficiaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_charge (id INT AUTO_INCREMENT NOT NULL, libelle_charge VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_dette (id INT AUTO_INCREMENT NOT NULL, libelle_dette VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE revenu ADD CONSTRAINT FK_7DA3C0452452E080 FOREIGN KEY (type_renevu_id) REFERENCES type_revenu (id)');
        $this->addSql('ALTER TABLE revenu ADD CONSTRAINT FK_7DA3C0455AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revenu DROP FOREIGN KEY FK_7DA3C0452452E080');
        $this->addSql('ALTER TABLE revenu DROP FOREIGN KEY FK_7DA3C0455AF81F68');
        $this->addSql('DROP TABLE revenu');
        $this->addSql('DROP TABLE type_charge');
        $this->addSql('DROP TABLE type_dette');
    }
}
