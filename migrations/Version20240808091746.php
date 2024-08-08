<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808091746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_demande (user_id INT NOT NULL, demande_id INT NOT NULL, INDEX IDX_7E99C9AFA76ED395 (user_id), INDEX IDX_7E99C9AF80E95E18 (demande_id), PRIMARY KEY(user_id, demande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_demande ADD CONSTRAINT FK_7E99C9AFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_demande ADD CONSTRAINT FK_7E99C9AF80E95E18 FOREIGN KEY (demande_id) REFERENCES demande (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_demande DROP FOREIGN KEY FK_7E99C9AFA76ED395');
        $this->addSql('ALTER TABLE user_demande DROP FOREIGN KEY FK_7E99C9AF80E95E18');
        $this->addSql('DROP TABLE user_demande');
    }
}
