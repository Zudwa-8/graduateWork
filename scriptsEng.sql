
CREATE SCHEMA IF NOT EXISTS `bd`;
USE `bd` ;

-- -----------------------------------------------------
-- Table `bd`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`roles` (
  `id_role` INT NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(25) NOT NULL,
  `priority` INT NOT NULL,
  PRIMARY KEY (`id_role`));


-- -----------------------------------------------------
-- Table `bd`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`users` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(25) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `fullname` VARCHAR(64) NULL DEFAULT NULL,
  `date_register` VARCHAR(19) NOT NULL,
  `role_id_role` INT NOT NULL,
  PRIMARY KEY (`id_user`),
  INDEX `fk_users_role1_idx` (`role_id_role` ASC),
  CONSTRAINT `fk_users_role1`
    FOREIGN KEY (`role_id_role`)
    REFERENCES `bd`.`roles` (`id_role`));


-- -----------------------------------------------------
-- Table `bd`.`contacts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`contacts` (
  `id_contact` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(128) NOT NULL,
  `email_confirm` TINYINT NOT NULL,
  `telephone` VARCHAR(18) NOT NULL,
  `date_edit` VARCHAR(19) NOT NULL,
  `users_id_user` INT NOT NULL,
  PRIMARY KEY (`id_contact`),
  INDEX `fk_contacts_users_idx` (`users_id_user` ASC),
  CONSTRAINT `fk_contacts_users`
    FOREIGN KEY (`users_id_user`)
    REFERENCES `bd`.`users` (`id_user`));


-- -----------------------------------------------------
-- Table `bd`.`news`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`news` (
  `id_new` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(256) NOT NULL,
  `text` LONGTEXT NOT NULL,
  `date` VARCHAR(19) NOT NULL,
  `users_id_user` INT NOT NULL,
  PRIMARY KEY (`id_new`),
  INDEX `fk_news_users1_idx` (`users_id_user` ASC),
  CONSTRAINT `fk_news_users1`
    FOREIGN KEY (`users_id_user`)
    REFERENCES `bd`.`users` (`id_user`));


-- -----------------------------------------------------
-- Table `bd`.`level_of_knowledge`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`level_of_knowledge` (
  `id_level_of_knowledge` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id_level_of_knowledge`));


-- -----------------------------------------------------
-- Table `bd`.`courses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`courses` (
  `id_course` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` LONGTEXT NULL,
  `cost` INT NULL,
  `level_of_knowledge_id_level_of_knowledge` INT NOT NULL,
  PRIMARY KEY (`id_course`),
  INDEX `fk_courses_level_of_knowledge1_idx` (`level_of_knowledge_id_level_of_knowledge` ASC),
  CONSTRAINT `fk_courses_level_of_knowledge1`
    FOREIGN KEY (`level_of_knowledge_id_level_of_knowledge`)
    REFERENCES `bd`.`level_of_knowledge` (`id_level_of_knowledge`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `bd`.`topics`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`topics` (
  `id_topic` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` LONGTEXT NULL,
  `courses_id_course` INT NOT NULL,
  PRIMARY KEY (`id_topic`),
  INDEX `fk_topics_courses1_idx` (`courses_id_course` ASC),
  CONSTRAINT `fk_topics_courses1`
    FOREIGN KEY (`courses_id_course`)
    REFERENCES `bd`.`courses` (`id_course`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `bd`.`lessons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`lessons` (
  `id_lesson` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `text` LONGTEXT NULL,
  `topics_id_topic` INT NOT NULL,
  PRIMARY KEY (`id_lesson`),
  INDEX `fk_lessons_topics1_idx` (`topics_id_topic` ASC),
  CONSTRAINT `fk_lessons_topics1`
    FOREIGN KEY (`topics_id_topic`)
    REFERENCES `bd`.`topics` (`id_topic`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `bd`.`purchased_courses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`purchased_courses` (
  `id_purchased_course` INT NOT NULL AUTO_INCREMENT,
  `users_id_user` INT NOT NULL,
  `courses_id_course` INT NOT NULL,
  `document_number` VARCHAR(45) NULL,
  `payment_verification` TINYINT NULL,
  PRIMARY KEY (`id_purchased_course`),
  INDEX `fk_purchased_courses_users1_idx` (`users_id_user` ASC),
  INDEX `fk_purchased_courses_courses1_idx` (`courses_id_course` ASC),
  CONSTRAINT `fk_purchased_courses_users1`
    FOREIGN KEY (`users_id_user`)
    REFERENCES `bd`.`users` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_purchased_courses_courses1`
    FOREIGN KEY (`courses_id_course`)
    REFERENCES `bd`.`courses` (`id_course`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `bd`.`progress_learning`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `bd`.`progress_learning` (
  `id_progress_learning` INT NOT NULL,
  `users_id_user` INT NOT NULL,
  `lessons_id_lesson` INT NOT NULL,
  PRIMARY KEY (`id_progress_learning`),
  INDEX `fk_progress_learning_users1_idx` (`users_id_user` ASC),
  INDEX `fk_progress_learning_lessons1_idx` (`lessons_id_lesson` ASC),
  CONSTRAINT `fk_progress_learning_users1`
    FOREIGN KEY (`users_id_user`)
    REFERENCES `bd`.`users` (`id_user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_progress_learning_lessons1`
    FOREIGN KEY (`lessons_id_lesson`)
    REFERENCES `bd`.`lessons` (`id_lesson`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
