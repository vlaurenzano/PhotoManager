CREATE TABLE `users` (
  `id` INT NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC));

ALTER TABLE `users` 
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;


CREATE TABLE `photos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image` LONGTEXT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`));
