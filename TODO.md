//TODO: make this migration for the tasklist progress view
// src/Migrations/VersionXXXXX.php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class VersionXXXXX extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create view task_list_progress to calculate task list progress';
    }

    public function up(Schema $schema): void
    {
        // This migration is auto-generated, please modify it to your needs

        // Create the view
        $this->addSql(<<<SQL
            CREATE VIEW task_list_progress AS
            SELECT
                t.taskList_id,
                COUNT(t.id) AS totalTasks,
                SUM(CASE WHEN t.completed = 1 THEN 1 ELSE 0 END) AS completedTasks,
                (SUM(CASE WHEN t.completed = 1 THEN 1 ELSE 0 END) / COUNT(t.id)) * 100 AS progress
            FROM
                tasks t
            GROUP BY
                t.taskList_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // This migration is auto-generated, please modify it to your needs

        // Drop the view
        $this->addSql('DROP VIEW task_list_progress');
    }
}
