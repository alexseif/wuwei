<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231226112832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE time_system_tag (time_system_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_46AB31B542E3247D (time_system_id), INDEX IDX_46AB31B5BAD26311 (tag_id), PRIMARY KEY(time_system_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE time_system_tag ADD CONSTRAINT FK_46AB31B542E3247D FOREIGN KEY (time_system_id) REFERENCES time_system (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE time_system_tag ADD CONSTRAINT FK_46AB31B5BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_system_tag DROP FOREIGN KEY FK_46AB31B542E3247D');
        $this->addSql('ALTER TABLE time_system_tag DROP FOREIGN KEY FK_46AB31B5BAD26311');
        $this->addSql('DROP TABLE time_system_tag');
    }
}
