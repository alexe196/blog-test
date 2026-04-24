CREATE DATABASE IF NOT EXISTS blog_test
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE blog_test;

DROP TABLE IF EXISTS category_post;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
                            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            title VARCHAR(255) NOT NULL,
                            description TEXT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE posts (
                       id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                       image VARCHAR(255) NULL,
                       title VARCHAR(255) NOT NULL,
                       description TEXT NULL,
                       content TEXT NOT NULL,
                       views INT UNSIGNED NOT NULL DEFAULT 0,
                       published_at DATETIME NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE category_post (
                               category_id INT UNSIGNED NOT NULL,
                               post_id INT UNSIGNED NOT NULL,

                               PRIMARY KEY (category_id, post_id),

                               CONSTRAINT fk_category_post_category
                                   FOREIGN KEY (category_id) REFERENCES categories(id)
                                       ON DELETE CASCADE,

                               CONSTRAINT fk_category_post_post
                                   FOREIGN KEY (post_id) REFERENCES posts(id)
                                       ON DELETE CASCADE
);

CREATE INDEX idx_posts_published_at ON posts(published_at);
CREATE INDEX idx_posts_views ON posts(views);