CREATE TABLE upload
(
    id          INT AUTO_INCREMENT NOT NULL,
    uploader_id INT          DEFAULT NULL,
    path        VARCHAR(255)       NOT NULL,
    type        VARCHAR(255) DEFAULT NULL,
    INDEX IDX_17BDE61F16678C77 (uploader_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE follower
(
    id            INT AUTO_INCREMENT NOT NULL,
    user_id       INT                NOT NULL,
    follower_id   INT                NOT NULL,
    follow_date   DATETIME           NOT NULL,
    unfollow_date DATETIME DEFAULT NULL,
    INDEX IDX_B9D60946A76ED395 (user_id),
    INDEX IDX_B9D60946AC24F853 (follower_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE chat_conversation
(
    id   INT AUTO_INCREMENT NOT NULL,
    name VARCHAR(20) DEFAULT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE chat_participant
(
    chat_conversation_id INT NOT NULL,
    user_id              INT NOT NULL,
    INDEX IDX_E8ED9C896ABC99C1 (chat_conversation_id),
    INDEX IDX_E8ED9C89A76ED395 (user_id),
    PRIMARY KEY (chat_conversation_id, user_id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE favorite
(
    id      INT AUTO_INCREMENT NOT NULL,
    user_id INT                NOT NULL,
    post_id INT                NOT NULL,
    INDEX IDX_68C58ED9A76ED395 (user_id),
    INDEX IDX_68C58ED94B89032C (post_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE hashtag
(
    id        INT AUTO_INCREMENT NOT NULL,
    name      VARCHAR(255)       NOT NULL,
    use_count INT                NOT NULL,
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE post_hashtag
(
    hashtag_id INT NOT NULL,
    post_id    INT NOT NULL,
    INDEX IDX_675D9D52FB34EF56 (hashtag_id),
    INDEX IDX_675D9D524B89032C (post_id),
    PRIMARY KEY (hashtag_id, post_id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE chat_message
(
    id              INT AUTO_INCREMENT NOT NULL,
    conversation_id INT                NOT NULL,
    sender_id       INT                NOT NULL,
    content         VARCHAR(255)       NOT NULL,
    submit_time     DATETIME           NOT NULL,
    INDEX IDX_FAB3FC169AC0396 (conversation_id),
    INDEX IDX_FAB3FC16F624B39D (sender_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE post
(
    id             INT AUTO_INCREMENT NOT NULL,
    sender_id      INT                NOT NULL,
    parent_post_id INT          DEFAULT NULL,
    source_post_id INT          DEFAULT NULL,
    content        VARCHAR(255)       NOT NULL,
    media_url      VARCHAR(255) DEFAULT NULL,
    media_type     SMALLINT     DEFAULT NULL,
    submit_time    DATETIME           NOT NULL,
    INDEX IDX_5A8A6C8DF624B39D (sender_id),
    INDEX IDX_5A8A6C8D39C1776A (parent_post_id),
    INDEX IDX_5A8A6C8D256BB44 (source_post_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE user
(
    id           INT AUTO_INCREMENT NOT NULL,
    username     VARCHAR(16)        NOT NULL,
    display_name VARCHAR(24)        NOT NULL,
    email        VARCHAR(180)       NOT NULL,
    password     VARCHAR(255)       NOT NULL,
    birth_date   DATE         DEFAULT NULL,
    city         VARCHAR(255) DEFAULT NULL,
    gender       SMALLINT     DEFAULT NULL,
    theme_color  VARCHAR(255) DEFAULT NULL,
    UNIQUE INDEX UNIQ_8D93D649F85E0677 (username),
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE notification
(
    id                INT AUTO_INCREMENT NOT NULL,
    user_id           INT                NOT NULL,
    notification_type SMALLINT           NOT NULL,
    notification_data JSON               NOT NULL,
    is_read           TINYINT(1)         NOT NULL,
    INDEX IDX_BF5476CAA76ED395 (user_id),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
ALTER TABLE upload
    ADD CONSTRAINT FK_17BDE61F16678C77 FOREIGN KEY (uploader_id) REFERENCES user (id);
ALTER TABLE follower
    ADD CONSTRAINT FK_B9D60946A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE follower
    ADD CONSTRAINT FK_B9D60946AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id);
ALTER TABLE chat_participant
    ADD CONSTRAINT FK_E8ED9C896ABC99C1 FOREIGN KEY (chat_conversation_id) REFERENCES chat_conversation (id) ON DELETE CASCADE;
ALTER TABLE chat_participant
    ADD CONSTRAINT FK_E8ED9C89A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
ALTER TABLE favorite
    ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE favorite
    ADD CONSTRAINT FK_68C58ED94B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE;
ALTER TABLE post_hashtag
    ADD CONSTRAINT FK_675D9D52FB34EF56 FOREIGN KEY (hashtag_id) REFERENCES hashtag (id) ON DELETE CASCADE;
ALTER TABLE post_hashtag
    ADD CONSTRAINT FK_675D9D524B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE;
ALTER TABLE chat_message
    ADD CONSTRAINT FK_FAB3FC169AC0396 FOREIGN KEY (conversation_id) REFERENCES chat_conversation (id);
ALTER TABLE chat_message
    ADD CONSTRAINT FK_FAB3FC16F624B39D FOREIGN KEY (sender_id) REFERENCES user (id);
ALTER TABLE post
    ADD CONSTRAINT FK_5A8A6C8DF624B39D FOREIGN KEY (sender_id) REFERENCES user (id);
ALTER TABLE post
    ADD CONSTRAINT FK_5A8A6C8D39C1776A FOREIGN KEY (parent_post_id) REFERENCES post (id) ON DELETE CASCADE;
ALTER TABLE post
    ADD CONSTRAINT FK_5A8A6C8D256BB44 FOREIGN KEY (source_post_id) REFERENCES post (id) ON DELETE CASCADE;
ALTER TABLE notification
    ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
