<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110161535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597419E9BA4');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597419E9BA4 FOREIGN KEY (work_log_id) REFERENCES work_log (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597419E9BA4');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597419E9BA4 FOREIGN KEY (work_log_id) REFERENCES work_log (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
