<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803182304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beneficiaire (id INT AUTO_INCREMENT NOT NULL, libelle_prof_id INT DEFAULT NULL, civilite_beneficiaire VARCHAR(20) NOT NULL, nom_beneficiaire VARCHAR(255) NOT NULL, prenom_beneficiaire VARCHAR(255) NOT NULL, ddn_beneficiaire DATETIME DEFAULT NULL, mail_beneficiaire VARCHAR(255) DEFAULT NULL, telephone_beneficiaire VARCHAR(255) DEFAULT NULL, profession_beneficiaire VARCHAR(255) DEFAULT NULL, INDEX IDX_B140D80211870684 (libelle_prof_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permanence (id INT AUTO_INCREMENT NOT NULL, site_id INT NOT NULL, jour VARCHAR(255) NOT NULL, horaire_debut DATETIME NOT NULL, horaire_fin DATETIME NOT NULL, INDEX IDX_DF30CBB6F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, nom_site VARCHAR(255) NOT NULL, intitule_site VARCHAR(255) NOT NULL, adresse1_site VARCHAR(255) DEFAULT NULL, adresse2_site VARCHAR(255) DEFAULT NULL, cp_site INT NOT NULL, ville_site VARCHAR(255) NOT NULL, carte_site VARCHAR(255) NOT NULL, is_actif TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_prof (id INT AUTO_INCREMENT NOT NULL, libelle_prof VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_revenu (id INT AUTO_INCREMENT NOT NULL, libelle_revenu VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE beneficiaire ADD CONSTRAINT FK_B140D80211870684 FOREIGN KEY (libelle_prof_id) REFERENCES type_prof (id)');
        $this->addSql('ALTER TABLE permanence ADD CONSTRAINT FK_DF30CBB6F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire DROP FOREIGN KEY FK_B140D80211870684');
        $this->addSql('ALTER TABLE permanence DROP FOREIGN KEY FK_DF30CBB6F6BD1646');
        $this->addSql('DROP TABLE beneficiaire');
        $this->addSql('DROP TABLE permanence');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE type_prof');
        $this->addSql('DROP TABLE type_revenu');
    }
}
