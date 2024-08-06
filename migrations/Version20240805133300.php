<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805133300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revenu DROP FOREIGN KEY FK_7DA3C0452452E080');
        $this->addSql('DROP INDEX IDX_7DA3C0452452E080 ON revenu');
        $this->addSql('ALTER TABLE revenu CHANGE type_renevu_id type_revenu_id INT NOT NULL');
        $this->addSql('ALTER TABLE revenu ADD CONSTRAINT FK_7DA3C04520F3EE6A FOREIGN KEY (type_revenu_id) REFERENCES type_revenu (id)');
        $this->addSql('CREATE INDEX IDX_7DA3C04520F3EE6A ON revenu (type_revenu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revenu DROP FOREIGN KEY FK_7DA3C04520F3EE6A');
        $this->addSql('DROP INDEX IDX_7DA3C04520F3EE6A ON revenu');
        $this->addSql('ALTER TABLE revenu CHANGE type_revenu_id type_renevu_id INT NOT NULL');
        $this->addSql('ALTER TABLE revenu ADD CONSTRAINT FK_7DA3C0452452E080 FOREIGN KEY (type_renevu_id) REFERENCES type_revenu (id)');
        $this->addSql('CREATE INDEX IDX_7DA3C0452452E080 ON revenu (type_renevu_id)');
    }
}
