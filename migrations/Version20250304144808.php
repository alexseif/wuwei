<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250304144808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE milestones DROP FOREIGN KEY FK_18F78184F4792058');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B32F4792058');
        $this->addSql('ALTER TABLE payments DROP FOREIGN KEY FK_65D29B324B3E2EDA');
        $this->addSql('ALTER TABLE requirements DROP FOREIGN KEY FK_70BEA1AAF4792058');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C4C3A3BB');
        $this->addSql('DROP TABLE milestones');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE proposals');
        $this->addSql('DROP TABLE requirements');
        $this->addSql('DROP TABLE transactions');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE milestones (id INT AUTO_INCREMENT NOT NULL, proposal_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, week INT NOT NULL, month INT NOT NULL, action_item VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, remarks LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_18F78184F4792058 (proposal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, proposal_id INT DEFAULT NULL, milestone_id INT DEFAULT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, due_date DATE NOT NULL, amount NUMERIC(10, 2) NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_65D29B32F4792058 (proposal_id), INDEX IDX_65D29B324B3E2EDA (milestone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proposals (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, code VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE requirements (id INT AUTO_INCREMENT NOT NULL, proposal_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, category VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, priority VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_70BEA1AAF4792058 (proposal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, payment_id INT DEFAULT NULL, date DATE NOT NULL, amount NUMERIC(10, 2) NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, notes LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_EAA81A4C4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE milestones ADD CONSTRAINT FK_18F78184F4792058 FOREIGN KEY (proposal_id) REFERENCES proposals (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32F4792058 FOREIGN KEY (proposal_id) REFERENCES proposals (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B324B3E2EDA FOREIGN KEY (milestone_id) REFERENCES milestones (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE requirements ADD CONSTRAINT FK_70BEA1AAF4792058 FOREIGN KEY (proposal_id) REFERENCES proposals (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C4C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id) ON UPDATE NO ACTION ON DELETE SET NULL');
    }
}
