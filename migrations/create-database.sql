CREATE TABLE `bill` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`accessKey` CHAR(49) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`establishment` CHAR(3) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`emissionPoint` CHAR(3) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`secuential` CHAR(9) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`dateOfIssue` DATE NOT NULL,
	`establishmentAddress` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`totalWithoutTax` DECIMAL(9,2) NOT NULL,
	`totalDiscount` DECIMAL(9,2) NULL DEFAULT NULL,
	`tip` DECIMAL(5,2) NULL DEFAULT NULL,
	`total` DECIMAL(10,2) NULL DEFAULT NULL,
	`filePath` VARCHAR(45) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`active` TINYINT(1) NULL DEFAULT '1',
	`voucherTypeId` INT(10) UNSIGNED NOT NULL,
	`buyerId` INT(10) UNSIGNED NOT NULL,
	`storeId` INT(10) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `id_UNIQUE` (`id`) USING BTREE,
	UNIQUE INDEX `billNumber_UNIQUE` (`establishment`, `emissionPoint`, `secuential`) USING BTREE,
	UNIQUE INDEX `accessKey_UNIQUE` (`accessKey`) USING BTREE,
	INDEX `bill_buyer_fk` (`buyerId`) USING BTREE,
	INDEX `bill_store_fk` (`storeId`) USING BTREE,
	INDEX `bill_voucherType_fk` (`voucherTypeId`) USING BTREE,
	CONSTRAINT `bill_buyer_fk` FOREIGN KEY (`buyerId`) REFERENCES `householdaccounting_v1`.`buyer` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT `bill_store_fk` FOREIGN KEY (`storeId`) REFERENCES `householdaccounting_v1`.`store` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT `bill_voucherType_fk` FOREIGN KEY (`voucherTypeId`) REFERENCES `householdaccounting_v1`.`voucher-type` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=8
;



