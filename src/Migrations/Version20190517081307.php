<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190517081307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE profile_image (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(1023) NOT NULL, is_local TINYINT(1) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

       $this->addSql('CREATE TABLE child_user_profile_image (child_user_id INT NOT NULL, profile_image_id INT NOT NULL, INDEX IDX_5B9B0451C5DA9B8E (child_user_id), INDEX IDX_5B9B0451C4CF44DC (profile_image_id), PRIMARY KEY(child_user_id, profile_image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE child_user_profile_image ADD CONSTRAINT FK_5B9B0451C5DA9B8E FOREIGN KEY (child_user_id) REFERENCES child_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE child_user_profile_image ADD CONSTRAINT FK_5B9B0451C4CF44DC FOREIGN KEY (profile_image_id) REFERENCES profile_image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quest ADD exp INT NOT NULL, ADD gold_coins INT NOT NULL');
        $this->addSql('ALTER TABLE child_user ADD profile_image_id INT DEFAULT NULL, ADD level_crystal INT NOT NULL, ADD gold_coins INT NOT NULL');
        $this->addSql('ALTER TABLE child_user ADD CONSTRAINT FK_38A275BBC4CF44DC FOREIGN KEY (profile_image_id) REFERENCES profile_image (id)');
        $this->addSql('CREATE INDEX IDX_38A275BBC4CF44DC ON child_user (profile_image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE child_user DROP FOREIGN KEY FK_38A275BBC4CF44DC');
        $this->addSql('ALTER TABLE child_user_profile_image DROP FOREIGN KEY FK_5B9B0451C4CF44DC');
        $this->addSql('DROP TABLE child_user_profile_image');
        $this->addSql('DROP TABLE profile_image');
        $this->addSql('DROP INDEX IDX_38A275BBC4CF44DC ON child_user');
        $this->addSql('ALTER TABLE child_user DROP profile_image_id, DROP level_crystal, DROP gold_coins');
        $this->addSql('ALTER TABLE quest DROP exp, DROP gold_coins');
    }
}
