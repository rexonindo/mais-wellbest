CREATE DATABASE  IF NOT EXISTS `mais_wellbest` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `mais_wellbest`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: mais_wellbest
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('mais-wellbest-cache-livewire-rate-limiter:a5953fb5e1c86b5792734a0b4775a77519f2794f','i:1;',1761469892),('mais-wellbest-cache-livewire-rate-limiter:a5953fb5e1c86b5792734a0b4775a77519f2794f:timer','i:1761469892;',1761469892);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `current_production_qty_view`
--

DROP TABLE IF EXISTS `current_production_qty_view`;
/*!50001 DROP VIEW IF EXISTS `current_production_qty_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `current_production_qty_view` AS SELECT 
 1 AS `pyear`,
 1 AS `pmonth`,
 1 AS `ttl_plan_qty`,
 1 AS `ttl_out_qty`,
 1 AS `ttl_os_qty`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `current_total_machine_view`
--

DROP TABLE IF EXISTS `current_total_machine_view`;
/*!50001 DROP VIEW IF EXISTS `current_total_machine_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `current_total_machine_view` AS SELECT 
 1 AS `ttl_machine`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `current_total_product_view`
--

DROP TABLE IF EXISTS `current_total_product_view`;
/*!50001 DROP VIEW IF EXISTS `current_total_product_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `current_total_product_view` AS SELECT 
 1 AS `ttl_product`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `dept_tbl`
--

DROP TABLE IF EXISTS `dept_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dept_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dept_cd` varchar(20) NOT NULL,
  `dept_nm` varchar(100) NOT NULL,
  `descrp` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dept_cd` (`dept_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dept_tbl`
--

LOCK TABLES `dept_tbl` WRITE;
/*!40000 ALTER TABLE `dept_tbl` DISABLE KEYS */;
INSERT INTO `dept_tbl` VALUES (1,'PPIC','PRODUCTION PLANING & INVENTORY CONTROL',NULL,'2025-10-18 08:06:33','2025-10-18 08:06:33',NULL,NULL),(2,'PRD','PRODUCTION',NULL,'2025-10-18 08:07:05','2025-10-18 08:07:05',NULL,NULL),(3,'QC','QUALITY CONTROL',NULL,'2025-10-18 08:07:20','2025-10-18 08:07:20',NULL,NULL),(6,'HRD','HUMAN RESOURCE AND DEVELOPMENT',NULL,'2025-10-24 18:11:57','2025-10-24 18:11:57','rexon','rexon');
/*!40000 ALTER TABLE `dept_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empl_tbl`
--

DROP TABLE IF EXISTS `empl_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empl_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(20) NOT NULL,
  `emp_nm` varchar(100) NOT NULL,
  `psition` varchar(100) DEFAULT NULL,
  `dept_cd` varchar(20) DEFAULT NULL,
  `shift_cd` varchar(20) DEFAULT NULL,
  `stats` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_id` (`emp_id`),
  KEY `dept_cd` (`dept_cd`),
  KEY `shift_cd` (`shift_cd`),
  CONSTRAINT `empl_tbl_ibfk_1` FOREIGN KEY (`dept_cd`) REFERENCES `dept_tbl` (`dept_cd`),
  CONSTRAINT `empl_tbl_ibfk_2` FOREIGN KEY (`shift_cd`) REFERENCES `shift_tbl` (`shift_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empl_tbl`
--

LOCK TABLES `empl_tbl` WRITE;
/*!40000 ALTER TABLE `empl_tbl` DISABLE KEYS */;
INSERT INTO `empl_tbl` VALUES (1,'1001','AGUS','LEADER','PRD','N','Active','2025-10-23 10:45:51','2025-10-23 11:31:45',NULL,NULL),(2,'1002','IWAN','OPERATOR','PRD','N','Active','2025-10-23 10:47:37','2025-10-26 05:06:14',NULL,'rexon'),(3,'1003','BAMBANG','OPERATOR','PRD','N','Active','2025-10-24 18:21:52','2025-10-26 05:06:31','rexon','rexon'),(4,'1004','FIRMAN','OPERATOR','PRD','N','Active','2025-10-26 05:07:39','2025-10-26 05:07:39','rexon','rexon');
/*!40000 ALTER TABLE `empl_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invmov_tbl`
--

DROP TABLE IF EXISTS `invmov_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invmov_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `loc_cd` varchar(50) NOT NULL,
  `itm_cd` varchar(50) NOT NULL,
  `mov_type` enum('IN','OUT') DEFAULT NULL,
  `qty` float DEFAULT NULL,
  `ref_type` varchar(50) DEFAULT NULL,
  `ref_id` int DEFAULT NULL,
  `mov_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itm_cd` (`itm_cd`),
  CONSTRAINT `invmov_tbl_ibfk_1` FOREIGN KEY (`itm_cd`) REFERENCES `itm_tbl` (`itm_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invmov_tbl`
--

LOCK TABLES `invmov_tbl` WRITE;
/*!40000 ALTER TABLE `invmov_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `invmov_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `itm_tbl`
--

DROP TABLE IF EXISTS `itm_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `itm_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `itm_cd` varchar(50) NOT NULL,
  `itm_nm` varchar(100) NOT NULL,
  `itm_type` varchar(50) DEFAULT NULL,
  `fg_flg` bit(1) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `std_rate` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `itm_cd` (`itm_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `itm_tbl`
--

LOCK TABLES `itm_tbl` WRITE;
/*!40000 ALTER TABLE `itm_tbl` DISABLE KEYS */;
INSERT INTO `itm_tbl` VALUES (1,'S-62098-D','PCB HSP-B203AA','SINGLE SIDE CARBON',_binary '','PCS',1000,'2025-10-18 08:10:50','2025-10-18 08:57:07',NULL,NULL),(2,'D-13046-C1','PCB ER-2830W ANT 515-05-030-10','SINGLE SIDE REGULER PCB - FINISH ROUTING',_binary '','PCS',1000,'2025-10-18 08:12:24','2025-10-23 09:25:34',NULL,NULL),(3,'S-50204-B','PCB PBAFA1015A','SINGLE SIDE CARBON',_binary '','PCS',1000,'2025-10-18 08:13:26','2025-10-24 18:24:28',NULL,'rexon');
/*!40000 ALTER TABLE `itm_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mchn_tbl`
--

DROP TABLE IF EXISTS `mchn_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mchn_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mchn_cd` varchar(50) NOT NULL,
  `mchn_nm` varchar(100) DEFAULT NULL,
  `dept_cd` varchar(20) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `dsc` varchar(50) DEFAULT NULL,
  `stats` enum('Running','Idle','Maintenance','Breakdown') DEFAULT 'Idle',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mchn_cd` (`mchn_cd`),
  KEY `dept_cd` (`dept_cd`),
  CONSTRAINT `mchn_tbl_ibfk_1` FOREIGN KEY (`dept_cd`) REFERENCES `dept_tbl` (`dept_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mchn_tbl`
--

LOCK TABLES `mchn_tbl` WRITE;
/*!40000 ALTER TABLE `mchn_tbl` DISABLE KEYS */;
INSERT INTO `mchn_tbl` VALUES (1,'M-02CL3','AutoCut Sheet Laminator','PRD','UN','A3','Running','2025-10-23 10:20:43','2025-10-23 10:20:43',NULL,NULL),(2,'M-02LMTX','Mesin Laminating','PRD','SN','A3','Running','2025-10-23 10:21:37','2025-10-23 10:21:37',NULL,NULL),(3,'M-02PFL','MTH Protective Film Luminator','PRD','UN','A3','Running','2025-10-23 10:22:51','2025-10-23 10:22:51',NULL,NULL);
/*!40000 ALTER TABLE `mchn_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mchndown_tbl`
--

DROP TABLE IF EXISTS `mchndown_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mchndown_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mchn_cd` varchar(50) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `reason` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mchn_cd` (`mchn_cd`),
  CONSTRAINT `mchndown_tbl_ibfk_1` FOREIGN KEY (`mchn_cd`) REFERENCES `mchn_tbl` (`mchn_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mchndown_tbl`
--

LOCK TABLES `mchndown_tbl` WRITE;
/*!40000 ALTER TABLE `mchndown_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `mchndown_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=359 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (141,'2025_10_18_145305_create_cache_table',0),(142,'2025_10_18_145305_create_cache_locks_table',0),(143,'2025_10_18_145305_create_dept_tbl_table',0),(144,'2025_10_18_145305_create_empl_tbl_table',0),(145,'2025_10_18_145305_create_failed_jobs_table',0),(146,'2025_10_18_145305_create_invmov_tbl_table',0),(147,'2025_10_18_145305_create_itm_tbl_table',0),(148,'2025_10_18_145305_create_jobs_table',0),(149,'2025_10_18_145305_create_mchn_tbl_table',0),(150,'2025_10_18_145305_create_mchndown_tbl_table',0),(151,'2025_10_18_145305_create_menus_table',0),(152,'2025_10_18_145305_create_password_reset_tokens_table',0),(153,'2025_10_18_145305_create_prdlog_tbl_table',0),(154,'2025_10_18_145305_create_prdroute_tbl_table',0),(155,'2025_10_18_145305_create_proc_tbl_table',0),(156,'2025_10_18_145305_create_qualinsp_tbl_table',0),(157,'2025_10_18_145305_create_sessions_table',0),(158,'2025_10_18_145305_create_shift_tbl_table',0),(159,'2025_10_18_145305_create_user_logs_table',0),(160,'2025_10_18_145305_create_users_table',0),(161,'2025_10_18_145305_create_wo_tbl_table',0),(162,'2025_10_18_145308_add_foreign_keys_to_empl_tbl_table',0),(163,'2025_10_18_145308_add_foreign_keys_to_invmov_tbl_table',0),(164,'2025_10_18_145308_add_foreign_keys_to_mchn_tbl_table',0),(165,'2025_10_18_145308_add_foreign_keys_to_mchndown_tbl_table',0),(166,'2025_10_18_145308_add_foreign_keys_to_prdlog_tbl_table',0),(167,'2025_10_18_145308_add_foreign_keys_to_prdroute_tbl_table',0),(168,'2025_10_18_145308_add_foreign_keys_to_proc_tbl_table',0),(169,'2025_10_18_145308_add_foreign_keys_to_qualinsp_tbl_table',0),(170,'2025_10_18_145308_add_foreign_keys_to_wo_tbl_table',0),(171,'2025_10_19_062811_create_cache_table',0),(172,'2025_10_19_062811_create_cache_locks_table',0),(173,'2025_10_19_062811_create_dept_tbl_table',0),(174,'2025_10_19_062811_create_empl_tbl_table',0),(175,'2025_10_19_062811_create_failed_jobs_table',0),(176,'2025_10_19_062811_create_invmov_tbl_table',0),(177,'2025_10_19_062811_create_itm_tbl_table',0),(178,'2025_10_19_062811_create_jobs_table',0),(179,'2025_10_19_062811_create_mchn_tbl_table',0),(180,'2025_10_19_062811_create_mchndown_tbl_table',0),(181,'2025_10_19_062811_create_menus_table',0),(182,'2025_10_19_062811_create_password_reset_tokens_table',0),(183,'2025_10_19_062811_create_prdlog_tbl_table',0),(184,'2025_10_19_062811_create_prdroute_tbl_table',0),(185,'2025_10_19_062811_create_proc_tbl_table',0),(186,'2025_10_19_062811_create_qualinsp_tbl_table',0),(187,'2025_10_19_062811_create_sessions_table',0),(188,'2025_10_19_062811_create_shift_tbl_table',0),(189,'2025_10_19_062811_create_user_logs_table',0),(190,'2025_10_19_062811_create_users_table',0),(191,'2025_10_19_062811_create_wo_tbl_table',0),(192,'2025_10_19_062814_add_foreign_keys_to_empl_tbl_table',0),(193,'2025_10_19_062814_add_foreign_keys_to_invmov_tbl_table',0),(194,'2025_10_19_062814_add_foreign_keys_to_itm_tbl_table',0),(195,'2025_10_19_062814_add_foreign_keys_to_mchn_tbl_table',0),(196,'2025_10_19_062814_add_foreign_keys_to_mchndown_tbl_table',0),(197,'2025_10_19_062814_add_foreign_keys_to_prdlog_tbl_table',0),(198,'2025_10_19_062814_add_foreign_keys_to_prdroute_tbl_table',0),(199,'2025_10_19_062814_add_foreign_keys_to_proc_tbl_table',0),(200,'2025_10_19_062814_add_foreign_keys_to_qualinsp_tbl_table',0),(201,'2025_10_19_062814_add_foreign_keys_to_wo_tbl_table',0),(202,'2025_10_22_215634_create_cache_table',0),(203,'2025_10_22_215634_create_cache_locks_table',0),(204,'2025_10_22_215634_create_dept_tbl_table',0),(205,'2025_10_22_215634_create_empl_tbl_table',0),(206,'2025_10_22_215634_create_failed_jobs_table',0),(207,'2025_10_22_215634_create_invmov_tbl_table',0),(208,'2025_10_22_215634_create_itm_tbl_table',0),(209,'2025_10_22_215634_create_jobs_table',0),(210,'2025_10_22_215634_create_mchn_tbl_table',0),(211,'2025_10_22_215634_create_mchndown_tbl_table',0),(212,'2025_10_22_215634_create_menus_table',0),(213,'2025_10_22_215634_create_password_reset_tokens_table',0),(214,'2025_10_22_215634_create_prdlog_tbl_table',0),(215,'2025_10_22_215634_create_prdroute_tbl_table',0),(216,'2025_10_22_215634_create_proc_tbl_table',0),(217,'2025_10_22_215634_create_qualinsp_tbl_table',0),(218,'2025_10_22_215634_create_sessions_table',0),(219,'2025_10_22_215634_create_shift_tbl_table',0),(220,'2025_10_22_215634_create_user_logs_table',0),(221,'2025_10_22_215634_create_users_table',0),(222,'2025_10_22_215634_create_wo_tbl_table',0),(223,'2025_10_22_215637_add_foreign_keys_to_empl_tbl_table',0),(224,'2025_10_22_215637_add_foreign_keys_to_invmov_tbl_table',0),(225,'2025_10_22_215637_add_foreign_keys_to_mchn_tbl_table',0),(226,'2025_10_22_215637_add_foreign_keys_to_mchndown_tbl_table',0),(227,'2025_10_22_215637_add_foreign_keys_to_prdlog_tbl_table',0),(228,'2025_10_22_215637_add_foreign_keys_to_prdroute_tbl_table',0),(229,'2025_10_22_215637_add_foreign_keys_to_proc_tbl_table',0),(230,'2025_10_22_215637_add_foreign_keys_to_qualinsp_tbl_table',0),(231,'2025_10_22_215637_add_foreign_keys_to_wo_tbl_table',0),(232,'2025_10_25_002442_create_cache_table',0),(233,'2025_10_25_002442_create_cache_locks_table',0),(234,'2025_10_25_002442_create_dept_tbl_table',0),(235,'2025_10_25_002442_create_empl_tbl_table',0),(236,'2025_10_25_002442_create_failed_jobs_table',0),(237,'2025_10_25_002442_create_invmov_tbl_table',0),(238,'2025_10_25_002442_create_itm_tbl_table',0),(239,'2025_10_25_002442_create_jobs_table',0),(240,'2025_10_25_002442_create_mchn_tbl_table',0),(241,'2025_10_25_002442_create_mchndown_tbl_table',0),(242,'2025_10_25_002442_create_menus_table',0),(243,'2025_10_25_002442_create_password_reset_tokens_table',0),(244,'2025_10_25_002442_create_prdlog_tbl_table',0),(245,'2025_10_25_002442_create_prdroute_tbl_table',0),(246,'2025_10_25_002442_create_proc_tbl_table',0),(247,'2025_10_25_002442_create_qualinsp_tbl_table',0),(248,'2025_10_25_002442_create_sessions_table',0),(249,'2025_10_25_002442_create_shift_tbl_table',0),(250,'2025_10_25_002442_create_user_logs_table',0),(251,'2025_10_25_002442_create_users_table',0),(252,'2025_10_25_002442_create_wo_tbl_table',0),(253,'2025_10_25_002445_add_foreign_keys_to_empl_tbl_table',0),(254,'2025_10_25_002445_add_foreign_keys_to_invmov_tbl_table',0),(255,'2025_10_25_002445_add_foreign_keys_to_mchn_tbl_table',0),(256,'2025_10_25_002445_add_foreign_keys_to_mchndown_tbl_table',0),(257,'2025_10_25_002445_add_foreign_keys_to_prdlog_tbl_table',0),(258,'2025_10_25_002445_add_foreign_keys_to_prdroute_tbl_table',0),(259,'2025_10_25_002445_add_foreign_keys_to_proc_tbl_table',0),(260,'2025_10_25_002445_add_foreign_keys_to_qualinsp_tbl_table',0),(261,'2025_10_25_002445_add_foreign_keys_to_wo_tbl_table',0),(262,'2025_10_25_004512_create_cache_table',0),(263,'2025_10_25_004512_create_cache_locks_table',0),(264,'2025_10_25_004512_create_dept_tbl_table',0),(265,'2025_10_25_004512_create_empl_tbl_table',0),(266,'2025_10_25_004512_create_failed_jobs_table',0),(267,'2025_10_25_004512_create_invmov_tbl_table',0),(268,'2025_10_25_004512_create_itm_tbl_table',0),(269,'2025_10_25_004512_create_jobs_table',0),(270,'2025_10_25_004512_create_mchn_tbl_table',0),(271,'2025_10_25_004512_create_mchndown_tbl_table',0),(272,'2025_10_25_004512_create_menus_table',0),(273,'2025_10_25_004512_create_password_reset_tokens_table',0),(274,'2025_10_25_004512_create_prdlog_tbl_table',0),(275,'2025_10_25_004512_create_prdroute_tbl_table',0),(276,'2025_10_25_004512_create_proc_tbl_table',0),(277,'2025_10_25_004512_create_qualinsp_tbl_table',0),(278,'2025_10_25_004512_create_sessions_table',0),(279,'2025_10_25_004512_create_shift_tbl_table',0),(280,'2025_10_25_004512_create_user_logs_table',0),(281,'2025_10_25_004512_create_users_table',0),(282,'2025_10_25_004512_create_wo_tbl_table',0),(283,'2025_10_25_004515_add_foreign_keys_to_empl_tbl_table',0),(284,'2025_10_25_004515_add_foreign_keys_to_invmov_tbl_table',0),(285,'2025_10_25_004515_add_foreign_keys_to_mchn_tbl_table',0),(286,'2025_10_25_004515_add_foreign_keys_to_mchndown_tbl_table',0),(287,'2025_10_25_004515_add_foreign_keys_to_prdlog_tbl_table',0),(288,'2025_10_25_004515_add_foreign_keys_to_prdroute_tbl_table',0),(289,'2025_10_25_004515_add_foreign_keys_to_proc_tbl_table',0),(290,'2025_10_25_004515_add_foreign_keys_to_qualinsp_tbl_table',0),(291,'2025_10_25_004515_add_foreign_keys_to_wo_tbl_table',0),(292,'2025_10_26_023306_create_cache_table',0),(293,'2025_10_26_023306_create_cache_locks_table',0),(294,'2025_10_26_023306_create_dept_tbl_table',0),(295,'2025_10_26_023306_create_empl_tbl_table',0),(296,'2025_10_26_023306_create_failed_jobs_table',0),(297,'2025_10_26_023306_create_invmov_tbl_table',0),(298,'2025_10_26_023306_create_itm_tbl_table',0),(299,'2025_10_26_023306_create_jobs_table',0),(300,'2025_10_26_023306_create_mchn_tbl_table',0),(301,'2025_10_26_023306_create_mchndown_tbl_table',0),(302,'2025_10_26_023306_create_menus_table',0),(303,'2025_10_26_023306_create_password_reset_tokens_table',0),(304,'2025_10_26_023306_create_prdlog_tbl_table',0),(305,'2025_10_26_023306_create_prdroute_tbl_table',0),(306,'2025_10_26_023306_create_proc_tbl_table',0),(307,'2025_10_26_023306_create_qualinsp_tbl_table',0),(308,'2025_10_26_023306_create_sessions_table',0),(309,'2025_10_26_023306_create_shift_tbl_table',0),(310,'2025_10_26_023306_create_user_logs_table',0),(311,'2025_10_26_023306_create_users_table',0),(312,'2025_10_26_023306_create_wo_tbl_table',0),(313,'2025_10_26_023307_create_wo_progress_view_view',0),(314,'2025_10_26_023307_create_wo_status_view_view',0),(315,'2025_10_26_023309_add_foreign_keys_to_empl_tbl_table',0),(316,'2025_10_26_023309_add_foreign_keys_to_invmov_tbl_table',0),(317,'2025_10_26_023309_add_foreign_keys_to_mchn_tbl_table',0),(318,'2025_10_26_023309_add_foreign_keys_to_mchndown_tbl_table',0),(319,'2025_10_26_023309_add_foreign_keys_to_prdlog_tbl_table',0),(320,'2025_10_26_023309_add_foreign_keys_to_prdroute_tbl_table',0),(321,'2025_10_26_023309_add_foreign_keys_to_proc_tbl_table',0),(322,'2025_10_26_023309_add_foreign_keys_to_qualinsp_tbl_table',0),(323,'2025_10_26_023309_add_foreign_keys_to_wo_tbl_table',0),(324,'2025_10_26_175739_create_cache_table',0),(325,'2025_10_26_175739_create_cache_locks_table',0),(326,'2025_10_26_175739_create_dept_tbl_table',0),(327,'2025_10_26_175739_create_empl_tbl_table',0),(328,'2025_10_26_175739_create_failed_jobs_table',0),(329,'2025_10_26_175739_create_invmov_tbl_table',0),(330,'2025_10_26_175739_create_itm_tbl_table',0),(331,'2025_10_26_175739_create_jobs_table',0),(332,'2025_10_26_175739_create_mchn_tbl_table',0),(333,'2025_10_26_175739_create_mchndown_tbl_table',0),(334,'2025_10_26_175739_create_menus_table',0),(335,'2025_10_26_175739_create_password_reset_tokens_table',0),(336,'2025_10_26_175739_create_prdlog_tbl_table',0),(337,'2025_10_26_175739_create_prdroute_tbl_table',0),(338,'2025_10_26_175739_create_proc_tbl_table',0),(339,'2025_10_26_175739_create_qualinsp_tbl_table',0),(340,'2025_10_26_175739_create_sessions_table',0),(341,'2025_10_26_175739_create_shift_tbl_table',0),(342,'2025_10_26_175739_create_user_logs_table',0),(343,'2025_10_26_175739_create_users_table',0),(344,'2025_10_26_175739_create_wo_tbl_table',0),(345,'2025_10_26_175740_create_current_production_qty_view_view',0),(346,'2025_10_26_175740_create_current_total_machine_view_view',0),(347,'2025_10_26_175740_create_current_total_product_view_view',0),(348,'2025_10_26_175740_create_wo_progress_view_view',0),(349,'2025_10_26_175740_create_wo_status_view_view',0),(350,'2025_10_26_175742_add_foreign_keys_to_empl_tbl_table',0),(351,'2025_10_26_175742_add_foreign_keys_to_invmov_tbl_table',0),(352,'2025_10_26_175742_add_foreign_keys_to_mchn_tbl_table',0),(353,'2025_10_26_175742_add_foreign_keys_to_mchndown_tbl_table',0),(354,'2025_10_26_175742_add_foreign_keys_to_prdlog_tbl_table',0),(355,'2025_10_26_175742_add_foreign_keys_to_prdroute_tbl_table',0),(356,'2025_10_26_175742_add_foreign_keys_to_proc_tbl_table',0),(357,'2025_10_26_175742_add_foreign_keys_to_qualinsp_tbl_table',0),(358,'2025_10_26_175742_add_foreign_keys_to_wo_tbl_table',0);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdlog_tbl`
--

DROP TABLE IF EXISTS `prdlog_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdlog_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wo_no` varchar(50) NOT NULL,
  `itm_cd` varchar(50) NOT NULL,
  `proc_cd` varchar(50) NOT NULL,
  `mchn_cd` varchar(50) DEFAULT NULL,
  `emp_id` varchar(20) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `in_qty` float DEFAULT NULL,
  `out_qty` float DEFAULT NULL,
  `ng_qty` float DEFAULT '0',
  `rmks` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wo_no` (`wo_no`),
  KEY `itm_cd` (`itm_cd`),
  KEY `proc_cd` (`proc_cd`),
  KEY `mchn_cd` (`mchn_cd`),
  KEY `emp_id` (`emp_id`),
  CONSTRAINT `prdlog_tbl_ibfk_1` FOREIGN KEY (`wo_no`) REFERENCES `wo_tbl` (`wo_no`),
  CONSTRAINT `prdlog_tbl_ibfk_2` FOREIGN KEY (`itm_cd`) REFERENCES `itm_tbl` (`itm_cd`),
  CONSTRAINT `prdlog_tbl_ibfk_3` FOREIGN KEY (`proc_cd`) REFERENCES `proc_tbl` (`proc_cd`),
  CONSTRAINT `prdlog_tbl_ibfk_4` FOREIGN KEY (`mchn_cd`) REFERENCES `mchn_tbl` (`mchn_cd`),
  CONSTRAINT `prdlog_tbl_ibfk_5` FOREIGN KEY (`emp_id`) REFERENCES `empl_tbl` (`emp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdlog_tbl`
--

LOCK TABLES `prdlog_tbl` WRITE;
/*!40000 ALTER TABLE `prdlog_tbl` DISABLE KEYS */;
INSERT INTO `prdlog_tbl` VALUES (2,'2025-304','S-62098-D','P001',NULL,'1001','2025-10-24 08:00:00','2025-10-24 08:53:45',100000,100000,10000,'NG Retak','2025-10-23 11:30:46','2025-10-24 20:05:38',NULL,'rexon'),(3,'2025-304','S-62098-D','P002','M-02CL3','1002','2025-10-25 10:02:57','2025-10-25 00:05:14',100000,95000,5000,'NG Retak : 2000\nNG Gores : 3000','2025-10-24 20:03:45','2025-10-24 20:08:43','rexon','rexon'),(4,'2025-304','S-62098-D','P003','M-02PFL','1003','2025-10-25 06:01:09','2025-10-25 06:01:13',95000,90000,5000,NULL,'2025-10-24 23:02:02','2025-10-24 23:02:02','rexon','rexon'),(5,'2025-305','S-50204-B','P001',NULL,'1001','2025-10-25 06:07:40','2025-10-25 06:07:49',25000,25000,0,NULL,'2025-10-24 23:08:33','2025-10-24 23:08:33','rexon','rexon'),(6,'2025-305','S-50204-B','P002','M-02CL3','1002','2025-10-25 06:09:43','2025-10-25 06:09:48',25000,24500,500,NULL,'2025-10-24 23:10:11','2025-10-24 23:16:08','rexon','rexon'),(7,'2025-305','S-50204-B','P008','M-02LMTX','1002','2025-10-25 07:19:15','2025-10-25 08:19:58',18000,15000,3000,'NG Warna','2025-10-24 23:21:05','2025-10-25 20:13:01','rexon','rexon'),(8,'2025-305','S-50204-B','P003',NULL,'1002','2025-10-25 06:22:38','2025-10-25 06:22:46',25000,19000,1000,NULL,'2025-10-24 23:23:13','2025-10-24 23:23:13','rexon','rexon'),(9,'2025-306','D-13046-C1','P001',NULL,'1001','2025-10-26 19:22:30','2025-10-26 19:40:35',70000,69000,0,NULL,'2025-10-25 20:23:27','2025-10-25 20:42:36','rexon','rexon'),(10,'2025-306','D-13046-C1','P002','M-02CL3','1003','2025-10-26 22:43:24','2025-10-26 23:43:38',69000,68500,500,'NG Retak : 500','2025-10-25 20:44:34','2025-10-25 20:44:34','rexon','rexon');
/*!40000 ALTER TABLE `prdlog_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prdroute_tbl`
--

DROP TABLE IF EXISTS `prdroute_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prdroute_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `itm_type` varchar(50) NOT NULL,
  `seq_no` int NOT NULL,
  `proc_cd` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `itm_type` (`itm_type`,`seq_no`,`proc_cd`),
  KEY `proc_cd` (`proc_cd`),
  CONSTRAINT `prdroute_tbl_ibfk_1` FOREIGN KEY (`proc_cd`) REFERENCES `proc_tbl` (`proc_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prdroute_tbl`
--

LOCK TABLES `prdroute_tbl` WRITE;
/*!40000 ALTER TABLE `prdroute_tbl` DISABLE KEYS */;
INSERT INTO `prdroute_tbl` VALUES (1,'SINGLE SIDE REGULER PCB - FINISH ROUTING',1,'P001','2025-10-18 08:20:17','2025-10-18 08:20:17',NULL,NULL),(2,'SINGLE SIDE REGULER PCB - FINISH ROUTING',2,'P002','2025-10-18 08:20:52','2025-10-18 08:20:52',NULL,NULL),(3,'SINGLE SIDE REGULER PCB - FINISH ROUTING',3,'P003','2025-10-18 08:21:21','2025-10-18 08:21:21',NULL,NULL),(4,'SINGLE SIDE REGULER PCB - FINISH ROUTING',4,'P004','2025-10-18 08:21:37','2025-10-18 08:21:37',NULL,NULL),(5,'SINGLE SIDE REGULER PCB - FINISH ROUTING',5,'P005','2025-10-18 08:21:50','2025-10-18 08:21:50',NULL,NULL),(6,'SINGLE SIDE REGULER PCB - FINISH ROUTING',6,'P006','2025-10-18 08:22:11','2025-10-18 08:22:11',NULL,NULL),(7,'SINGLE SIDE REGULER PCB - FINISH ROUTING',7,'P007','2025-10-18 08:22:23','2025-10-18 08:22:23',NULL,NULL),(8,'SINGLE SIDE CARBON',1,'P001','2025-10-18 08:44:23','2025-10-18 08:44:23',NULL,NULL),(9,'SINGLE SIDE CARBON',2,'P002','2025-10-18 08:44:40','2025-10-18 08:44:40',NULL,NULL),(10,'SINGLE SIDE CARBON',3,'P003','2025-10-18 08:44:52','2025-10-18 08:44:52',NULL,NULL),(11,'SINGLE SIDE CARBON',4,'P008','2025-10-18 08:55:14','2025-10-18 08:55:14',NULL,NULL),(12,'SINGLE SIDE CARBON',5,'P009','2025-10-18 08:56:08','2025-10-18 08:56:08',NULL,NULL),(13,'SINGLE SIDE REGULER PCB - FINISH ROUTING',8,'P009','2025-10-25 18:53:39','2025-10-25 18:53:39','rexon','rexon'),(14,'SINGLE SIDE CARBON',6,'P007','2025-10-25 19:03:44','2025-10-25 19:03:44','rexon','rexon');
/*!40000 ALTER TABLE `prdroute_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proc_tbl`
--

DROP TABLE IF EXISTS `proc_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proc_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proc_cd` varchar(50) NOT NULL,
  `proc_nm` varchar(100) NOT NULL,
  `dept_cd` varchar(20) DEFAULT NULL,
  `std_time` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proc_cd` (`proc_cd`),
  KEY `dept_cd` (`dept_cd`),
  CONSTRAINT `proc_tbl_ibfk_1` FOREIGN KEY (`dept_cd`) REFERENCES `dept_tbl` (`dept_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proc_tbl`
--

LOCK TABLES `proc_tbl` WRITE;
/*!40000 ALTER TABLE `proc_tbl` DISABLE KEYS */;
INSERT INTO `proc_tbl` VALUES (1,'P001','RAW MATERIAL WAREHOUSE','PPIC',30,'2025-10-18 08:14:38','2025-10-18 08:14:38',NULL,NULL),(2,'P002','CUTTING + GRINDING','PRD',30,'2025-10-18 08:15:22','2025-10-18 08:15:22',NULL,NULL),(3,'P003','SCRUBBING NO. 5','PRD',15,'2025-10-18 08:16:18','2025-10-18 08:16:18',NULL,NULL),(4,'P004','PRINTING PATERN BLOCK','PRD',60,'2025-10-18 08:16:58','2025-10-18 08:16:58',NULL,NULL),(5,'P005','OVEN','PRD',45,'2025-10-18 08:17:31','2025-10-18 08:17:31',NULL,NULL),(6,'P006','EXPOSURE PATTERN','PRD',30,'2025-10-18 08:18:14','2025-10-18 08:18:14',NULL,NULL),(7,'P007','DEVELOP PATTERN','PRD',20,'2025-10-18 08:18:51','2025-10-18 08:18:51',NULL,NULL),(8,'P008','PRINTING PATTERN - UV - QC','PRD',60,'2025-10-18 08:46:09','2025-10-18 08:46:09',NULL,NULL),(9,'P009','ETCHING','PRD',50,'2025-10-18 08:47:57','2025-10-18 08:47:57',NULL,NULL),(10,'P010','HOLE DRILLING','PRD',15,'2025-10-18 08:54:05','2025-10-18 08:54:05',NULL,NULL),(11,'P011','PATTERN QC','PRD',20,'2025-10-25 19:26:51','2025-10-25 19:26:51','rexon','rexon');
/*!40000 ALTER TABLE `proc_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qualinsp_tbl`
--

DROP TABLE IF EXISTS `qualinsp_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualinsp_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wo_no` varchar(50) NOT NULL,
  `itm_cd` varchar(50) NOT NULL,
  `insp_qty` float DEFAULT NULL,
  `passed_qty` float DEFAULT NULL,
  `failed_qty` float DEFAULT NULL,
  `insp_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `pic_id` varchar(20) NOT NULL,
  `remarks` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wo_no` (`wo_no`),
  KEY `itm_cd` (`itm_cd`),
  KEY `pic_id` (`pic_id`),
  CONSTRAINT `qualinsp_tbl_ibfk_1` FOREIGN KEY (`wo_no`) REFERENCES `wo_tbl` (`wo_no`),
  CONSTRAINT `qualinsp_tbl_ibfk_2` FOREIGN KEY (`itm_cd`) REFERENCES `itm_tbl` (`itm_cd`),
  CONSTRAINT `qualinsp_tbl_ibfk_3` FOREIGN KEY (`pic_id`) REFERENCES `empl_tbl` (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualinsp_tbl`
--

LOCK TABLES `qualinsp_tbl` WRITE;
/*!40000 ALTER TABLE `qualinsp_tbl` DISABLE KEYS */;
/*!40000 ALTER TABLE `qualinsp_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('yFDVcda6sY0KZ05fRuq8yxQ8Ni3HBwzHZixEN38H',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:143.0) Gecko/20100101 Firefox/143.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiNklHT05tTnBlNmE1N2dUenh0MGltdnM5RzgxQ3ZUTnZQMk0ydW5rcyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ4OiJodHRwOi8vbG9jYWxob3N0OjgwODEvd2VsbGJlc3QvY29tcGFueS1kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkYVIuZHcwNnRWNWlkMEk1TFBlYnVyZXFCZjhZdW9uN252YVBaT3h6OGg3NTdneUVxUk5ENmEiO3M6NjoidGFibGVzIjthOjE6e3M6Mjc6Ik1hbmFnZVByb2R1Y3RSb3V0ZV9wZXJfcGFnZSI7czoxOiI1Ijt9fQ==',1760846529);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shift_tbl`
--

DROP TABLE IF EXISTS `shift_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shift_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shift_cd` varchar(20) NOT NULL,
  `shift_nm` varchar(50) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shift_cd` (`shift_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shift_tbl`
--

LOCK TABLES `shift_tbl` WRITE;
/*!40000 ALTER TABLE `shift_tbl` DISABLE KEYS */;
INSERT INTO `shift_tbl` VALUES (1,'1','PAGI','06:00:00','14:00:00','2025-10-23 10:40:34','2025-10-23 10:40:34',NULL,NULL),(2,'2','SIANG','14:00:00','22:00:00','2025-10-23 10:41:03','2025-10-24 18:24:04',NULL,'rexon'),(3,'3','MALAM','22:00:00','06:00:00','2025-10-23 10:41:35','2025-10-23 10:41:35',NULL,NULL),(4,'N','NON SHIFT','08:00:00','17:00:00','2025-10-23 10:46:24','2025-10-23 10:46:24',NULL,NULL);
/*!40000 ALTER TABLE `shift_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_logs`
--

DROP TABLE IF EXISTS `user_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `usr_id` varchar(20) NOT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `log_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_logs`
--

LOCK TABLES `user_logs` WRITE;
/*!40000 ALTER TABLE `user_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'rexon','rexonindo@gmail.com',NULL,'$2y$12$aR.dw06tV5id0I5LPebureqBf8Yuon7nvaPZOxz8h757gyEqRND6a','1EnsEnFdfS19sRycXW15ec9kNLAU6AfoawTpzG2yKbUEyUWbX8HB3So5WhWM','2025-10-18 08:02:51','2025-10-18 08:02:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `wo_progress_view`
--

DROP TABLE IF EXISTS `wo_progress_view`;
/*!50001 DROP VIEW IF EXISTS `wo_progress_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `wo_progress_view` AS SELECT 
 1 AS `wo_no`,
 1 AS `itm_cd`,
 1 AS `itm_type`,
 1 AS `seq_no`,
 1 AS `proc_cd`,
 1 AS `proc_nm`,
 1 AS `end_time`,
 1 AS `in_qty`,
 1 AS `ng_qty`,
 1 AS `out_qty`,
 1 AS `mchn_cd`,
 1 AS `emp_nm`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `wo_status_view`
--

DROP TABLE IF EXISTS `wo_status_view`;
/*!50001 DROP VIEW IF EXISTS `wo_status_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `wo_status_view` AS SELECT 
 1 AS `wo_no`,
 1 AS `req_dt`,
 1 AS `itm_cd`,
 1 AS `itm_type`,
 1 AS `seq_no`,
 1 AS `proc_cd`,
 1 AS `proc_nm`,
 1 AS `end_time`,
 1 AS `plan_qty`,
 1 AS `out_qty`,
 1 AS `os_qty`,
 1 AS `mchn_cd`,
 1 AS `emp_nm`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `wo_tbl`
--

DROP TABLE IF EXISTS `wo_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wo_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `wo_no` varchar(50) NOT NULL,
  `itm_cd` varchar(50) NOT NULL,
  `po_no` varchar(50) DEFAULT NULL,
  `req_dt` date DEFAULT NULL,
  `plan_qty` float DEFAULT NULL,
  `start_dt` date DEFAULT NULL,
  `end_dt` date DEFAULT NULL,
  `stats` enum('Planned','In Progress','Completed','Cancelled') DEFAULT 'Planned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wo_no` (`wo_no`),
  KEY `itm_cd` (`itm_cd`),
  CONSTRAINT `wo_tbl_ibfk_1` FOREIGN KEY (`itm_cd`) REFERENCES `itm_tbl` (`itm_cd`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wo_tbl`
--

LOCK TABLES `wo_tbl` WRITE;
/*!40000 ALTER TABLE `wo_tbl` DISABLE KEYS */;
INSERT INTO `wo_tbl` VALUES (1,'2025-304','S-62098-D','2025-894','2025-10-30',100000,'2025-10-20','2025-10-28','Planned','2025-10-18 09:03:26','2025-10-26 10:24:19',NULL,'rexon'),(3,'2025-305','S-50204-B','2025-102','2025-10-16',25000,'2025-10-11','2025-10-15','Planned','2025-10-24 23:06:47','2025-10-26 10:25:01','rexon','rexon'),(4,'2025-306','D-13046-C1','2025-113','2025-11-26',70000,'2025-11-01','2025-11-24','Planned','2025-10-25 20:21:01','2025-10-26 10:26:06','rexon','rexon'),(5,'2025-307','S-62098-D','2025-114','2025-12-01',250000,'2025-11-15','2025-11-28','Planned','2025-10-25 21:05:50','2025-10-25 21:06:41','rexon','rexon');
/*!40000 ALTER TABLE `wo_tbl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'mais_wellbest'
--

--
-- Dumping routines for database 'mais_wellbest'
--

--
-- Final view structure for view `current_production_qty_view`
--

/*!50001 DROP VIEW IF EXISTS `current_production_qty_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `current_production_qty_view` AS select `a`.`pyear` AS `pyear`,`a`.`pmonth` AS `pmonth`,sum(ifnull(`a`.`plan_qty`,0)) AS `ttl_plan_qty`,sum(ifnull(`b`.`out_qty`,0)) AS `ttl_out_qty`,sum((ifnull(`a`.`plan_qty`,0) - ifnull(`b`.`out_qty`,0))) AS `ttl_os_qty` from ((select year(`a`.`req_dt`) AS `pyear`,month(`a`.`req_dt`) AS `pmonth`,sum(`a`.`plan_qty`) AS `plan_qty` from `wo_status_view` `a` group by year(`a`.`req_dt`),month(`a`.`req_dt`)) `a` left join (select year(`a`.`end_time`) AS `pyear`,month(`a`.`end_time`) AS `pmonth`,sum(`a`.`out_qty`) AS `out_qty` from (`wo_status_view` `a` join (select `prdroute_tbl`.`itm_type` AS `itm_type`,max(`prdroute_tbl`.`seq_no`) AS `seq_no` from `prdroute_tbl` group by `prdroute_tbl`.`itm_type`) `b` on(((`a`.`itm_type` = `b`.`itm_type`) and (`a`.`seq_no` = `b`.`seq_no`)))) group by year(`a`.`end_time`),month(`a`.`end_time`)) `b` on(((`a`.`pyear` = `b`.`pyear`) and (`a`.`pmonth` = `b`.`pmonth`)))) where ((`a`.`pyear` = year(now())) and (`a`.`pmonth` = month(now()))) group by `a`.`pyear`,`a`.`pmonth` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `current_total_machine_view`
--

/*!50001 DROP VIEW IF EXISTS `current_total_machine_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `current_total_machine_view` AS select count(0) AS `ttl_machine` from (select `prdlog_tbl`.`mchn_cd` AS `total_mchn` from `prdlog_tbl` where ((year(`prdlog_tbl`.`start_time`) = year(now())) and (month(`prdlog_tbl`.`start_time`) = month(now()))) group by `prdlog_tbl`.`mchn_cd`) `a` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `current_total_product_view`
--

/*!50001 DROP VIEW IF EXISTS `current_total_product_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `current_total_product_view` AS select count(0) AS `ttl_product` from (select `prdlog_tbl`.`itm_cd` AS `total_product` from `prdlog_tbl` where ((year(`prdlog_tbl`.`start_time`) = year(now())) and (month(`prdlog_tbl`.`start_time`) = month(now()))) group by `prdlog_tbl`.`itm_cd`) `a` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `wo_progress_view`
--

/*!50001 DROP VIEW IF EXISTS `wo_progress_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `wo_progress_view` AS select `a`.`wo_no` AS `wo_no`,`a`.`itm_cd` AS `itm_cd`,`b`.`itm_type` AS `itm_type`,`c`.`seq_no` AS `seq_no`,`c`.`proc_cd` AS `proc_cd`,`d`.`proc_nm` AS `proc_nm`,`e`.`end_time` AS `end_time`,`e`.`in_qty` AS `in_qty`,`e`.`ng_qty` AS `ng_qty`,`e`.`out_qty` AS `out_qty`,`e`.`mchn_cd` AS `mchn_cd`,`f`.`emp_nm` AS `emp_nm` from (((((`wo_tbl` `a` left join `itm_tbl` `b` on((`a`.`itm_cd` = `b`.`itm_cd`))) left join `prdroute_tbl` `c` on((`b`.`itm_type` = `c`.`itm_type`))) left join `proc_tbl` `d` on((`c`.`proc_cd` = `d`.`proc_cd`))) left join `prdlog_tbl` `e` on(((`a`.`wo_no` = `e`.`wo_no`) and (`a`.`itm_cd` = `e`.`itm_cd`) and (`c`.`proc_cd` = `e`.`proc_cd`)))) left join `empl_tbl` `f` on((`e`.`emp_id` = `f`.`emp_id`))) order by `a`.`wo_no`,`a`.`itm_cd`,`b`.`itm_type`,`c`.`seq_no` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `wo_status_view`
--

/*!50001 DROP VIEW IF EXISTS `wo_status_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `wo_status_view` AS select `a`.`wo_no` AS `wo_no`,`c`.`req_dt` AS `req_dt`,`a`.`itm_cd` AS `itm_cd`,`a`.`itm_type` AS `itm_type`,`a`.`seq_no` AS `seq_no`,`a`.`proc_cd` AS `proc_cd`,`a`.`proc_nm` AS `proc_nm`,`a`.`end_time` AS `end_time`,`c`.`plan_qty` AS `plan_qty`,`a`.`out_qty` AS `out_qty`,(`c`.`plan_qty` - `a`.`out_qty`) AS `os_qty`,`a`.`mchn_cd` AS `mchn_cd`,`a`.`emp_nm` AS `emp_nm` from ((`wo_progress_view` `a` join (select `wo_progress_view`.`wo_no` AS `wo_no`,max(`wo_progress_view`.`seq_no`) AS `seq_no` from `wo_progress_view` where (ifnull(`wo_progress_view`.`out_qty`,0) <> 0) group by `wo_progress_view`.`wo_no`) `b` on(((`a`.`wo_no` = `b`.`wo_no`) and (`a`.`seq_no` = `b`.`seq_no`)))) join `wo_tbl` `c` on((`a`.`wo_no` = `c`.`wo_no`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-26 18:04:27
