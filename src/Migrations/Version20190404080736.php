<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404080736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE notification ADD child_users_id INT DEFAULT NULL, ADD parent_users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAEDB4C999 FOREIGN KEY (child_users_id) REFERENCES child_user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF87E18EC FOREIGN KEY (parent_users_id) REFERENCES parent_user (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAEDB4C999 ON notification (child_users_id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAF87E18EC ON notification (parent_users_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAEDB4C999');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF87E18EC');
        $this->addSql('DROP INDEX IDX_BF5476CAEDB4C999 ON notification');
        $this->addSql('DROP INDEX IDX_BF5476CAF87E18EC ON notification');
        $this->addSql('ALTER TABLE notification DROP child_users_id, DROP parent_users_id');
    }
}
