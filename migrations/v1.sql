CREATE TABLE user (
  id         INT AUTO_INCREMENT NOT NULL,
  email      VARCHAR(255)       NOT NULL,
  roles      LONGTEXT           NOT NULL
  COMMENT '(DC2Type:array)',
  password   VARCHAR(255)       NOT NULL,
  created_at DATETIME           NOT NULL,
  updated_at DATETIME           NOT NULL,
  UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;