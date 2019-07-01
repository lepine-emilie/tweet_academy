<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190701125756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE follower (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, follower_id_id INT NOT NULL, follow_date DATETIME NOT NULL, unfollow_date DATETIME DEFAULT NULL, INDEX IDX_B9D60946A76ED395 (user_id), INDEX IDX_B9D60946E8DDDA11 (follower_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_conversation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_conversation_user (chat_conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BFCC678C6ABC99C1 (chat_conversation_id), INDEX IDX_BFCC678CA76ED395 (user_id), PRIMARY KEY(chat_conversation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_68C58ED9A76ED395 (user_id), INDEX IDX_68C58ED94B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, sender_id INT NOT NULL, content VARCHAR(255) NOT NULL, submit_time DATETIME NOT NULL, INDEX IDX_FAB3FC169AC0396 (conversation_id), INDEX IDX_FAB3FC16F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, parent_post_id INT DEFAULT NULL, source_post_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, media_url VARCHAR(255) DEFAULT NULL, media_type SMALLINT DEFAULT NULL, submit_time DATETIME NOT NULL, INDEX IDX_5A8A6C8DF624B39D (sender_id), INDEX IDX_5A8A6C8D39C1776A (parent_post_id), INDEX IDX_5A8A6C8D256BB44 (source_post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(32) NOT NULL, display_name VARCHAR(64) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, birth_date DATE DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, notification_type SMALLINT NOT NULL, notification_data JSON NOT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D60946A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE follower ADD CONSTRAINT FK_B9D60946E8DDDA11 FOREIGN KEY (follower_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_conversation_user ADD CONSTRAINT FK_BFCC678C6ABC99C1 FOREIGN KEY (chat_conversation_id) REFERENCES chat_conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_conversation_user ADD CONSTRAINT FK_BFCC678CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED94B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC169AC0396 FOREIGN KEY (conversation_id) REFERENCES chat_conversation (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D39C1776A FOREIGN KEY (parent_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D256BB44 FOREIGN KEY (source_post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat_conversation_user DROP FOREIGN KEY FK_BFCC678C6ABC99C1');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC169AC0396');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED94B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D39C1776A');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D256BB44');
        $this->addSql('ALTER TABLE follower DROP FOREIGN KEY FK_B9D60946A76ED395');
        $this->addSql('ALTER TABLE follower DROP FOREIGN KEY FK_B9D60946E8DDDA11');
        $this->addSql('ALTER TABLE chat_conversation_user DROP FOREIGN KEY FK_BFCC678CA76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC16F624B39D');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF624B39D');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('DROP TABLE follower');
        $this->addSql('DROP TABLE chat_conversation');
        $this->addSql('DROP TABLE chat_conversation_user');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE notification');
    }
}
