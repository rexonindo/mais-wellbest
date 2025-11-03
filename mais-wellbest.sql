DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- ----------------------------------- MAIN TABLE PROGRAM----------------------------------

DROP TABLE IF EXISTS `itm_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `itm_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `itm_cd` varchar(50) NOT NULL,
  `itm_nm` varchar(100) NOT NULL,
  `fg_flg` bit(1) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `std_rate` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`itm_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `dept_cd` (`dept_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `shift_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shift_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shift_cd` varchar(20) NOT NULL,
  `shift_name` varchar(50) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shift_cd` (`shift_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY (`proc_cd`),
  KEY `dept_cd` (`dept_cd`),
  CONSTRAINT `proc_tbl_ibfk_1` FOREIGN KEY (`dept_cd`) REFERENCES `dept_tbl` (`dept_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `mchn_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mchn_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mchn_cd` varchar(50) NOT NULL,
  `mchn_nm` varchar(100) DEFAULT NULL,
  `dept_cd` varchar(20) DEFAULT NULL,
  `stats` enum('Running','Idle','Maintenance','Breakdown') DEFAULT 'Idle',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`mchn_cd`),
  KEY `dept_cd` (`dept_cd`),
  CONSTRAINT `mchn_tbl_ibfk_1` FOREIGN KEY (`dept_cd`) REFERENCES `dept_tbl` (`dept_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY (`emp_id`),
  KEY `dept_cd` (`dept_cd`),
  KEY `shift_cd` (`shift_cd`),
  CONSTRAINT `empl_tbl_ibfk_1` FOREIGN KEY (`dept_cd`) REFERENCES `dept_tbl` (`dept_cd`),
  CONSTRAINT `empl_tbl_ibfk_2` FOREIGN KEY (`shift_cd`) REFERENCES `shift_tbl` (`shift_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY (`wo_no`),
  KEY `itm_cd` (`itm_cd`),
  CONSTRAINT `wo_tbl_ibfk_1` FOREIGN KEY (`itm_cd`) REFERENCES `itm_tbl` (`itm_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  KEY `mchn_cd` (`mchn_cd`),
  CONSTRAINT `mchndown_tbl_ibfk_1` FOREIGN KEY (`mchn_cd`) REFERENCES `mchn_tbl` (`mchn_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY (`itm_type`,`seq_no`,`proc_cd`),
  KEY `proc_cd` (`proc_cd`),
  CONSTRAINT `prdroute_tbl_ibfk_1` FOREIGN KEY (`proc_cd`) REFERENCES `proc_tbl` (`proc_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `invmov_tbl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invmov_tbl` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `itm_cd` varchar(50) NOT NULL,
  `mov_type` enum('IN','OUT') DEFAULT NULL,
  `qty` float DEFAULT NULL,
  `ref_type` varchar(50) DEFAULT NULL,
  `ref_id` int DEFAULT NULL,
  `mov_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itm_cd` (`itm_cd`),
  CONSTRAINT `invmov_tbl_ibfk_1` FOREIGN KEY (`itm_cd`) REFERENCES `itm_tbl` (`itm_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`id`),
  KEY `wo_no` (`wo_no`),
  KEY `itm_cd` (`itm_cd`),
  KEY `pic_id` (`pic_id`),
  CONSTRAINT `qualinsp_tbl_ibfk_1` FOREIGN KEY (`wo_no`) REFERENCES `wo_tbl` (`wo_no`),
  CONSTRAINT `qualinsp_tbl_ibfk_2` FOREIGN KEY (`itm_cd`) REFERENCES `itm_tbl` (`itm_cd`),
  CONSTRAINT `qualinsp_tbl_ibfk_3` FOREIGN KEY (`pic_id`) REFERENCES `empl_tbl` (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

