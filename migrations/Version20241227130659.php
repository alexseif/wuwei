<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227130659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE VIEW task_duration_per_day AS
        SELECT 
            DATE(completed_at) AS completedDate, 
            SUM(duration) AS durationSum
        FROM 
            tasks
        WHERE 
            completed_at IS NOT NULL
        GROUP BY 
            completedDate;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW task_duration_per_day;');
    }
}
