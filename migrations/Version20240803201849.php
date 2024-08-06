<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803201849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avancement (id INT AUTO_INCREMENT NOT NULL, type_demande_id INT NOT NULL, libelle_avancement VARCHAR(255) NOT NULL, INDEX IDX_6D2A7A2A9DEA883D (type_demande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dette (id INT AUTO_INCREMENT NOT NULL, titulaire_principal_id INT NOT NULL, type_dette_id INT NOT NULL, organisme VARCHAR(255) NOT NULL, montant_initial INT DEFAULT NULL, mensualite INT DEFAULT NULL, montant_du INT NOT NULL, INDEX IDX_831BC8085E4CD77B (titulaire_principal_id), INDEX IDX_831BC8082CEB275D (type_dette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE origine (id INT AUTO_INCREMENT NOT NULL, libelle_origine VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position_demande (id INT AUTO_INCREMENT NOT NULL, libelle_position VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avancement ADD CONSTRAINT FK_6D2A7A2A9DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demande (id)');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC8085E4CD77B FOREIGN KEY (titulaire_principal_id) REFERENCES beneficiaire (id)');
        $this->addSql('ALTER TABLE dette ADD CONSTRAINT FK_831BC8082CEB275D FOREIGN KEY (type_dette_id) REFERENCES type_dette (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avancement DROP FOREIGN KEY FK_6D2A7A2A9DEA883D');
        $this->addSql('ALTER TABLE dette DROP FOREIGN KEY FK_831BC8085E4CD77B');
        $this->addSql('ALTER TABLE dette DROP FOREIGN KEY FK_831BC8082CEB275D');
        $this->addSql('DROP TABLE avancement');
        $this->addSql('DROP TABLE dette');
        $this->addSql('DROP TABLE origine');
        $this->addSql('DROP TABLE position_demande');
    }
}
