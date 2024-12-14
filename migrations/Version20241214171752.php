<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214171752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks ADD work_log_id INT DEFAULT NULL, CHANGE work_loggable work_loggable TINYINT(1) NOT NULL, CHANGE completedAt completed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597419E9BA4 FOREIGN KEY (work_log_id) REFERENCES work_log (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_50586597419E9BA4 ON tasks (work_log_id)');
        $this->addSql('ALTER TABLE work_log DROP FOREIGN KEY FK_F5513F598DB60186');
        $this->addSql('ALTER TABLE work_log CHANGE task_id task_id INT NOT NULL, CHANGE pricePerUnit price_per_unit DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE work_log ADD CONSTRAINT FK_F5513F598DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597419E9BA4');
        $this->addSql('DROP INDEX UNIQ_50586597419E9BA4 ON tasks');
        $this->addSql('ALTER TABLE tasks DROP work_log_id, CHANGE work_loggable work_loggable TINYINT(1) DEFAULT 1 NOT NULL, CHANGE completed_at completedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE work_log DROP FOREIGN KEY FK_F5513F598DB60186');
        $this->addSql('ALTER TABLE work_log CHANGE task_id task_id INT DEFAULT NULL, CHANGE price_per_unit pricePerUnit DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE work_log ADD CONSTRAINT FK_F5513F598DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
