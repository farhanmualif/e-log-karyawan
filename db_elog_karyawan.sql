-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: db_elog_karyawan
-- ------------------------------------------------------
-- Server version	10.1.26-MariaDB

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
-- Table structure for table `log_aktivitas`
--

DROP TABLE IF EXISTS `log_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_aktivitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_awal` time NOT NULL,
  `waktu_akhir` time NOT NULL,
  `aktivitas` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `departemen_id` bigint(20) unsigned DEFAULT NULL,
  `unit_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('menunggu','tervalidasi','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `validated_by` bigint(20) unsigned DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `catatan_validasi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_aktivitas_validated_by_foreign` (`validated_by`),
  KEY `log_aktivitas_user_id_tanggal_index` (`user_id`,`tanggal`),
  KEY `log_aktivitas_status_index` (`status`),
  KEY `log_aktivitas_departemen_id_index` (`departemen_id`),
  KEY `log_aktivitas_unit_id_index` (`unit_id`),
  CONSTRAINT `log_aktivitas_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `tb_departemen` (`id`) ON DELETE SET NULL,
  CONSTRAINT `log_aktivitas_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `tb_unit` (`id`) ON DELETE SET NULL,
  CONSTRAINT `log_aktivitas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `log_aktivitas_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_aktivitas`
--

LOCK TABLES `log_aktivitas` WRITE;
/*!40000 ALTER TABLE `log_aktivitas` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_aktivitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2024_01_01_000001_create_log_aktivitas_table',1),(4,'2025_11_19_101419_create_tb_departemen',1),(5,'2025_11_19_101602_create_tb_unit',1),(6,'2025_11_19_111523_add_unit_and_departemen_to_users_table',1),(7,'2025_11_20_000001_add_foreign_keys_to_log_aktivitas_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_departemen`
--

DROP TABLE IF EXISTS `tb_departemen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_departemen` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_departemen`
--

LOCK TABLES `tb_departemen` WRITE;
/*!40000 ALTER TABLE `tb_departemen` DISABLE KEYS */;
INSERT INTO `tb_departemen` VALUES (1,'ADMINISTRASI','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(2,'ADUM','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(3,'ANALIS KESEHATAN LAB','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(4,'ANESTESI','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(5,'ASPER','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(6,'BELUM DITENTUKAN','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(7,'BIDAN','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(8,'BISDEV','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(9,'CLEANING SERVICE','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(10,'CS','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(11,'DIREKTUR','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(12,'DOKTER','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(13,'DOKTER SPESIALIS','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(14,'FARMASI','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(15,'FISIOTERAPI','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(16,'GENERAL KASIR','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(17,'GIZI','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(18,'GUDANG FARMASI','2025-11-20 18:03:36','2025-11-20 18:03:36',NULL),(19,'IT','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(20,'JAN MED','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(21,'KAMAR BEDAH DAN CSSD','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(22,'KASIR','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(23,'KEBIDANAN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(24,'KEPERAWATAN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(25,'KERUMAHTANGGAAN, LOGISTIK','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(26,'KESEKRETARIATAN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(27,'KESLING','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(28,'KEUANGAN DAN AKUTANSI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(29,'KOMITE ETIK HUKUM','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(30,'KOMITE K3 RS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(31,'KOMITE KEPERAWATAN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(32,'KOMITE MEDIS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(33,'KOMITE MUTU DAN KESELAMAT','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(34,'KOMITE NAKES LAIN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(35,'KOMITE PPI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(36,'KOMITE PPRA','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(37,'LABORATORIUM','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(38,'LAUNDRY','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(39,'LAYANAN UNGGULAN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(40,'MARKETING','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(41,'PANITIA FARMASI DAN TERAP','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(42,'PANITIA PKRS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(43,'PANITIA REKAM MEDIS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(44,'PELAPORAN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(45,'PENATA ANESTESI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(46,'PENDAFTARAN','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(47,'PENGEMUDI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(48,'PENJAMINAN ASURANSI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(49,'PENJAMINAN BPJS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(50,'PERBENDAHARAAN DAN MOBILI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(51,'PERENCANAAN ANGGARAN, AKU','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(52,'PHM','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(53,'PSRS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(54,'QUALITY CONTROL','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(55,'RADIOLOGI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(56,'RAWAT INAP DAN HCU','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(57,'RAWAT JALAN DAN HD','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(58,'REHABILITASI MEDIS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(59,'REKAM MEDIS','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(60,'SATPAM','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(61,'SDM, DIKLAT','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(62,'SPI','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(63,'TIM CASEMIX','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(64,'TIM KORDIK','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(65,'TIM PONEX','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(66,'TIM TB-HIV','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(67,'UGD','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(68,'UNGGULAN (LANSIA, ON CALL','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(69,'UNGGULAN DOKTER','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL),(70,'YANMED','2025-11-20 18:03:37','2025-11-20 18:03:37',NULL);
/*!40000 ALTER TABLE `tb_departemen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tb_unit`
--

DROP TABLE IF EXISTS `tb_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_unit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `departemen_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_unit_departemen_id_foreign` (`departemen_id`),
  CONSTRAINT `tb_unit_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `tb_departemen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_unit`
--

LOCK TABLES `tb_unit` WRITE;
/*!40000 ALTER TABLE `tb_unit` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password_changed` tinyint(1) NOT NULL DEFAULT '0',
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'karyawan',
  `unit_id` bigint(20) unsigned DEFAULT NULL,
  `departemen_id` bigint(20) unsigned DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_unit_id_index` (`unit_id`),
  KEY `users_departemen_id_index` (`departemen_id`),
  CONSTRAINT `users_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `tb_departemen` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `tb_unit` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','superadmin','superadmin@example.com','2025-11-20 18:03:36',1,'superadmin',NULL,NULL,'$2y$10$MGrQ1r6Ktxy3NZu2vrF/HuRuiBIZGkcCrpJyagBsYtreS31SRytrm',NULL,'2025-11-20 18:03:36','2025-11-20 18:12:34'),(2,'dr. Anastasia RSH','L9909132',NULL,NULL,0,'karyawan',NULL,NULL,'$2y$10$n1ttQb.LB87VhOhAXO/.neqypf7fAmG5qgHr6LMAhJT168XHejHgS',NULL,'2025-11-20 18:13:22','2025-11-20 18:13:22');
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

-- Dump completed on 2025-11-21  1:17:09
