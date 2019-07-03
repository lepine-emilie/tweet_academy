<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190703130859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chat_participant (chat_conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E8ED9C896ABC99C1 (chat_conversation_id), INDEX IDX_E8ED9C89A76ED395 (user_id), PRIMARY KEY(chat_conversation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_participant ADD CONSTRAINT FK_E8ED9C896ABC99C1 FOREIGN KEY (chat_conversation_id) REFERENCES chat_conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_participant ADD CONSTRAINT FK_E8ED9C89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE chat_conversation_user');
        $this->addSql('ALTER TABLE follower CHANGE unfollow_date unfollow_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_conversation CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE parent_post_id parent_post_id INT DEFAULT NULL, CHANGE source_post_id source_post_id INT DEFAULT NULL, CHANGE media_url media_url VARCHAR(255) DEFAULT NULL, CHANGE media_type media_type SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date DATE DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE notification CHANGE notification_data notification_data JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chat_conversation_user (chat_conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BFCC678C6ABC99C1 (chat_conversation_id), INDEX IDX_BFCC678CA76ED395 (user_id), PRIMARY KEY(chat_conversation_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE chat_conversation_user ADD CONSTRAINT FK_BFCC678C6ABC99C1 FOREIGN KEY (chat_conversation_id) REFERENCES chat_conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_conversation_user ADD CONSTRAINT FK_BFCC678CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE chat_participant');
        $this->addSql('ALTER TABLE chat_conversation CHANGE name name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE follower CHANGE unfollow_date unfollow_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE notification CHANGE notification_data notification_data LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE post CHANGE parent_post_id parent_post_id INT DEFAULT NULL, CHANGE source_post_id source_post_id INT DEFAULT NULL, CHANGE media_url media_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE media_type media_type SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE birth_date birth_date DATE DEFAULT \'NULL\', CHANGE city city VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
