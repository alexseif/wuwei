<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250719195323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE account_service_assignment (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, product_service_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, renewal_date DATE DEFAULT NULL, rrule VARCHAR(255) DEFAULT NULL, is_complete TINYINT(1) NOT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8117E4549B6B5FBA (account_id), INDEX IDX_8117E4547E3BF6CD (product_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, billing_cycle VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_service_assignment ADD CONSTRAINT FK_8117E4549B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_service_assignment ADD CONSTRAINT FK_8117E4547E3BF6CD FOREIGN KEY (product_service_id) REFERENCES product_service (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE account_service_assignment DROP FOREIGN KEY FK_8117E4549B6B5FBA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE account_service_assignment DROP FOREIGN KEY FK_8117E4547E3BF6CD
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE account_service_assignment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_service
        SQL);
    }
}
