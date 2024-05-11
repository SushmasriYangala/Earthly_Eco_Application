-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema yangal56
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema yangal56
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `yangal56` DEFAULT CHARACTER SET utf8 ;
USE `yangal56` ;

-- -----------------------------------------------------
-- Table `yangal56`.`User_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`User_EEA` (
  `UserID` INT NOT NULL,
  `Username` VARCHAR(255) NULL,
  `Email` VARCHAR(255) NULL,
  `Password` VARCHAR(255) NULL,
  `Address` VARCHAR(255) NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE INDEX `Username_UNIQUE` (`Username` ASC) VISIBLE,
  UNIQUE INDEX `Email_UNIQUE` (`Email` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`Admin_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`Admin_EEA` (
  `AdminID` INT NOT NULL,
  `Username` VARCHAR(255) NULL,
  `Email` VARCHAR(255) NULL,
  `Password` VARCHAR(255) NULL,
  UNIQUE INDEX `Username_UNIQUE` (`Username` ASC) VISIBLE,
  UNIQUE INDEX `Email_UNIQUE` (`Email` ASC) VISIBLE,
  PRIMARY KEY (`AdminID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`Product_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`Product_EEA` (
  `ProductID` INT NOT NULL,
  `Name` VARCHAR(255) NULL,
  `Description` TEXT NULL,
  `Price` DECIMAL(10,2) NULL,
  `Category` VARCHAR(255) NULL,
  `Image` VARCHAR(255) NULL,
  PRIMARY KEY (`ProductID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`Orders_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`Orders_EEA` (
  `OrderID` INT NOT NULL,
  `UserID` INT NULL,
  `OrderDate` DATETIME NULL,
  `TotalAmount` DECIMAL(10,2) NULL,
  `Status` VARCHAR(50) NULL,
  PRIMARY KEY (`OrderID`),
  INDEX `fk_user_id_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`UserID`)
    REFERENCES `yangal56`.`User_EEA` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`CartItem_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`CartItem_EEA` (
  `CartItemID` INT NOT NULL,
  `UserID` INT NULL,
  `ProductID` INT NULL,
  `Quantity` INT NULL,
  PRIMARY KEY (`CartItemID`),
  INDEX `fk_user_id_idx` (`UserID` ASC) VISIBLE,
  INDEX `fk_product_id_idx` (`ProductID` ASC) VISIBLE,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`UserID`)
    REFERENCES `yangal56`.`User_EEA` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_id`
    FOREIGN KEY (`ProductID`)
    REFERENCES `yangal56`.`Product_EEA` (`ProductID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`Review_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`Review_EEA` (
  `ReviewID` INT NOT NULL,
  `UserID` INT NULL,
  `ProductID` INT NULL,
  `Rating` INT NULL,
  `Comment` LONGTEXT NULL,
  PRIMARY KEY (`ReviewID`),
  UNIQUE INDEX `ReviewID_UNIQUE` (`ReviewID` ASC) VISIBLE,
  INDEX `fk_product_id_idx` (`ProductID` ASC) VISIBLE,
  INDEX `fk_user_id_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`UserID`)
    REFERENCES `yangal56`.`User_EEA` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_id`
    FOREIGN KEY (`ProductID`)
    REFERENCES `yangal56`.`Product_EEA` (`ProductID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`Wishlist_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`Wishlist_EEA` (
  `wishlistItemID` INT NOT NULL,
  `UserID` INT NULL,
  `ProductID` INT NULL,
  PRIMARY KEY (`wishlistItemID`),
  INDEX `fk_user_id_idx` (`UserID` ASC) VISIBLE,
  INDEX `fk_product_id_idx` (`ProductID` ASC) VISIBLE,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`UserID`)
    REFERENCES `yangal56`.`User_EEA` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_id`
    FOREIGN KEY (`ProductID`)
    REFERENCES `yangal56`.`Product_EEA` (`ProductID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`Inventory_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`Inventory_EEA` (
  `Quantity` INT NOT NULL,
  `ProductID` INT NOT NULL,
  PRIMARY KEY (`ProductID`),
  CONSTRAINT `fk_product_id`
    FOREIGN KEY (`ProductID`)
    REFERENCES `yangal56`.`Product_EEA` (`ProductID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`UserFeedback_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`UserFeedback_EEA` (
  `FeedbackID` INT NOT NULL,
  `UserID` INT NULL,
  `FeedbackType` VARCHAR(45) NULL,
  `FeedbackText` LONGTEXT NULL,
  `Timestamp` TIMESTAMP NULL,
  PRIMARY KEY (`FeedbackID`),
  INDEX `fk_user_id_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`UserID`)
    REFERENCES `yangal56`.`User_EEA` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`OrderItem_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`OrderItem_EEA` (
  `OrderItemID` INT NOT NULL,
  `OrderID` INT NULL,
  `ProductID` INT NULL,
  `Quantity` INT NULL,
  `UnitPrice` DECIMAL(10,2) NULL,
  PRIMARY KEY (`OrderItemID`),
  INDEX `fk_order_id_idx` (`OrderID` ASC) VISIBLE,
  INDEX `fk_product_id_idx` (`ProductID` ASC) VISIBLE,
  CONSTRAINT `fk_order_id`
    FOREIGN KEY (`OrderID`)
    REFERENCES `yangal56`.`Orders_EEA` (`OrderID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_id`
    FOREIGN KEY (`ProductID`)
    REFERENCES `yangal56`.`Product_EEA` (`ProductID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`PaymentMethod_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`PaymentMethod_EEA` (
  `PaymentMethodID` INT NOT NULL,
  `UserID` INT NULL,
  `Type` VARCHAR(45) NULL,
  `Details` TEXT NULL,
  PRIMARY KEY (`PaymentMethodID`),
  INDEX `fk_user_id_idx` (`UserID` ASC) VISIBLE,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`UserID`)
    REFERENCES `yangal56`.`User_EEA` (`UserID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`Supplier_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`Supplier_EEA` (
  `SupplierID` INT NOT NULL,
  `Name` VARCHAR(255) NULL,
  `Email` VARCHAR(255) NULL,
  `Password` VARCHAR(45) NULL,
  `Address` VARCHAR(255) NULL,
  PRIMARY KEY (`SupplierID`),
  UNIQUE INDEX `Email_UNIQUE` (`Email` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `yangal56`.`SupplierProduct_EEA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `yangal56`.`SupplierProduct_EEA` (
  `SupplierProductID` INT NOT NULL,
  `ProductID` INT NULL,
  `SupplierID` INT NULL,
  PRIMARY KEY (`SupplierProductID`),
  INDEX `fk_product_id_idx` (`ProductID` ASC) VISIBLE,
  INDEX `fk_supplier_id_idx` (`SupplierID` ASC) VISIBLE,
  CONSTRAINT `fk_product_id`
    FOREIGN KEY (`ProductID`)
    REFERENCES `yangal56`.`Product_EEA` (`ProductID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_supplier_id`
    FOREIGN KEY (`SupplierID`)
    REFERENCES `yangal56`.`Supplier_EEA` (`SupplierID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
