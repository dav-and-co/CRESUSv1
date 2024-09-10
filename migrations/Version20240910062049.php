<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240910062049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avancement ADD is_actif TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE origine ADD is_actif TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD is_annule TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_charge ADD is_actif TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_dette ADD is_actif TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_prof ADD is_actif TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_revenu ADD is_actif TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avancement DROP is_actif');
        $this->addSql('ALTER TABLE origine DROP is_actif');
        $this->addSql('ALTER TABLE rendez_vous DROP is_annule');
        $this->addSql('ALTER TABLE type_charge DROP is_actif');
        $this->addSql('ALTER TABLE type_dette DROP is_actif');
        $this->addSql('ALTER TABLE type_prof DROP is_actif');
        $this->addSql('ALTER TABLE type_revenu DROP is_actif');
    }
}
