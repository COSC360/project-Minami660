CREATE TABLE blogs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  description TEXT NOT NULL
  title VARCHAR(255) NOT NULL,
);

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  is_admin BIT(1) NOT NULL,
  blog_id INT UNSIGNED,
  CONSTRAINT fk_user_blog
    FOREIGN KEY (blog_id)
    REFERENCES blogs (id)
    ON DELETE SET NULL
);

CREATE TABLE posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  body TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  title VARCHAR(255) NOT NULL,
  blog_id INT UNSIGNED NOT NULL,
  CONSTRAINT fk_post_blog
    FOREIGN KEY (blog_id)
    REFERENCES blogs (id)
    ON DELETE CASCADE
);

CREATE TABLE comments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  body TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  post_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  CONSTRAINT fk_comment_post
    FOREIGN KEY (post_id)
    REFERENCES posts (id)
    ON DELETE CASCADE,
  CONSTRAINT fk_comment_user
    FOREIGN KEY (user_id)
    REFERENCES users (id)
    ON DELETE CASCADE
);

CREATE TABLE post_tags (
  post_id INT UNSIGNED NOT NULL,
  tag VARCHAR(255) NOT NULL,
  PRIMARY KEY (post_id, tag),
  CONSTRAINT fk_posttag_post
    FOREIGN KEY (post_id)
    REFERENCES posts (id)
    ON DELETE CASCADE
);
