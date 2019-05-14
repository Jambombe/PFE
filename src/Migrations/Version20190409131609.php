<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190409131609 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE child_user (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, password VARCHAR(1023) NOT NULL, exp INT NOT NULL, INDEX IDX_38A275BB727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trophy (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('CREATE TABLE child_user_trophy (child_user_id INT NOT NULL, trophy_id INT NOT NULL, INDEX IDX_AEFC86FAC5DA9B8E (child_user_id), INDEX IDX_AEFC86FAF59AEEEF (trophy_id), PRIMARY KEY(child_user_id, trophy_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, child_users_id INT DEFAULT NULL, parent_users_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, message VARCHAR(511) DEFAULT NULL, type INT NOT NULL, INDEX IDX_BF5476CAEDB4C999 (child_users_id), INDEX IDX_BF5476CAF87E18EC (parent_users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parent_user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_token VARCHAR(45) DEFAULT NULL, password VARCHAR(1023) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', lost_password_token VARCHAR(45) DEFAULT NULL, lost_password_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B070E0FDE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quest (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, child_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(2047) DEFAULT NULL, daily TIME DEFAULT NULL, assignated_date DATETIME DEFAULT NULL, return_date DATETIME DEFAULT NULL, limit_time TIME DEFAULT NULL, status INT NOT NULL, INDEX IDX_4317F8177E3C61F9 (owner_id), INDEX IDX_4317F817DD62C21B (child_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE child_user ADD CONSTRAINT FK_38A275BB727ACA70 FOREIGN KEY (parent_id) REFERENCES parent_user (id)');
        $this->addSql('ALTER TABLE child_user_trophy ADD CONSTRAINT FK_AEFC86FAC5DA9B8E FOREIGN KEY (child_user_id) REFERENCES child_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE child_user_trophy ADD CONSTRAINT FK_AEFC86FAF59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAEDB4C999 FOREIGN KEY (child_users_id) REFERENCES child_user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF87E18EC FOREIGN KEY (parent_users_id) REFERENCES parent_user (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F8177E3C61F9 FOREIGN KEY (owner_id) REFERENCES parent_user (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F817DD62C21B FOREIGN KEY (child_id) REFERENCES child_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE child_user_trophy DROP FOREIGN KEY FK_AEFC86FAC5DA9B8E');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAEDB4C999');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F817DD62C21B');
        $this->addSql('ALTER TABLE child_user DROP FOREIGN KEY FK_38A275BB727ACA70');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAF87E18EC');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F8177E3C61F9');
        $this->addSql('ALTER TABLE child_user_trophy DROP FOREIGN KEY FK_AEFC86FAF59AEEEF');
        $this->addSql('DROP TABLE child_user');
        $this->addSql('DROP TABLE child_user_trophy');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE parent_user');
        $this->addSql('DROP TABLE quest');
        $this->addSql('DROP TABLE trophy');
    }
}
