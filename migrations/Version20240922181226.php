<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922181226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permanance_site (id INT AUTO_INCREMENT NOT NULL, id_site_id INT DEFAULT NULL, date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', heure_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_end DATETIME NOT NULL, benevole1 VARCHAR(255) DEFAULT NULL, benevole2 VARCHAR(255) DEFAULT NULL, benevole3 VARCHAR(255) DEFAULT NULL, benevole4 VARCHAR(255) DEFAULT NULL, INDEX IDX_758A80002820BF36 (id_site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permanance_site ADD CONSTRAINT FK_758A80002820BF36 FOREIGN KEY (id_site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AA76ED395');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AF6BD1646');
        $this->addSql('DROP INDEX IDX_65E8AA0AA76ED395 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0AF6BD1646 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD id_site_id INT DEFAULT NULL, ADD statut VARCHAR(255) DEFAULT NULL, ADD heure_end TIME DEFAULT NULL, ADD commentaires VARCHAR(255) DEFAULT NULL, DROP user_id, DROP site_id, DROP date_at, DROP is_annule');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A2820BF36 FOREIGN KEY (id_site_id) REFERENCES permanance_site (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A2820BF36 ON rendez_vous (id_site_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A2820BF36');
        $this->addSql('ALTER TABLE permanance_site DROP FOREIGN KEY FK_758A80002820BF36');
        $this->addSql('DROP TABLE permanance_site');
        $this->addSql('DROP INDEX IDX_65E8AA0A2820BF36 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD user_id INT NOT NULL, ADD site_id INT NOT NULL, ADD date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD is_annule TINYINT(1) DEFAULT NULL, DROP id_site_id, DROP statut, DROP heure_end, DROP commentaires');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AF6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AA76ED395 ON rendez_vous (user_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AF6BD1646 ON rendez_vous (site_id)');
    }
}
