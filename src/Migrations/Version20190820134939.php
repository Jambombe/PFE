<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190820134939 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE child_user_trophy (child_user_id INT NOT NULL, trophy_id INT NOT NULL, INDEX IDX_AEFC86FAC5DA9B8E (child_user_id), INDEX IDX_AEFC86FAF59AEEEF (trophy_id), PRIMARY KEY(child_user_id, trophy_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE child_user_profile_image (child_user_id INT NOT NULL, profile_image_id INT NOT NULL, INDEX IDX_5B9B0451C5DA9B8E (child_user_id), INDEX IDX_5B9B0451C4CF44DC (profile_image_id), PRIMARY KEY(child_user_id, profile_image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE custom_reward (id INT AUTO_INCREMENT NOT NULL, reward_owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(511) DEFAULT NULL, gold_coin_price INT NOT NULL, image VARCHAR(1023) NOT NULL, INDEX IDX_7A7CA657CDB0AC2A (reward_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, child_users_id INT DEFAULT NULL, parent_users_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, message VARCHAR(511) DEFAULT NULL, type INT NOT NULL, INDEX IDX_BF5476CAEDB4C999 (child_users_id), INDEX IDX_BF5476CAF87E18EC (parent_users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile_image (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(1023) NOT NULL, name VARCHAR(127) NOT NULL, is_local TINYINT(1) NOT NULL, price INT NOT NULL, required_level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trophy (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, category INT NOT NULL, argument INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE child_user_trophy ADD CONSTRAINT FK_AEFC86FAC5DA9B8E FOREIGN KEY (child_user_id) REFERENCES child_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE child_user_trophy ADD CONSTRAINT FK_AEFC86FAF59AEEEF FOREIGN KEY (trophy_id) REFERENCES trophy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE child_user_profile_image ADD CONSTRAINT FK_5B9B0451C5DA9B8E FOREIGN KEY (child_user_id) REFERENCES child_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE child_user_profile_image ADD CONSTRAINT FK_5B9B0451C4CF44DC FOREIGN KEY (profile_image_id) REFERENCES profile_image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE custom_reward ADD CONSTRAINT FK_7A7CA657CDB0AC2A FOREIGN KEY (reward_owner_id) REFERENCES parent_user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAEDB4C999 FOREIGN KEY (child_users_id) REFERENCES child_user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAF87E18EC FOREIGN KEY (parent_users_id) REFERENCES parent_user (id)');
        $this->addSql('ALTER TABLE child_user ADD profile_image_id INT DEFAULT NULL, ADD pseudo VARCHAR(255) NOT NULL, ADD level_crystal INT NOT NULL, ADD gold_coins INT NOT NULL');
        $this->addSql('ALTER TABLE child_user ADD CONSTRAINT FK_38A275BBC4CF44DC FOREIGN KEY (profile_image_id) REFERENCES profile_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_38A275BB86CC499D ON child_user (pseudo)');
        $this->addSql('CREATE INDEX IDX_38A275BBC4CF44DC ON child_user (profile_image_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B070E0FDE7927C74 ON parent_user (email)');
        $this->addSql('ALTER TABLE quest ADD exp INT NOT NULL, ADD gold_coins INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE child_user DROP FOREIGN KEY FK_38A275BBC4CF44DC');
        $this->addSql('ALTER TABLE child_user_profile_image DROP FOREIGN KEY FK_5B9B0451C4CF44DC');
        $this->addSql('ALTER TABLE child_user_trophy DROP FOREIGN KEY FK_AEFC86FAF59AEEEF');
        $this->addSql('DROP TABLE child_user_trophy');
        $this->addSql('DROP TABLE child_user_profile_image');
        $this->addSql('DROP TABLE custom_reward');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE profile_image');
        $this->addSql('DROP TABLE trophy');
        $this->addSql('DROP INDEX UNIQ_38A275BB86CC499D ON child_user');
        $this->addSql('DROP INDEX IDX_38A275BBC4CF44DC ON child_user');
        $this->addSql('ALTER TABLE child_user DROP profile_image_id, DROP pseudo, DROP level_crystal, DROP gold_coins');
        $this->addSql('DROP INDEX UNIQ_B070E0FDE7927C74 ON parent_user');
        $this->addSql('ALTER TABLE quest DROP exp, DROP gold_coins');
    }
}
