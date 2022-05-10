-- MySQL dump 10.13  Distrib 5.7.33, for Win64 (x86_64)
--
-- Host: localhost    Database: householdaccounting_v1
-- ------------------------------------------------------
-- Server version	8.0.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `voucher-type`
--

DROP TABLE IF EXISTS `voucher-type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voucher-type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `code` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `active` tinyint DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher-type`
--

LOCK TABLES `voucher-type` WRITE;
/*!40000 ALTER TABLE `voucher-type` DISABLE KEYS */;
INSERT INTO `voucher-type` VALUES (2,'bill','01',1);
/*!40000 ALTER TABLE `voucher-type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;



--
-- Table structure for table `bill`
--

DROP TABLE IF EXISTS `bill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `buyer`
--

DROP TABLE IF EXISTS `buyer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buyer`
--


-- LOCK TABLES `buyer` WRITE;
/*!40000 ALTER TABLE `buyer` DISABLE KEYS */;
-- INSERT INTO `buyer` VALUES (1,'buyer test','0123456789',1);
/*!40000 ALTER TABLE `buyer` ENABLE KEYS */;
-- UNLOCK TABLES;

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-03-28 20:43:51
DROP TABLE IF EXISTS `bill_detail`;
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

DROP TABLE IF EXISTS `deductible`;
CREATE TABLE `deductible` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `active` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) );


DROP TABLE IF EXISTS `bill_deductible`;
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
    REFERENCES `householdaccounting_test`.`bill` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `bill_deductible_deductible_fk`
    FOREIGN KEY (`deductibleId`)
    REFERENCES `householdaccounting_test`.`deductible` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE);
