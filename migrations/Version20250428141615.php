<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428141615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE timelog (id INT AUTO_INCREMENT NOT NULL, start DATETIME NOT NULL, duration VARCHAR(255) DEFAULT NULL COMMENT '(DC2Type:dateinterval)', log LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE timelog_tag (timelog_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_CCA9362D252D5066 (timelog_id), INDEX IDX_CCA9362DBAD26311 (tag_id), PRIMARY KEY(timelog_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timelog_tag ADD CONSTRAINT FK_CCA9362D252D5066 FOREIGN KEY (timelog_id) REFERENCES timelog (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timelog_tag ADD CONSTRAINT FK_CCA9362DBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE timelog_tag DROP FOREIGN KEY FK_CCA9362D252D5066
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE timelog_tag DROP FOREIGN KEY FK_CCA9362DBAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE timelog
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE timelog_tag
        SQL);
    }
}
