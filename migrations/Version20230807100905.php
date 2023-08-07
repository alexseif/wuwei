<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230807100905 extends AbstractMigration
{

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
          'CREATE TABLE daily (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE item ADD daily_id INT DEFAULT NULL');
        $this->addSql(
          'ALTER TABLE item ADD CONSTRAINT FK_1F1B251EE04C0193 FOREIGN KEY (daily_id) REFERENCES daily (id)'
        );
        $this->addSql('CREATE INDEX IDX_1F1B251EE04C0193 ON item (daily_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EE04C0193');
        $this->addSql('DROP TABLE daily');
        $this->addSql('DROP INDEX IDX_1F1B251EE04C0193 ON item');
        $this->addSql('ALTER TABLE item DROP daily_id');
    }

}
