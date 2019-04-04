<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190404080038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE trophy (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE child_user_trophy (child_user_id INT NOT NULL, trophy_id INT NOT NULL, INDEX IDX_AEFC86FAC5DA9B8E (child_user_id), INDEX IDX_AEFC86FAF59AEEEF (trophy_id), PRIMARY KEY(child_user_id, trophy_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE child_user_trophy ADD CONSTRAINT FK_AEFC86FAC5DA9B8E FOREIGN KEY (child_user_id) REFERENCES child_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE child_user_trophy ADD CONSTRAINT FK_AEFC86FAF59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophy (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE child_user_trophy DROP FOREIGN KEY FK_AEFC86FAF59AEEEF');
        $this->addSql('DROP TABLE trophy');
        $this->addSql('DROP TABLE child_user_trophy');
    }
}
