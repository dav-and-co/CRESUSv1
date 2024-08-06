<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803175658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formulaire (id INT AUTO_INCREMENT NOT NULL, nom_demandeur VARCHAR(255) NOT NULL, prenom_demandeur VARCHAR(255) NOT NULL, mail_demandeur VARCHAR(255) DEFAULT NULL, telephone_demandeur VARCHAR(255) DEFAULT NULL, permanence_demandeur VARCHAR(255) NOT NULL, besoin_demandeur VARCHAR(255) NOT NULL, description_besoin LONGTEXT DEFAULT NULL, is_traite TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_demande (id INT AUTO_INCREMENT NOT NULL, libelle_demande VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE formulaire');
        $this->addSql('DROP TABLE type_demande');
    }
}
