<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190330150221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE child_user ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE child_user ADD CONSTRAINT FK_38A275BB727ACA70 FOREIGN KEY (parent_id) REFERENCES parent_user (id)');
        $this->addSql('CREATE INDEX IDX_38A275BB727ACA70 ON child_user (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE child_user DROP FOREIGN KEY FK_38A275BB727ACA70');
        $this->addSql('DROP INDEX IDX_38A275BB727ACA70 ON child_user');
        $this->addSql('ALTER TABLE child_user DROP parent_id');
    }
}
