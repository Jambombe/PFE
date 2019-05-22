<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190522072811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_reward ADD reward_owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_reward ADD CONSTRAINT FK_7A7CA657CDB0AC2A FOREIGN KEY (reward_owner_id) REFERENCES parent_user (id)');
        $this->addSql('CREATE INDEX IDX_7A7CA657CDB0AC2A ON custom_reward (reward_owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_reward DROP FOREIGN KEY FK_7A7CA657CDB0AC2A');
        $this->addSql('DROP INDEX IDX_7A7CA657CDB0AC2A ON custom_reward');
        $this->addSql('ALTER TABLE custom_reward DROP reward_owner_id');
    }
}
