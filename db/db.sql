-- MySQL Script generated by MySQL Workbench
-- Sat Jul 13 20:56:56 2019
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema prod_8
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `prod_8` ;

-- -----------------------------------------------------
-- Schema prod_8
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `prod_8` ;
USE `prod_8` ;

-- -----------------------------------------------------
-- Table `prod_8`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`users` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(50) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prod_8`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`groups` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` TEXT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prod_8`.`products`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`products` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `image` VARCHAR(16) NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `active_flag` TINYINT NULL DEFAULT 1,
  `new_flag` TINYINT NULL,
  `sale_flag` TINYINT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prod_8`.`сategories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`сategories` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`сategories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prod_8`.`orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`orders` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `create_time` DATETIME NOT NULL,
  `processed_flag` TINYINT NOT NULL DEFAULT 0,
  `product_id` INT NOT NULL,
  `cost` DECIMAL(10,2) NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `middle_name` VARCHAR(50) NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `delivery` TINYINT NOT NULL,
  `cash` TINYINT NOT NULL,
  `comment` TEXT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_orders_products1_idx` (`product_id` ASC),
  CONSTRAINT `fk_orders_products1`
    FOREIGN KEY (`product_id`)
    REFERENCES `prod_8`.`products` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prod_8`.`addresses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`addresses` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`addresses` (
  `id` INT NOT NULL,
  `city` VARCHAR(50) NOT NULL,
  `street` VARCHAR(50) NOT NULL,
  `house` VARCHAR(20) NOT NULL,
  `apartment` VARCHAR(30) NOT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_addresses_orders_idx` (`id` ASC),
  CONSTRAINT `fk_addresses_orders`
    FOREIGN KEY (`id`)
    REFERENCES `prod_8`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prod_8`.`category_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`category_product` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`category_product` (
  `product_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`, `category_id`),
  INDEX `fk_product_category_сategories1_idx` (`category_id` ASC),
  CONSTRAINT `fk_product_category_products1`
    FOREIGN KEY (`product_id`)
    REFERENCES `prod_8`.`products` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_category_сategories1`
    FOREIGN KEY (`category_id`)
    REFERENCES `prod_8`.`сategories` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `prod_8`.`group_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `prod_8`.`group_user` ;

CREATE TABLE IF NOT EXISTS `prod_8`.`group_user` (
  `user_id` INT NOT NULL,
  `group_id` INT NOT NULL,
  `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `group_id`),
  INDEX `fk_user_group_groups1_idx` (`group_id` ASC),
  CONSTRAINT `fk_user_group_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `prod_8`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_group_groups1`
    FOREIGN KEY (`group_id`)
    REFERENCES `prod_8`.`groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SET SQL_MODE = '';
DROP USER IF EXISTS prod_admin;
SET SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
CREATE USER 'prod_admin' IDENTIFIED BY 'prod_admin';

GRANT ALL ON `prod_8`.* TO 'prod_admin';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `prod_8`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`users` (`id`, `login`, `password_hash`, `last_update`) VALUES (1, 'admin@test.ru', '$2y$10$U6Lb8oGbRiH9SchXq5I5nOWe4rgaS/n6q.hufxGs4dMUSD9BlltAe', DEFAULT);
INSERT INTO `prod_8`.`users` (`id`, `login`, `password_hash`, `last_update`) VALUES (2, 'operator@test.ru', '$2y$10$O14SisMQGlXPJXiq25y0Ke.LgjEXFyN8zOWu28.zts5EZrBXtfoTu', DEFAULT);
INSERT INTO `prod_8`.`users` (`id`, `login`, `password_hash`, `last_update`) VALUES (3, 'user@test.ru', '$2y$10$K1xnFOF/M5qNEnLxIuRuA.psKDxwQ41cMdzPriRhzghLTNuTEilGm', DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `prod_8`.`groups`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`groups` (`id`, `name`, `description`, `last_update`) VALUES (1, 'admins', NULL, DEFAULT);
INSERT INTO `prod_8`.`groups` (`id`, `name`, `description`, `last_update`) VALUES (2, 'operators', NULL, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `prod_8`.`products`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 1', 'product-1.jpg', 1234.56, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 2', 'product-2.jpg', 1900, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 3', 'product-3.jpg', 5600, 1, 1, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 4', 'product-4.jpg', 8900, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 5', 'product-5.jpg', 7800, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 6', 'product-6.jpg', 500, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 7', 'product-7.jpg', 2300, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 8', 'product-8.jpg', 2700, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 9', 'product-9.jpg', 1700, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 10', 'product-0.jpg', 9400, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 11', 'product-1.jpg', 2800, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 12', 'product-2.jpg', 4700, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 13', 'product-3.jpg', 4600, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 14', 'product-4.jpg', 7700, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 15', 'product-5.jpg', 6300, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 16', 'product-6.jpg', 4300, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 17', 'product-7.jpg', 1600, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 18', 'product-8.jpg', 8600, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 19', 'product-9.jpg', 600, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 20', 'product-0.jpg', 9700, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 21', 'product-1.jpg', 3800, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 22', 'product-2.jpg', 2600, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 23', 'product-3.jpg', 1800, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 24', 'product-4.jpg', 2400, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 25', 'product-5.jpg', 600, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 26', 'product-6.jpg', 9900, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 27', 'product-7.jpg', 3600, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 28', 'product-8.jpg', 9000, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 29', 'product-9.jpg', 8500, 1, 1, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 30', 'product-0.jpg', 7500, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 31', 'product-1.jpg', 6600, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 32', 'product-2.jpg', 500, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 33', 'product-3.jpg', 8300, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 34', 'product-4.jpg', 3900, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 35', 'product-5.jpg', 6300, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 36', 'product-6.jpg', 300, 1, 1, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 37', 'product-7.jpg', 6800, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 38', 'product-8.jpg', 6700, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 39', 'product-9.jpg', 6400, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 40', 'product-0.jpg', 8800, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 41', 'product-1.jpg', 1300, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 42', 'product-2.jpg', 8900, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 43', 'product-3.jpg', 3500, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 44', 'product-4.jpg', 1600, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 45', 'product-5.jpg', 7100, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 46', 'product-6.jpg', 4300, 1, NULL, 1, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 47', 'product-7.jpg', 5500, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 48', 'product-8.jpg', 8600, 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 49', 'product-9.jpg', 2100, 1, NULL, NULL, DEFAULT);
INSERT INTO `prod_8`.`products` (`id`, `name`, `image`, `price`, `active_flag`, `new_flag`, `sale_flag`, `last_update`) VALUES (DEFAULT, 'Товар 50', 'product-0.jpg', 5800, 1, NULL, 1, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `prod_8`.`сategories`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`сategories` (`id`, `name`, `last_update`) VALUES (DEFAULT, 'Женщины', DEFAULT);
INSERT INTO `prod_8`.`сategories` (`id`, `name`, `last_update`) VALUES (DEFAULT, 'Мужчины', DEFAULT);
INSERT INTO `prod_8`.`сategories` (`id`, `name`, `last_update`) VALUES (DEFAULT, 'Дети', DEFAULT);
INSERT INTO `prod_8`.`сategories` (`id`, `name`, `last_update`) VALUES (DEFAULT, 'Аксессуары', DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `prod_8`.`orders`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`orders` (`id`, `create_time`, `processed_flag`, `product_id`, `cost`, `first_name`, `middle_name`, `last_name`, `phone`, `email`, `delivery`, `cash`, `comment`, `last_update`) VALUES (1, '2019-07-04 11:00:00', 1, 1, 1234.56, 'Иван', 'Иванович', 'Иванов', '+7 999 123 456', 'ivanov@mail.ru', 1, 1, NULL, DEFAULT);
INSERT INTO `prod_8`.`orders` (`id`, `create_time`, `processed_flag`, `product_id`, `cost`, `first_name`, `middle_name`, `last_name`, `phone`, `email`, `delivery`, `cash`, `comment`, `last_update`) VALUES (2, '2019-07-04 12:00:00', DEFAULT, 5, 2000, 'Иван', 'Иванович', 'Иванов', '+7 999 123 456', 'ivanov@mail.ru', 0, 0, 'text', DEFAULT);
INSERT INTO `prod_8`.`orders` (`id`, `create_time`, `processed_flag`, `product_id`, `cost`, `first_name`, `middle_name`, `last_name`, `phone`, `email`, `delivery`, `cash`, `comment`, `last_update`) VALUES (3, '2019-07-04 13:00:00', DEFAULT, 10, 3000, 'Иван', 'Иванович', 'Иванов', '+7 999 123 456', 'ivanov@mail.ru', 0, 1, '---', DEFAULT);
INSERT INTO `prod_8`.`orders` (`id`, `create_time`, `processed_flag`, `product_id`, `cost`, `first_name`, `middle_name`, `last_name`, `phone`, `email`, `delivery`, `cash`, `comment`, `last_update`) VALUES (4, '2019-07-04 14:00:00', DEFAULT, 11, 700, 'Иван', 'Иванович', 'Иванов', '+7 999 123 456', 'ivanov@mail.ru', 1, 0, NULL, DEFAULT);
INSERT INTO `prod_8`.`orders` (`id`, `create_time`, `processed_flag`, `product_id`, `cost`, `first_name`, `middle_name`, `last_name`, `phone`, `email`, `delivery`, `cash`, `comment`, `last_update`) VALUES (5, '2019-07-04 15:00:00', 1, 5, 500, 'Иван', 'Иванович', 'Иванов', '+7 999 123 456', 'ivanov@mail.ru', 1, 1, 'text', DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `prod_8`.`addresses`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`addresses` (`id`, `city`, `street`, `house`, `apartment`, `last_update`) VALUES (1, 'Владивосток', 'Главная', '1', '11', DEFAULT);
INSERT INTO `prod_8`.`addresses` (`id`, `city`, `street`, `house`, `apartment`, `last_update`) VALUES (4, 'Владивосток', 'Главная', '4', '44', DEFAULT);
INSERT INTO `prod_8`.`addresses` (`id`, `city`, `street`, `house`, `apartment`, `last_update`) VALUES (5, 'Владивосток', 'Главная', '5', '55', DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `prod_8`.`category_product`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (1, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (1, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (2, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (2, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (3, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (3, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (4, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (4, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (5, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (5, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (6, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (6, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (7, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (7, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (8, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (8, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (9, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (9, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (10, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (10, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (11, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (11, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (12, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (12, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (13, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (13, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (14, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (14, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (15, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (15, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (16, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (16, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (17, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (17, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (18, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (18, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (19, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (19, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (20, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (20, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (21, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (21, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (22, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (22, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (23, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (23, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (24, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (24, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (25, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (25, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (26, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (26, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (27, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (27, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (28, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (28, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (29, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (29, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (30, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (30, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (31, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (31, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (32, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (32, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (33, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (33, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (34, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (34, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (35, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (35, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (36, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (36, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (37, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (37, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (38, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (38, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (39, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (39, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (40, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (40, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (41, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (41, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (42, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (42, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (43, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (43, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (44, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (44, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (45, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (45, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (46, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (46, 3, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (47, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (47, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (48, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (48, 4, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (49, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (49, 2, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (50, 1, DEFAULT);
INSERT INTO `prod_8`.`category_product` (`product_id`, `category_id`, `last_update`) VALUES (50, 3, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `prod_8`.`group_user`
-- -----------------------------------------------------
START TRANSACTION;
USE `prod_8`;
INSERT INTO `prod_8`.`group_user` (`user_id`, `group_id`, `last_update`) VALUES (1, 1, DEFAULT);
INSERT INTO `prod_8`.`group_user` (`user_id`, `group_id`, `last_update`) VALUES (2, 2, DEFAULT);

COMMIT;

