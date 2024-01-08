<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108145031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goal_tag (goal_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_323E7D11667D1AFE (goal_id), INDEX IDX_323E7D11BAD26311 (tag_id), PRIMARY KEY(goal_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE goal_tag ADD CONSTRAINT FK_323E7D11667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE goal_tag ADD CONSTRAINT FK_323E7D11BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goal_tag DROP FOREIGN KEY FK_323E7D11667D1AFE');
        $this->addSql('ALTER TABLE goal_tag DROP FOREIGN KEY FK_323E7D11BAD26311');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE goal_tag');
    }
}
