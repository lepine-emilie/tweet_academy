<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190711082838 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hashtag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, use_count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hashtag_post (hashtag_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_EFB38414FB34EF56 (hashtag_id), INDEX IDX_EFB384144B89032C (post_id), PRIMARY KEY(hashtag_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hashtag_post ADD CONSTRAINT FK_EFB38414FB34EF56 FOREIGN KEY (hashtag_id) REFERENCES hashtag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hashtag_post ADD CONSTRAINT FK_EFB384144B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE upload CHANGE uploader_id uploader_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE follower CHANGE unfollow_date unfollow_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_conversation CHANGE name name VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE parent_post_id parent_post_id INT DEFAULT NULL, CHANGE source_post_id source_post_id INT DEFAULT NULL, CHANGE media_url media_url VARCHAR(255) DEFAULT NULL, CHANGE media_type media_type SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE gender gender SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE notification_data notification_data JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hashtag_post DROP FOREIGN KEY FK_EFB38414FB34EF56');
        $this->addSql('DROP TABLE hashtag');
        $this->addSql('DROP TABLE hashtag_post');
        $this->addSql('ALTER TABLE chat_conversation CHANGE name name VARCHAR(20) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE follower CHANGE unfollow_date unfollow_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE notification CHANGE notification_data notification_data LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE post CHANGE parent_post_id parent_post_id INT DEFAULT NULL, CHANGE source_post_id source_post_id INT DEFAULT NULL, CHANGE media_url media_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE media_type media_type SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE upload CHANGE uploader_id uploader_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date DATE DEFAULT \'NULL\', CHANGE city city VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE gender gender SMALLINT DEFAULT NULL');
    }
}
