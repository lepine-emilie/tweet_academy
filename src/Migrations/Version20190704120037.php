<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190704120037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE upload DROP type, CHANGE uploader_id uploader_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE follower DROP FOREIGN KEY FK_B9D60946E8DDDA11');
        $this->addSql('DROP INDEX IDX_B9D60946E8DDDA11 ON follower');
        $this->addSql('ALTER TABLE follower CHANGE unfollow_date unfollow_date DATETIME DEFAULT NULL, CHANGE follower_id_id follower_id INT NOT NULL');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D60946AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B9D60946AC24F853 ON follower (follower_id)');
        $this->addSql('ALTER TABLE chat_conversation CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE parent_post_id parent_post_id INT DEFAULT NULL, CHANGE source_post_id source_post_id INT DEFAULT NULL, CHANGE media_url media_url VARCHAR(255) DEFAULT NULL, CHANGE media_type media_type SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE notification_data notification_data JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat_conversation CHANGE name name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE follower DROP FOREIGN KEY FK_B9D60946AC24F853');
        $this->addSql('DROP INDEX IDX_B9D60946AC24F853 ON follower');
        $this->addSql('ALTER TABLE follower CHANGE unfollow_date unfollow_date DATETIME DEFAULT \'NULL\', CHANGE follower_id follower_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D60946E8DDDA11 FOREIGN KEY (follower_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B9D60946E8DDDA11 ON follower (follower_id_id)');
        $this->addSql('ALTER TABLE notification CHANGE notification_data notification_data LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE post CHANGE parent_post_id parent_post_id INT DEFAULT NULL, CHANGE source_post_id source_post_id INT DEFAULT NULL, CHANGE media_url media_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE media_type media_type SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE upload ADD type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE uploader_id uploader_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date DATE DEFAULT \'NULL\', CHANGE city city VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
