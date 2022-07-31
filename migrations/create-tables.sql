DROP TABLE IF EXISTS `bill_detail_deductible`;
DROP TABLE IF EXISTS `bill_detail_expense`;
DROP TABLE IF EXISTS `bill_deductible`;
DROP TABLE IF EXISTS `bill_expense`;
DROP TABLE  IF EXISTS `bill_additional_information`;
DROP TABLE IF EXISTS `bill_detail`;
DROP TABLE IF EXISTS `bill`;
DROP TABLE IF EXISTS `store`;
DROP TABLE IF EXISTS `buyer`;
DROP TABLE IF EXISTS `voucher-type`;
DROP TABLE IF EXISTS `deductible`;
DROP TABLE IF EXISTS `expense`;
--
-- Table structure for table `voucher-type`
--


CREATE TABLE `voucher-type` (
                                `id` int unsigned NOT NULL AUTO_INCREMENT,
                                `name` varchar(45) NOT NULL,
                                `code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
                                `active` tinyint DEFAULT '1',
                                PRIMARY KEY (`id`),
                                UNIQUE KEY `id_UNIQUE` (`id`),
                                UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
--
-- Table structure for table `buyer`
--


CREATE TABLE `buyer` (
                         `id` int unsigned NOT NULL AUTO_INCREMENT,
                         `name` varchar(45) NOT NULL,
                         `identification` char(13) DEFAULT NULL,
                         `identificationType` CHAR(2) NULL,
                         `active` tinyint DEFAULT '1',
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `id_UNIQUE` (`id`),
                         UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `store`
--


CREATE TABLE `store` (
                         `id` int unsigned NOT NULL AUTO_INCREMENT,
                         `businessName` varchar(255) NOT NULL,
                         `tradeName` varchar(255) NOT NULL,
                         `ruc` char(13) NOT NULL,
                         `parentAddress` varchar(45) DEFAULT NULL,
                         `active` tinyint DEFAULT '1',
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `id_UNIQUE` (`id`),
                         UNIQUE KEY `businessName_UNIQUE` (`businessName`),
                         UNIQUE KEY `tradeName_UNIQUE` (`tradeName`),
                         UNIQUE KEY `ruc_UNIQUE` (`ruc`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Table structure for table `bill`
--


CREATE TABLE `bill` (
                        `id` int unsigned NOT NULL AUTO_INCREMENT,
                        `accessKey` char(49) DEFAULT NULL,
                        `establishment` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
                        `emissionPoint` char(3) NOT NULL,
                        `secuential` char(9) NOT NULL,
                        `dateOfIssue` date NOT NULL,
                        `establishmentAddress` varchar(45) DEFAULT NULL,
                        `totalWithoutTax` decimal(9,2) NOT NULL,
                        `totalDiscount` decimal(9,2) DEFAULT NULL,
                        `tip` decimal(5,2) DEFAULT NULL,
                        `total` decimal(10,2) DEFAULT NULL,
                        `filePath` varchar(45) DEFAULT NULL,
                        `active` tinyint(1) DEFAULT '1',
                        `voucherTypeId` int unsigned NOT NULL,
                        `buyerId` int unsigned NOT NULL,
                        `storeId` int unsigned NOT NULL,
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `id_UNIQUE` (`id`),
                        UNIQUE KEY `billNumber_UNIQUE` (`establishment`,`emissionPoint`,`secuential`) USING BTREE,
                        UNIQUE KEY `accessKey_UNIQUE` (`accessKey`),
                        KEY `bill_buyer_fk` (`buyerId`),
                        KEY `bill_store_fk` (`storeId`),
                        KEY `bill_voucherType_fk` (`voucherTypeId`),
                        CONSTRAINT `bill_buyer_fk` FOREIGN KEY (`buyerId`) REFERENCES `buyer` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                        CONSTRAINT `bill_store_fk` FOREIGN KEY (`storeId`) REFERENCES `store` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                        CONSTRAINT `bill_voucherType_fk` FOREIGN KEY (`voucherTypeId`) REFERENCES `voucher-type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE `bill_detail` (
                               `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                               `billId` INT UNSIGNED NOT NULL,
                               `mainCode` VARCHAR(25) NULL,
                               `description` VARCHAR(300) NOT NULL,
                               `quantity` DECIMAL(14,2) NOT NULL,
                               `unitPrice` DECIMAL(14,4) NOT NULL,
                               `discount` DECIMAL(14,4) NULL,
                               `totalPriceWithoutTaxes` DECIMAL(14,4) NOT NULL,
                               `active` tinyint DEFAULT '1',
                               PRIMARY KEY (`id`),
                               UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
                               INDEX `fk_Bill_Detail_Bill_idx` (`billId` ASC) VISIBLE,
                               CONSTRAINT `fk_Bill_Detail_Bill`
                                   FOREIGN KEY (`billId`)
                                       REFERENCES `bill` (`id`)
                                       ON DELETE CASCADE
                                       ON UPDATE CASCADE);


CREATE TABLE `deductible` (
                              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                              `name` VARCHAR(45) NULL,
                              `active` TINYINT NOT NULL DEFAULT 1,
                              PRIMARY KEY (`id`),
                              UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
                              UNIQUE INDEX `name_UNIQUE` (`name` ASC) );



CREATE TABLE `bill_deductible` (
                                   `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                   `value` DECIMAL(14,2) NOT NULL,
                                   `billId` INT UNSIGNED NULL,
                                   `deductibleId` INT UNSIGNED NULL,
                                   `active` TINYINT NULL DEFAULT 1,
                                   PRIMARY KEY (`id`),
                                   UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
                                   INDEX `bill_deductible_bill_fk_idx` (`billId` ASC) ,
                                   INDEX `bill_deductible_deductible_fk_idx` (`deductibleId` ASC) ,
                                   CONSTRAINT `bill_deductible_bill_fk`
                                       FOREIGN KEY (`billId`)
                                           REFERENCES `bill` (`id`)
                                           ON DELETE CASCADE
                                           ON UPDATE CASCADE,
                                   CONSTRAINT `bill_deductible_deductible_fk`
                                       FOREIGN KEY (`deductibleId`)
                                           REFERENCES `deductible` (`id`)
                                           ON DELETE RESTRICT
                                           ON UPDATE CASCADE);


CREATE TABLE `bill_additional_information` (
                                               `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                               `billId` INT UNSIGNED NOT NULL,
                                               `name` VARCHAR(300) NOT NULL,
                                               `value` VARCHAR(300) NOT NULL,
                                               `active` TINYINT NOT NULL DEFAULT 1,
                                               PRIMARY KEY (`id`),
                                               INDEX `bill_additional_information_bill_idx` (`billId` ASC) VISIBLE,
                                               CONSTRAINT `bill_additional_information_bill`
                                                   FOREIGN KEY (`billId`)
                                                       REFERENCES `bill` (`id`)
                                                       ON DELETE CASCADE
                                                       ON UPDATE CASCADE);


CREATE TABLE `expense` (
                           `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                           `name` VARCHAR(45) NULL,
                           `active` TINYINT NOT NULL DEFAULT 1,
                           PRIMARY KEY (`id`),
                           UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
                           UNIQUE INDEX `name_UNIQUE` (`name` ASC) );



CREATE TABLE `bill_expense` (
                                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `value` DECIMAL(14,2) NOT NULL,
                                `billId` INT UNSIGNED NULL,
                                `expenseId` INT UNSIGNED NULL,
                                `active` TINYINT NULL DEFAULT 1,
                                PRIMARY KEY (`id`),
                                UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
                                INDEX `bill_expense_bill_fk_idx` (`billId` ASC) ,
                                INDEX `bill_expense_expense_fk_idx` (`expenseId` ASC) ,
                                CONSTRAINT `bill_expense_bill_fk`
                                    FOREIGN KEY (`billId`)
                                        REFERENCES `bill` (`id`)
                                        ON DELETE CASCADE
                                        ON UPDATE CASCADE,
                                CONSTRAINT `bill_expense_deductible_fk`
                                    FOREIGN KEY (`expenseId`)
                                        REFERENCES `expense` (`id`)
                                        ON DELETE RESTRICT
                                        ON UPDATE CASCADE);


CREATE TABLE `bill_detail_expense` (
                                       `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                       `billDetailId` INT UNSIGNED NOT NULL,
                                       `expenseId` INT UNSIGNED NOT NULL,
                                       `value` DECIMAL(14,2) NOT NULL,
                                       `active` TINYINT NOT NULL DEFAULT 1,
                                       PRIMARY KEY (`id`),
                                       UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
                                       UNIQUE INDEX `uq_bill_detail_expense_billDetailIdExpenseId` (`billDetailId` ASC, `expenseId` ASC) VISIBLE,
                                       INDEX `fk_bill_detail_expense_expense_idx` (`expenseId` ASC) VISIBLE,
                                       CONSTRAINT `fk_bill_detail_expense_bill_detail`
                                           FOREIGN KEY (`billDetailId`)
                                               REFERENCES bill_detail(`id`)
                                               ON DELETE CASCADE
                                               ON UPDATE CASCADE,
                                       CONSTRAINT `fk_bill_detail_expense_expense`
                                           FOREIGN KEY (`expenseId`)
                                               REFERENCES `expense` (`id`)
                                               ON DELETE RESTRICT
                                               ON UPDATE CASCADE);