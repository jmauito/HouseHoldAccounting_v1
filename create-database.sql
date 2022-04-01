CREATE SCHEMA `householdaccounting_v1` ;

CREATE TABLE `householdaccounting_v1`.`voucher-type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `code` CHAR(1) NULL,
  `active` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE);

CREATE TABLE `householdaccounting_v1`.`buyer` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `identification` CHAR(13) NULL,
  `active` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE);

CREATE TABLE `householdaccounting_v1`.`store` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `businessName` VARCHAR(45) NOT NULL,
  `tradeName` VARCHAR(45) NOT NULL,
  `ruc` CHAR(13) NOT NULL,
  `parentAddress` VARCHAR(45) NULL,
  `active` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `businessName_UNIQUE` (`businessName` ASC) VISIBLE,
  UNIQUE INDEX `tradeName_UNIQUE` (`tradeName` ASC) VISIBLE,
  UNIQUE INDEX `ruc_UNIQUE` (`ruc` ASC) VISIBLE);

CREATE TABLE `householdaccounting_v1`.`bill` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `accessKey` CHAR(49) NULL,
  `estabilshment` CHAR(3) NOT NULL,
  `emissionPoint` CHAR(3) NOT NULL,
  `secuential` CHAR(9) NOT NULL,
  `dateOfIssue` DATE NOT NULL,
  `establishmentAddress` VARCHAR(45) NULL,
  `totalWithoutTax` DECIMAL(9,2) NOT NULL,
  `totalDiscount` DECIMAL(9,2) NULL,
  `tip` DECIMAL(5,2) NULL,
  `total` DECIMAL(10,2) NULL,
  `filePath` VARCHAR(45) NULL,
  `active` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  UNIQUE INDEX `accessKey_UNIQUE` (`accessKey` ASC) VISIBLE,
  UNIQUE INDEX `billNumber_UNIQUE` (`estabilshment` ASC, `emissionPoint` ASC, `secuential` ASC) VISIBLE);

ALTER TABLE `householdaccounting_v1`.`bill` 
ADD COLUMN `voucherTypeId` INT UNSIGNED NOT NULL AFTER `active`,
ADD COLUMN `buyerId` INT UNSIGNED NOT NULL AFTER `voucherTypeId`,
ADD COLUMN `storeId` INT UNSIGNED NOT NULL AFTER `buyerId`;
ALTER TABLE `householdaccounting_v1`.`bill` 
ADD CONSTRAINT `bill_voucherType_fk`
  FOREIGN KEY (`id`)
  REFERENCES `householdaccounting_v1`.`voucher-type` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE,
ADD CONSTRAINT `bill_buyer_fk`
  FOREIGN KEY (`id`)
  REFERENCES `householdaccounting_v1`.`buyer` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE,
ADD CONSTRAINT `bill_store_fk`
  FOREIGN KEY (`id`)
  REFERENCES `householdaccounting_v1`.`store` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;
