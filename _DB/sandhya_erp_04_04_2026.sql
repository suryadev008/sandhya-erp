-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: sandhya_erp
-- ------------------------------------------------------
-- Server version	8.4.6

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
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. created, updated, deleted, approved, payroll_generated, slip_sent',
  `model_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. App\\Models\\Payroll',
  `model_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_index` (`user_id`),
  KEY `activity_logs_model_index` (`model_type`,`model_id`),
  KEY `activity_logs_action_index` (`action`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

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

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('356a192b7913b04c54574d18c28d46e6395428ab','i:2;',1775303404),('356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1775303404;',1775303404),('5c785c036466adea360111aa28563bfd556b5fba','i:1;',1775303400),('5c785c036466adea360111aa28563bfd556b5fba:timer','i:1775303399;',1775303400);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

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

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnc_productions`
--

DROP TABLE IF EXISTS `cnc_productions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cnc_productions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `machine_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `shift` enum('day','night','A','B','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `company_id` bigint unsigned NOT NULL,
  `job_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_id` bigint unsigned NOT NULL,
  `operation_type` enum('full_finish','finish_first_side','finish_second_side') COLLATE utf8mb4_unicode_ci NOT NULL,
  `actual_cycle_time` time DEFAULT NULL,
  `production_qty` int NOT NULL DEFAULT '0',
  `target_qty` smallint unsigned NOT NULL DEFAULT '90' COMMENT 'Target for this shift — defaults from employee setting',
  `incentive_qty` int unsigned NOT NULL DEFAULT '0' COMMENT 'MAX(0, production_qty - target_qty)',
  `rate_per_piece` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Applicable for per_piece payment model only',
  `incentive_rate` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT 'Incentive per piece above target — day_rate model only',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Calculated production amount for this entry',
  `target_met` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Auto-calculated when saving',
  `downtime_type` enum('power_cut','machine_breakdown','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Excused reason — full day pay even if target not met',
  `downtime_minutes` int DEFAULT '0',
  `is_sunday` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Sunday = half day pay',
  `is_half_day` tinyint(1) NOT NULL DEFAULT '0',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cnc_productions_company_id_foreign` (`company_id`),
  KEY `cnc_productions_part_id_foreign` (`part_id`),
  KEY `cnc_prod_emp_date_index` (`employee_id`,`date`),
  KEY `cnc_productions_date_index` (`date`),
  KEY `cnc_productions_machine_id_index` (`machine_id`),
  KEY `cnc_productions_created_by_foreign` (`created_by`),
  CONSTRAINT `cnc_productions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `cnc_productions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cnc_productions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `cnc_productions_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cnc_productions_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnc_productions`
--

LOCK TABLES `cnc_productions` WRITE;
/*!40000 ALTER TABLE `cnc_productions` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnc_productions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plant_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `gst_no` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_trade_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_legal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_pan` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_registration_date` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_business_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_verified_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_gst_no_unique` (`gst_no`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Tata Motors Ltd','Jamshedpur Plant','Ramesh Kumar','9876543210','Jamshedpur, Jharkhand','Main client',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(2,'Bosch India Ltd','Pune Plant','Sunil Sharma','9876543211','Pune, Maharashtra','Auto parts supplier',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(3,'Mahindra & Mahindra','Nashik Plant','Anil Gupta','9876543212','Nashik, Maharashtra',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(4,'Bajaj Auto Ltd','Aurangabad Plant','Vijay Patil','9876543213','Aurangabad, MH','Two wheeler parts',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(5,'Hero MotoCorp','Dharuhera Plant','Rakesh Singh','9876543214','Dharuhera, Haryana',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(6,'Ashok Leyland','Hosur Plant','Praveen Nair','9876543215','Hosur, Tamil Nadu','Heavy vehicle parts',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(7,'Maruti Suzuki India','Manesar Plant','Deepak Verma','9876543216','Manesar, Haryana',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(8,'RKFL u','plan 1 u','Suraj u','9865587840','jamshedpur u','top company u',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2026-04-03 14:57:50','2026-04-03 14:58:47','2026-04-03 14:58:47');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_categories`
--

DROP TABLE IF EXISTS `contact_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_categories`
--

LOCK TABLES `contact_categories` WRITE;
/*!40000 ALTER TABLE `contact_categories` DISABLE KEYS */;
INSERT INTO `contact_categories` VALUES (1,'CNC Maintenance',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(2,'Lathe Maintenance',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(3,'Electrician',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(4,'Civil Engineer',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(5,'Plumber',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(6,'Transporter',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(7,'Marketing',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(8,'Vendor',1,'2026-04-03 14:23:50','2026-04-03 14:23:50'),(9,'Accountant',1,'2026-04-03 14:23:50','2026-04-03 14:23:50');
/*!40000 ALTER TABLE `contact_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_phones`
--

DROP TABLE IF EXISTS `contact_phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_phones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` bigint unsigned NOT NULL,
  `phone_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mobile',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_phones_contact_id_foreign` (`contact_id`),
  CONSTRAINT `contact_phones_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_phones`
--

LOCK TABLES `contact_phones` WRITE;
/*!40000 ALTER TABLE `contact_phones` DISABLE KEYS */;
INSERT INTO `contact_phones` VALUES (3,7,'9876543222','Mobile',1,'2026-04-03 14:39:14','2026-04-03 14:39:14'),(4,7,'89545855','Mobile',0,'2026-04-03 14:39:14','2026-04-03 14:39:14'),(5,7,'8954558787','WhatsApp',0,'2026-04-03 14:39:14','2026-04-03 14:39:14'),(6,7,'9865445878','Office',0,'2026-04-03 14:39:14','2026-04-03 14:39:14');
/*!40000 ALTER TABLE `contact_phones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `person_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_category_id` bigint unsigned DEFAULT NULL,
  `upi_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_contact_category_id_foreign` (`contact_category_id`),
  CONSTRAINT `contacts_contact_category_id_foreign` FOREIGN KEY (`contact_category_id`) REFERENCES `contact_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (3,'suraj',NULL,NULL,'9876767654','aasd sa','as dsad','fsdfsdf','dfsdfsd','asdsads','asdasd ada',1,'2026-03-24 13:57:39','2026-03-24 13:57:39'),(4,'suraj','Jamshedpur',NULL,'9876767654','aasd sa','as dsad','fsdfsdfrr','dfsdfsd','asdsads','asdasd ada',1,'2026-03-24 14:02:01','2026-04-03 14:25:55'),(5,'suraj','saa',4,'9876767654','aasd sa','as dsad','fsdfsdfrr','dfsdfsd','asdsads','asdasd ada',1,'2026-04-03 14:27:33','2026-04-03 14:27:33'),(7,'Suraj','Jamshedpur',9,'9876767654','dasda','9898989799','AS23222','SBI','Jmashedpur','asdasd ada',1,'2026-04-03 14:39:14','2026-04-03 14:39:14');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_operation_rates`
--

DROP TABLE IF EXISTS `employee_operation_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_operation_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `operation_id` bigint unsigned NOT NULL,
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `applicable_from` date NOT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_operation_rates_operation_id_foreign` (`operation_id`),
  KEY `emp_op_rate_lookup` (`employee_id`,`operation_id`,`applicable_from`),
  KEY `employee_operation_rates_created_by_foreign` (`created_by`),
  CONSTRAINT `employee_operation_rates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employee_operation_rates_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_operation_rates_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_operation_rates`
--

LOCK TABLES `employee_operation_rates` WRITE;
/*!40000 ALTER TABLE `employee_operation_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee_operation_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_salaries`
--

DROP TABLE IF EXISTS `employee_salaries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_salaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `per_day` decimal(10,2) NOT NULL COMMENT 'Per day salary',
  `per_month` decimal(10,2) NOT NULL COMMENT 'Per month salary (30 days)',
  `effect_from` date NOT NULL COMMENT 'Salary effective from this date',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'e.g. Joining Salary, Annual Increment',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_salaries_created_by_foreign` (`created_by`),
  KEY `employee_salaries_employee_id_effect_from_index` (`employee_id`,`effect_from`),
  KEY `idx_salaries_emp_effect` (`employee_id`,`effect_from`),
  CONSTRAINT `employee_salaries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employee_salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_salaries`
--

LOCK TABLES `employee_salaries` WRITE;
/*!40000 ALTER TABLE `employee_salaries` DISABLE KEYS */;
INSERT INTO `employee_salaries` VALUES (1,5,300.00,9000.00,'2026-01-01',NULL,1,'2026-03-21 11:25:07','2026-03-21 11:25:07');
/*!40000 ALTER TABLE `employee_salaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `emp_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. EMP001',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aadhar_no` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_primary` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Required',
  `mobile_secondary` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional',
  `whatsapp_no` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For salary slip sharing',
  `upi_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` text COLLATE utf8mb4_unicode_ci,
  `present_address` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Required',
  `aadhar_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File path in storage',
  `bank_account_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc_code` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_no` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '15-digit GSTIN (optional, for contractor employees)',
  `employee_type` enum('lathe','cnc','both') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lathe' COMMENT 'Determines payroll calculation method',
  `cnc_payment_type` enum('day_rate','per_piece') COLLATE utf8mb4_unicode_ci DEFAULT 'day_rate',
  `cnc_target_per_shift` smallint unsigned DEFAULT '90',
  `cnc_incentive_rate` decimal(8,2) DEFAULT '0.00',
  `experience_years` decimal(4,1) DEFAULT '0.0',
  `joining_date` date DEFAULT NULL,
  `status` enum('active','inactive','terminated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_emp_code_unique` (`emp_code`),
  UNIQUE KEY `employees_aadhar_no_unique` (`aadhar_no`),
  UNIQUE KEY `employees_gst_no_unique` (`gst_no`),
  KEY `employees_emp_code_index` (`emp_code`),
  KEY `employees_mobile_primary_index` (`mobile_primary`),
  KEY `employees_mobile_secondary_index` (`mobile_secondary`),
  KEY `employees_whatsapp_no_index` (`whatsapp_no`),
  KEY `employees_name_index` (`name`),
  KEY `employees_status_index` (`status`),
  KEY `employees_created_by_foreign` (`created_by`),
  KEY `idx_employees_status` (`status`),
  KEY `idx_employees_type` (`employee_type`),
  CONSTRAINT `employees_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'EMP001','Raju Prasad','123456789012','9801234561',NULL,'9801234561','raju@upi','Village Rampur, Jharkhand','Gamharia, Seraikela, Jharkhand',NULL,'1234567890','SBI',NULL,'SBIN0001234',NULL,'lathe','day_rate',90,0.00,5.0,'2020-01-01','active',1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(2,'EMP002','Mohan Singh','234567890123','9801234562','9801234572','9801234562',NULL,'Village Simdega, Jharkhand','Adityapur, Jharkhand',NULL,'2345678901','Bank of India',NULL,'BKID0001234',NULL,'lathe','day_rate',90,0.00,3.5,'2021-03-15','active',1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(3,'EMP003','Sanjay Kumar','345678901234','9801234563',NULL,'9801234563','sanjay@ybl','Village Bokaro, Jharkhand','Gamharia, Jharkhand',NULL,'3456789012','PNB',NULL,'PUNB0001234',NULL,'lathe','day_rate',90,0.00,7.0,'2018-06-01','active',1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(4,'EMP004','Vikash Mahto','456789012345','9801234564',NULL,'9801234564','vikash@paytm','Village Ranchi, Jharkhand','Adityapur, Jharkhand',NULL,'4567890123','HDFC',NULL,'HDFC0001234',NULL,'cnc','day_rate',90,0.00,4.0,'2020-07-01','active',1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(5,'EMP005','Amit Sharma','567890123456','9801234565','9801234575','9801234575',NULL,'Village Dhanbad, Jharkhand','Gamharia, Jharkhand',NULL,'5678901234','ICICI',NULL,'ICIC0001234',NULL,'cnc','day_rate',90,0.00,6.5,'2019-01-15','active',1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(6,'EMP006','Deepak Yadav','678901234567','9801234566',NULL,'9801234566','deepak@gpay','Village Hazaribagh, Jharkhand','Adityapur, Jharkhand',NULL,'6789012345','Axis Bank',NULL,'UTIB0001234',NULL,'both','day_rate',90,0.00,8.0,'2017-04-01','active',1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(7,'EMP007','Suresh Oraon','789012345678','9801234567',NULL,NULL,NULL,'Village Gumla, Jharkhand','Gamharia, Jharkhand',NULL,NULL,NULL,NULL,NULL,NULL,'lathe','day_rate',90,0.00,2.0,'2022-01-01','inactive',1,'2026-03-21 08:14:07','2026-03-21 08:14:07',NULL),(8,'EMP008','Test test',NULL,'9865321444',NULL,NULL,NULL,'test','test',NULL,NULL,NULL,'Surah',NULL,NULL,'lathe','day_rate',90,0.00,NULL,NULL,'active',NULL,'2026-03-31 22:22:19','2026-04-03 15:04:16',NULL),(9,'EMP009','test','123123123123','9897778787','9123485342',NULL,NULL,'test','test',NULL,NULL,NULL,NULL,NULL,NULL,'lathe','day_rate',90,0.00,NULL,NULL,'active',NULL,'2026-03-31 22:22:47','2026-03-31 22:23:02','2026-03-31 22:23:02'),(10,'EMP010','Test test',NULL,'9988776655',NULL,NULL,NULL,'test','test',NULL,NULL,NULL,NULL,NULL,NULL,'cnc','day_rate',90,0.00,NULL,NULL,'active',NULL,'2026-03-31 22:24:09','2026-03-31 22:24:09',NULL);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extra_payments`
--

DROP TABLE IF EXISTS `extra_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `extra_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payroll_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `month` tinyint unsigned NOT NULL,
  `year` smallint unsigned NOT NULL,
  `payment_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g. Diwali Bonus, Advance, Travel Allowance',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `extra_payments_payroll_id_foreign` (`payroll_id`),
  KEY `extra_payments_employee_id_month_year_index` (`employee_id`,`month`,`year`),
  KEY `extra_payments_created_by_foreign` (`created_by`),
  CONSTRAINT `extra_payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `extra_payments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `extra_payments_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extra_payments`
--

LOCK TABLES `extra_payments` WRITE;
/*!40000 ALTER TABLE `extra_payments` DISABLE KEYS */;
INSERT INTO `extra_payments` VALUES (1,1,5,1,2026,'bonus',500.00,1,'2026-03-21 14:08:08','2026-03-21 14:08:08'),(2,1,5,1,2026,'HR',1000.00,1,'2026-03-21 14:08:21','2026-03-21 14:08:21'),(3,3,1,4,2026,'Diwali bonus',500.00,1,'2026-04-03 23:29:06','2026-04-03 23:29:06');
/*!40000 ALTER TABLE `extra_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

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

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

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

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lathe_productions`
--

DROP TABLE IF EXISTS `lathe_productions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lathe_productions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `machine_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `shift` enum('day','night','A','B','general') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `company_id` bigint unsigned NOT NULL,
  `part_id` bigint unsigned NOT NULL,
  `operation_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Fetched from operations at time of entry (snapshot)',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'qty × rate — calculated on save',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `downtime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `downtime_minutes` smallint unsigned DEFAULT NULL,
  `is_half_day` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lathe_productions_machine_id_foreign` (`machine_id`),
  KEY `lathe_productions_part_id_foreign` (`part_id`),
  KEY `lathe_productions_operation_id_foreign` (`operation_id`),
  KEY `lathe_prod_emp_date_index` (`employee_id`,`date`),
  KEY `lathe_productions_date_index` (`date`),
  KEY `lathe_productions_company_id_index` (`company_id`),
  KEY `lathe_productions_created_by_foreign` (`created_by`),
  KEY `idx_lathe_emp_date` (`employee_id`,`date`),
  CONSTRAINT `lathe_productions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lathe_productions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lathe_productions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lathe_productions_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`) ON DELETE SET NULL,
  CONSTRAINT `lathe_productions_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `lathe_productions_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lathe_productions`
--

LOCK TABLES `lathe_productions` WRITE;
/*!40000 ALTER TABLE `lathe_productions` DISABLE KEYS */;
INSERT INTO `lathe_productions` VALUES (1,1,11,'2026-03-21','day',1,13,11,20,15.00,300.00,NULL,NULL,NULL,0,1,'2026-03-21 13:10:17','2026-03-21 13:10:17'),(2,1,11,'2026-03-21','day',1,13,2,15,4.50,67.50,NULL,NULL,NULL,0,1,'2026-03-21 13:10:17','2026-03-21 13:10:17'),(3,1,11,'2026-03-21','day',1,1,3,11,6.00,66.00,NULL,NULL,NULL,0,1,'2026-03-21 13:10:17','2026-03-21 13:10:17'),(4,1,11,'2026-02-01','day',6,15,16,50,10.00,500.00,NULL,NULL,NULL,0,1,'2026-03-22 00:02:15','2026-03-22 00:36:20'),(5,1,11,'2026-02-01','day',6,16,15,10,7.00,70.00,NULL,NULL,NULL,0,1,'2026-03-22 00:02:15','2026-03-22 00:02:15'),(6,1,11,'2026-02-01','day',6,11,14,20,5.00,100.00,NULL,NULL,NULL,0,1,'2026-03-22 00:02:15','2026-03-22 00:02:15'),(7,1,11,'2026-02-02','night',6,15,16,100,10.00,1000.00,NULL,NULL,NULL,0,1,'2026-03-22 00:03:02','2026-03-22 00:03:02'),(8,1,11,'2026-02-02','night',6,16,14,50,5.00,250.00,NULL,NULL,NULL,0,1,'2026-03-22 00:03:02','2026-03-22 00:03:02'),(9,1,1,'2026-03-03','A',6,11,15,500,7.00,3500.00,NULL,NULL,NULL,0,1,'2026-03-22 00:03:35','2026-03-22 00:03:35'),(10,1,11,'2026-04-03','A',6,15,15,5,7.00,35.00,NULL,NULL,NULL,0,1,'2026-04-03 23:21:17','2026-04-03 23:21:17'),(11,1,11,'2026-04-03','A',6,15,15,5,7.00,35.00,NULL,NULL,NULL,0,1,'2026-04-03 23:21:17','2026-04-03 23:21:17'),(12,1,11,'2026-04-03','A',6,16,15,5,7.00,35.00,NULL,NULL,NULL,0,1,'2026-04-03 23:21:17','2026-04-03 23:21:17');
/*!40000 ALTER TABLE `lathe_productions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machine_types`
--

DROP TABLE IF EXISTS `machine_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `machine_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machine_types`
--

LOCK TABLES `machine_types` WRITE;
/*!40000 ALTER TABLE `machine_types` DISABLE KEYS */;
INSERT INTO `machine_types` VALUES (1,'Lathe','Lathe turning machines',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(2,'CNC','CNC machining centers',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(3,'Drill','Drilling machines',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(4,'Tap','Tapping machines',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(6,'Polish','testtt',1,'2026-03-21 10:51:20','2026-03-21 10:51:20');
/*!40000 ALTER TABLE `machine_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `machines`
--

DROP TABLE IF EXISTS `machines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `machines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `machine_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `machine_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `machine_type_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `machines_machine_number_unique` (`machine_number`),
  KEY `machines_machine_type_id_foreign` (`machine_type_id`),
  CONSTRAINT `machines_machine_type_id_foreign` FOREIGN KEY (`machine_type_id`) REFERENCES `machine_types` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `machines`
--

LOCK TABLES `machines` WRITE;
/*!40000 ALTER TABLE `machines` DISABLE KEYS */;
INSERT INTO `machines` VALUES (1,'Lathe Machine 1','LT-001',1,'Heavy duty lathe machine',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(2,'Lathe Machine 2','LT-002',1,'Medium duty lathe machine',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(3,'CNC Machine 1','CNC-001',2,'CNC turning center',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(4,'CNC Machine 2','CNC-002',2,'CNC milling machine',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(5,'CNC Machine 3','CNC-003',2,NULL,0,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(6,'Drill Machine 1','DR-001',3,'Radial drill machine',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(7,'Drill Machine 2','DR-002',3,NULL,1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(8,'Tap Machine 1','TP-001',4,'Tapping machine',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(9,'Tap Machine 2','TP-002',4,NULL,0,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(10,'Lathe Machine 3','LT-003',1,'Old lathe machine',0,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(11,'Drill Machine 3','DM3',1,NULL,1,'2026-03-21 11:02:12','2026-03-21 11:05:03');
/*!40000 ALTER TABLE `machines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000005_create_employees_table',1),(5,'2024_01_01_000006_create_employee_salaries_table',1),(6,'2024_01_01_000007_create_companies_table',1),(7,'2024_01_01_000008_create_machine_types_table',1),(8,'2024_01_01_000008_create_parts_table',1),(9,'2024_01_01_000009_create_machines_table',1),(10,'2024_01_01_000010_create_operations_table',1),(11,'2024_01_01_000011_create_operation_prices_table',1),(12,'2024_01_01_000012_create_lathe_productions_table',1),(13,'2024_01_01_000013_create_cnc_productions_table',1),(14,'2024_01_01_000014_create_payrolls_table',1),(15,'2024_01_01_000015_create_extra_payments_table',1),(16,'2024_01_01_000016_create_salary_slips_table',1),(17,'2024_01_01_000017_create_activity_logs_table',1),(18,'2026_03_19_040701_add_theme_settings_to_users_table',1),(19,'2024_01_01_000018_create_contacts_table',2),(20,'2024_01_01_000019_recreate_contacts_table',3),(21,'2024_01_01_000020_update_contacts_bank_fields',4),(23,'2026_03_24_184845_add_gst_no_to_employees_table',5),(24,'2026_03_24_185647_add_gst_fields_to_companies_table',5),(25,'2026_03_31_055828_create_owner_companies_table',6),(26,'2026_03_31_055828_create_owner_company_bank_accounts_table',6),(27,'2026_03_31_055829_create_owner_company_contacts_table',6),(28,'2026_03_31_080852_add_soft_deletes_and_indexes_to_tables',7),(29,'2026_03_31_081355_add_is_admin_to_users_table',8),(30,'2026_04_01_000001_create_employee_operation_rates_table',9),(31,'2026_04_01_000002_add_cnc_fields_to_employees_table',9),(32,'2026_04_01_000003_add_payroll_fields_to_cnc_productions_table',9),(33,'2026_04_01_000004_fix_cnc_fields_nullable',10),(34,'2026_04_01_100000_add_downtime_halfday_to_lathe_productions_table',11),(35,'2026_04_03_192106_add_area_category_to_contacts_table',12),(36,'2026_04_03_193640_create_contact_categories_table',13),(37,'2026_04_03_193641_change_contacts_category_to_fk',14),(38,'2026_04_03_200204_create_contact_phones_table',15),(39,'2026_04_03_200206_drop_contact_no_from_contacts',16),(40,'2026_04_04_000001_add_bank_branch_to_employees_table',17);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operation_prices`
--

DROP TABLE IF EXISTS `operation_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operation_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `operation_id` bigint unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT 'Rate per piece in INR',
  `applicable_from` date NOT NULL COMMENT 'Price effective from this date',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operation_prices_created_by_foreign` (`created_by`),
  KEY `operation_prices_operation_id_applicable_from_index` (`operation_id`,`applicable_from`),
  CONSTRAINT `operation_prices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `operation_prices_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operation_prices`
--

LOCK TABLES `operation_prices` WRITE;
/*!40000 ALTER TABLE `operation_prices` DISABLE KEYS */;
INSERT INTO `operation_prices` VALUES (1,1,5.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(2,2,4.50,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(3,3,6.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(4,4,7.50,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(5,5,5.50,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(6,6,4.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(7,7,12.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(8,8,8.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(9,9,10.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(10,10,9.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(11,11,15.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(12,12,3.00,'2026-03-21','Initial price',NULL,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(13,13,5.00,'2026-03-01','Initial price',1,'2026-03-21 11:16:36','2026-03-21 11:16:36'),(14,13,5.50,'2026-02-01',NULL,1,'2026-03-21 11:16:58','2026-03-21 11:16:58'),(15,13,6.00,'2026-04-01',NULL,1,'2026-03-21 11:17:16','2026-03-21 11:17:16'),(16,14,5.00,'2026-02-01','Initial price',1,'2026-03-21 23:32:14','2026-03-21 23:32:14'),(17,15,7.00,'2026-02-01','Initial price',1,'2026-03-21 23:32:55','2026-03-21 23:32:55'),(18,16,10.00,'2026-02-01','Initial price',1,'2026-03-21 23:34:04','2026-03-21 23:34:04');
/*!40000 ALTER TABLE `operation_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operations`
--

DROP TABLE IF EXISTS `operations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `operation_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applicable_for` enum('lathe','cnc','both') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lathe',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operations_company_id_foreign` (`company_id`),
  CONSTRAINT `operations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operations`
--

LOCK TABLES `operations` WRITE;
/*!40000 ALTER TABLE `operations` DISABLE KEYS */;
INSERT INTO `operations` VALUES (1,1,'Turning','lathe','Basic turning operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(2,1,'Facing','lathe','Facing operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(3,1,'Boring','lathe','Boring operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(4,1,'Threading','lathe','Threading operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(5,1,'Knurling','lathe','Knurling operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(6,1,'Grooving','lathe','Grooving operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(7,1,'CNC Milling','cnc','CNC milling operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(8,1,'CNC Drilling','cnc','CNC drilling operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(9,1,'CNC Turning','cnc','CNC turning operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(10,1,'CNC Tapping','cnc','CNC tapping operation',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(11,1,'Finish Grinding','both','Final finish grinding',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(12,1,'Quality Check','both','Quality inspection',0,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(13,4,'Drill','cnc',NULL,1,'2026-03-21 11:16:36','2026-03-21 11:20:59'),(14,6,'Polish','lathe',NULL,1,'2026-03-21 23:32:14','2026-03-21 23:32:14'),(15,6,'Drill','lathe',NULL,1,'2026-03-21 23:32:55','2026-03-21 23:32:55'),(16,6,'Cutting','lathe',NULL,1,'2026-03-21 23:34:03','2026-03-21 23:34:03');
/*!40000 ALTER TABLE `operations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owner_companies`
--

DROP TABLE IF EXISTS `owner_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owner_companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_type` enum('pvt_ltd','llp','partnership','sole_prop','public_ltd') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gstin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `incorporation_date` date NOT NULL,
  `financial_year_start` enum('april','january') COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'INR',
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Asia/Kolkata',
  `date_format` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'd/m/Y',
  `invoice_prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_regime` enum('old','new') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reg_address_line1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_pincode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'India',
  `industry_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cin_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tan_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `msme_reg_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corp_address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corp_address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corp_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corp_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corp_pincode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_multi_branch` tinyint(1) NOT NULL DEFAULT '0',
  `authorized_capital` decimal(15,2) DEFAULT NULL,
  `paid_up_capital` decimal(15,2) DEFAULT NULL,
  `num_directors` int DEFAULT NULL,
  `auditor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditor_firm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cs_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `updated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `owner_companies_company_name_unique` (`company_name`),
  UNIQUE KEY `owner_companies_pan_number_unique` (`pan_number`),
  UNIQUE KEY `owner_companies_gstin_unique` (`gstin`),
  KEY `owner_companies_created_by_foreign` (`created_by`),
  KEY `owner_companies_updated_by_foreign` (`updated_by`),
  CONSTRAINT `owner_companies_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `owner_companies_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner_companies`
--

LOCK TABLES `owner_companies` WRITE;
/*!40000 ALTER TABLE `owner_companies` DISABLE KEYS */;
/*!40000 ALTER TABLE `owner_companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owner_company_bank_accounts`
--

DROP TABLE IF EXISTS `owner_company_bank_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owner_company_bank_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_company_id` bigint unsigned NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('current','savings') COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_company_bank_accounts_owner_company_id_foreign` (`owner_company_id`),
  CONSTRAINT `owner_company_bank_accounts_owner_company_id_foreign` FOREIGN KEY (`owner_company_id`) REFERENCES `owner_companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner_company_bank_accounts`
--

LOCK TABLES `owner_company_bank_accounts` WRITE;
/*!40000 ALTER TABLE `owner_company_bank_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `owner_company_bank_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `owner_company_contacts`
--

DROP TABLE IF EXISTS `owner_company_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `owner_company_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `owner_company_id` bigint unsigned NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternate_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `support_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_company_contacts_owner_company_id_foreign` (`owner_company_id`),
  CONSTRAINT `owner_company_contacts_owner_company_id_foreign` FOREIGN KEY (`owner_company_id`) REFERENCES `owner_companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `owner_company_contacts`
--

LOCK TABLES `owner_company_contacts` WRITE;
/*!40000 ALTER TABLE `owner_company_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `owner_company_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parts`
--

DROP TABLE IF EXISTS `parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `part_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `part_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parts_company_id_part_number_unique` (`company_id`,`part_number`),
  CONSTRAINT `parts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parts`
--

LOCK TABLES `parts` WRITE;
/*!40000 ALTER TABLE `parts` DISABLE KEYS */;
INSERT INTO `parts` VALUES (1,1,'TM-001','Engine Shaft','Main engine shaft for truck',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(2,1,'TM-002','Gear Box Housing','Gear box housing component',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(3,1,'TM-003','Brake Drum','Rear brake drum',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(4,1,'TM-004','Axle Shaft','Front axle shaft',0,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(5,2,'BS-001','Fuel Injector Body','Fuel injector housing',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(6,2,'BS-002','Pump Shaft','Hydraulic pump shaft',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(7,2,'BS-003','Valve Body','Control valve body',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(8,3,'MM-001','Camshaft','Engine camshaft',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(9,3,'MM-002','Crankshaft','Engine crankshaft',1,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(10,3,'MM-003','Flywheel','Engine flywheel assembly',0,'2026-03-21 08:14:07','2026-03-21 08:14:07'),(11,6,'BJ-001','Piston Pin','Gudgeon pin / piston pin',1,'2026-03-21 08:14:07','2026-03-21 12:38:57'),(12,1,'BJ-002','Connecting Rod','Engine connecting rod',1,'2026-03-21 08:14:07','2026-03-21 12:38:50'),(13,1,'HR-001','Sprocket','Chain sprocket front',1,'2026-03-21 08:14:07','2026-03-21 12:38:40'),(14,1,'HR-002','Disc Rotor','Front disc brake rotor',1,'2026-03-21 08:14:07','2026-03-21 12:38:31'),(15,6,'AL-002','Iron Roa',NULL,1,'2026-03-21 23:35:01','2026-03-21 23:35:44'),(16,6,'AL-003','Bottom of T rod',NULL,1,'2026-03-21 23:35:33','2026-03-21 23:35:33');
/*!40000 ALTER TABLE `parts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

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

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payrolls`
--

DROP TABLE IF EXISTS `payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payrolls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned NOT NULL,
  `month` tinyint unsigned NOT NULL COMMENT '1 = January … 12 = December',
  `year` smallint unsigned NOT NULL,
  `total_working_days` int NOT NULL DEFAULT '0' COMMENT 'Calendar working days in the month',
  `present_days` decimal(5,1) NOT NULL DEFAULT '0.0' COMMENT 'Includes 0.5 for half days',
  `sunday_half_days` decimal(5,1) NOT NULL DEFAULT '0.0',
  `total_lathe_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'SUM of lathe_productions.amount for the month',
  `total_cnc_days` decimal(5,1) NOT NULL DEFAULT '0.0' COMMENT 'Billable days calculated by target logic',
  `cnc_rate_per_day` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Rate at time of payroll generation (snapshot)',
  `total_cnc_amount` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'total_cnc_days × cnc_rate_per_day',
  `extra_payment_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `deductions` decimal(12,2) NOT NULL DEFAULT '0.00',
  `deduction_remarks` text COLLATE utf8mb4_unicode_ci,
  `net_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','approved','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `generated_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payrolls_emp_month_year_unique` (`employee_id`,`month`,`year`),
  KEY `payrolls_month_year_index` (`month`,`year`),
  KEY `payrolls_status_index` (`status`),
  KEY `payrolls_generated_by_foreign` (`generated_by`),
  KEY `payrolls_approved_by_foreign` (`approved_by`),
  KEY `idx_payrolls_emp_year_month` (`employee_id`,`year`,`month`),
  KEY `idx_payrolls_status` (`status`),
  CONSTRAINT `payrolls_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `payrolls_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payrolls`
--

LOCK TABLES `payrolls` WRITE;
/*!40000 ALTER TABLE `payrolls` DISABLE KEYS */;
INSERT INTO `payrolls` VALUES (1,5,1,2026,26,26.0,0.0,0.00,26.0,300.00,7800.00,1500.00,9300.00,0.00,NULL,9300.00,'approved',1,1,'2026-03-21 14:07:32','2026-03-21 14:08:53',NULL,'2026-03-21 14:07:32','2026-03-21 14:08:53'),(2,1,3,2026,26,26.0,0.0,433.50,0.0,0.00,0.00,0.00,433.50,0.00,NULL,433.50,'paid',1,1,'2026-03-21 14:58:07','2026-03-21 14:58:21','2026-04-03 23:27:43','2026-03-21 14:58:07','2026-04-03 23:27:43'),(3,1,4,2026,0,0.0,0.0,105.00,0.0,0.00,0.00,500.00,605.00,100.00,'late fine',505.00,'draft',1,NULL,'2026-04-03 23:33:01',NULL,NULL,'2026-04-03 23:28:03','2026-04-03 23:33:01');
/*!40000 ALTER TABLE `payrolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salary_slips`
--

DROP TABLE IF EXISTS `salary_slips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `salary_slips` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payroll_id` bigint unsigned NOT NULL,
  `slip_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_sent` tinyint(1) NOT NULL DEFAULT '0',
  `whatsapp_sent_at` timestamp NULL DEFAULT NULL,
  `sent_to_number` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'WhatsApp number used at time of sending',
  `sent_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salary_slips_payroll_id_unique` (`payroll_id`),
  UNIQUE KEY `salary_slips_slip_number_unique` (`slip_number`),
  KEY `salary_slips_sent_by_foreign` (`sent_by`),
  CONSTRAINT `salary_slips_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE,
  CONSTRAINT `salary_slips_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salary_slips`
--

LOCK TABLES `salary_slips` WRITE;
/*!40000 ALTER TABLE `salary_slips` DISABLE KEYS */;
/*!40000 ALTER TABLE `salary_slips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

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

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('hS7eYUVr5OnqLS6PyuuuxRMjWmMo73zbrYv334RI',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','ZXlKcGRpSTZJbXB3TkRRcmRGWmxSbmx2Unk5U2FHTm5jMWhRYVZFOVBTSXNJblpoYkhWbElqb2lkME52VGxOeGVtRnRiVFV4WWpsQk1VVkdja2RwUzFnMmVucDJhbTVuUzA4eE0zQnlhM0pQZG5WWGRIWlhjVlZWVFRSdFExRmxibXRzY21WMlJqaHdaRVJ5ZURsMmMzcHhkSEptYUdKb1VVZDFWa0p1ZVZWamFFOW9RWFpDWTBSNFpsWlNXRU5OTlhaemNWTktkMU5QZUhSVmNYWTFhaTlyYUVsTWJXOXlhSEJxVVhrMVZtUnNOSEY2Y1dRM2RtSlJPVWhGYmtGVU5sUTVkV2RKTUVsS2NVdE1SRVZqU21RelJVdFNabVExTkRCUWJ6WmpkMGh3ZWk5TlkyZFZZMG80VW5jMmJ6aGtRMWcyYkZsdWVVMHJXSGg1UTA1NU9URTBPRlJ0TTJaVWVtMHZZazlaVjNaSVpEQmFRMmhCWW5sUWFWRnlTbmxNUnpGeVdXb3ZLM0ZyZGt4b01qbENhekJtV1doNVdtdExjMVJsZUcwMmNqaENTRE55Ykd4ek9YTkRSbGRoTkc4eU4yVTJNbXRPVWtKbGJFSldWbkJaVDI4Mk1WTXJVMUpaVkU1dFZFZFRUR2hqVG5KMGJHbEVRVlEwVmpNNU1raHpkemR6WjFvM1NVcElNR1JXTm1WYU9VcEhNRmd3WWxvNVRHaHhWM1JqYUhsME5tRjZZMXBsVmtFcklpd2liV0ZqSWpvaU16RXhOemN4WVRFMk9ESm1PR05tWXpCaFl6VXlOV1JoWlRKaE1XTTJNVEkzTkRkaFpETm1ZVFExWkdVMk5tTTFNR00xWlRSa00yVmxNbVU0WVdWaFlTSXNJblJoWnlJNklpSjk=',1775303519);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_employee_id_foreign` (`employee_id`),
  CONSTRAINT `users_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Suraj Das','admin@gmail.com',1,'2026-03-21 08:14:06','$2y$12$ARnlPXoryk120F2vosvCHeg6bNdnBi5rD4yfCyWV6tPHBaBlV4Br2','qSWkTm9Ylk',NULL,'2026-03-21 08:14:07','2026-03-31 02:45:27');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-04 18:41:32
