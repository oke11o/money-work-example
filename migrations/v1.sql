CREATE TABLE user (
  id       INT AUTO_INCREMENT NOT NULL,
  email    VARCHAR(255)       NOT NULL,
  password VARCHAR(255)       NOT NULL,
  amount   BIGINT DEFAULT 0,
  UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
  PRIMARY KEY (id)
)
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;

INSERT INTO user (email, password, amount)
    VALUES
        ('admin@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000),
      ('admin+1@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000),
      ('admin+2@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000),
      ('admin+3@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000),
      ('admin+4@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000),
      ('admin+5@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000),
      ('admin+6@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000),
      ('admin+7@admin.ru', '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva', 100000);