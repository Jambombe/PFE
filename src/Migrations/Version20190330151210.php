<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190330151210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quest ADD owner_id INT DEFAULT NULL, ADD child_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F8177E3C61F9 FOREIGN KEY (owner_id) REFERENCES parent_user (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F817DD62C21B FOREIGN KEY (child_id) REFERENCES child_user (id)');
        $this->addSql('CREATE INDEX IDX_4317F8177E3C61F9 ON quest (owner_id)');
        $this->addSql('CREATE INDEX IDX_4317F817DD62C21B ON quest (child_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F8177E3C61F9');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F817DD62C21B');
        $this->addSql('DROP INDEX IDX_4317F8177E3C61F9 ON quest');
        $this->addSql('DROP INDEX IDX_4317F817DD62C21B ON quest');
        $this->addSql('ALTER TABLE quest DROP owner_id, DROP child_id');
    }
}
