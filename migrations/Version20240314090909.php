<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314090909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_tag (tag_source INT NOT NULL, tag_target INT NOT NULL, INDEX IDX_2572D81B6CB365F (tag_source), INDEX IDX_2572D81AF2E66D0 (tag_target), PRIMARY KEY(tag_source, tag_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_tag ADD CONSTRAINT FK_2572D81B6CB365F FOREIGN KEY (tag_source) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_tag ADD CONSTRAINT FK_2572D81AF2E66D0 FOREIGN KEY (tag_target) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_rate DROP FOREIGN KEY FK_E70FC5B19EB6921');
        $this->addSql('DROP TABLE client_rate');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_rate (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, rate INT NOT NULL, unit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, no_of_hours INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_E70FC5B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE client_rate ADD CONSTRAINT FK_E70FC5B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tag_tag DROP FOREIGN KEY FK_2572D81B6CB365F');
        $this->addSql('ALTER TABLE tag_tag DROP FOREIGN KEY FK_2572D81AF2E66D0');
        $this->addSql('DROP TABLE tag_tag');
    }
}
