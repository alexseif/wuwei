<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526180849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE expenses (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_2496F35B38248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE expenses ADD CONSTRAINT FK_2496F35B38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35B38248176
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE expenses
        SQL);
    }
}
