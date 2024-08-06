<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240803201025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE charge (id INT AUTO_INCREMENT NOT NULL, type_charge_id INT NOT NULL, beneficiaire_id INT NOT NULL, montant_mensuel INT NOT NULL, INDEX IDX_556BA434E1EE0804 (type_charge_id), INDEX IDX_556BA4345AF81F68 (beneficiaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA434E1EE0804 FOREIGN KEY (type_charge_id) REFERENCES type_charge (id)');
        $this->addSql('ALTER TABLE charge ADD CONSTRAINT FK_556BA4345AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA434E1EE0804');
        $this->addSql('ALTER TABLE charge DROP FOREIGN KEY FK_556BA4345AF81F68');
        $this->addSql('DROP TABLE charge');
    }
}
