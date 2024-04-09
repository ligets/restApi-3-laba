-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: restapi
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `airplane_seats`
--

DROP TABLE IF EXISTS `airplane_seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `airplane_seats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `airplane_seats`
--

LOCK TABLES `airplane_seats` WRITE;
/*!40000 ALTER TABLE `airplane_seats` DISABLE KEYS */;
INSERT INTO `airplane_seats` VALUES (1,'A1'),(2,'A2'),(3,'A3'),(4,'A4'),(5,'A5'),(6,'A6'),(7,'B1'),(8,'B2'),(9,'B3'),(10,'B4'),(11,'B5'),(12,'B6'),(13,'C1'),(14,'C2'),(15,'C3'),(16,'C4'),(17,'C5'),(18,'C6'),(19,'D1'),(20,'D2'),(21,'D3'),(22,'D4'),(23,'D5'),(24,'D6'),(25,'E1'),(26,'E2'),(27,'E3'),(28,'E4'),(29,'E5'),(30,'E6'),(31,'F1'),(32,'F2'),(33,'F3'),(34,'F4'),(35,'F5'),(36,'F6'),(37,'G1'),(38,'G2'),(39,'G3'),(40,'G4'),(41,'G5'),(42,'G6'),(43,'H1'),(44,'H2'),(45,'H3'),(46,'H4'),(47,'H5'),(48,'H6');
/*!40000 ALTER TABLE `airplane_seats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `airports`
--

DROP TABLE IF EXISTS `airports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `airports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `iata` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `airports`
--

LOCK TABLES `airports` WRITE;
/*!40000 ALTER TABLE `airports` DISABLE KEYS */;
INSERT INTO `airports` VALUES (1,'Kazan','Kazan','KZN'),(2,'Moscow','Sheremetyevo','SVO'),(3,'St Petersburg','Pulkovo','LED'),(4,'Sochi','Sochi','AER');
/*!40000 ALTER TABLE `airports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `flight_from` bigint unsigned NOT NULL,
  `flight_back` bigint unsigned DEFAULT NULL,
  `date_from` date NOT NULL,
  `date_back` date DEFAULT NULL,
  `code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_flight_from_foreign` (`flight_from`),
  KEY `bookings_flight_back_foreign` (`flight_back`),
  CONSTRAINT `bookings_flight_back_foreign` FOREIGN KEY (`flight_back`) REFERENCES `flights` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_flight_from_foreign` FOREIGN KEY (`flight_from`) REFERENCES `flights` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (25,1,2,'2020-09-20','2020-09-30','3TIUR'),(26,1,2,'2020-09-20','2020-09-30','WTATA'),(27,1,2,'2020-09-20','2020-09-30','2IJS5'),(28,1,2,'2020-09-20','2020-09-30','Y9NM9'),(29,2,1,'2020-09-20','2020-09-30','NRB08'),(30,2,1,'2020-09-20','2020-09-30','LB0TP');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
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
-- Table structure for table `flights`
--

DROP TABLE IF EXISTS `flights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flights` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `flight_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint unsigned NOT NULL,
  `time_from` time NOT NULL,
  `to_id` bigint unsigned NOT NULL,
  `time_to` time NOT NULL,
  `cost` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `flights_from_id_foreign` (`from_id`),
  KEY `flights_to_id_foreign` (`to_id`),
  CONSTRAINT `flights_from_id_foreign` FOREIGN KEY (`from_id`) REFERENCES `airports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `flights_to_id_foreign` FOREIGN KEY (`to_id`) REFERENCES `airports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flights`
--

LOCK TABLES `flights` WRITE;
/*!40000 ALTER TABLE `flights` DISABLE KEYS */;
INSERT INTO `flights` VALUES (1,'FP2100',2,'08:35:00',1,'10:05:00',10500),(2,'FP1200',1,'12:00:00',2,'13:35:00',9500),(3,'FP2300',2,'07:05:00',3,'08:20:00',4500),(4,'FP3200',3,'11:35:00',2,'12:50:00',5500),(5,'FP2400',2,'10:00:00',4,'11:20:00',3500),(6,'FP4200',4,'13:00:00',2,'14:20:00',4500),(7,'FP3100',3,'15:00:00',1,'16:50:00',7000),(8,'FP1300',1,'18:30:00',3,'20:10:00',7500),(9,'FP3400',3,'18:00:00',4,'20:10:00',10450),(10,'FP4300',4,'21:30:00',3,'23:10:00',12050),(11,'FP1400',1,'14:30:00',4,'16:30:00',15050),(12,'FP1400',1,'17:30:00',4,'19:30:00',14050),(13,'FP2101',2,'12:10:00',1,'13:35:00',12500),(14,'FP1201',1,'08:45:00',2,'10:05:00',10500),(15,'FP2301',2,'11:45:00',3,'12:50:00',5000),(16,'FP3201',3,'07:15:00',2,'08:20:00',6000),(17,'FP2401',2,'13:10:00',4,'14:20:00',2500),(18,'FP4201',4,'10:10:00',2,'11:20:00',3500),(19,'FP3101',3,'18:40:00',1,'20:10:00',7500),(20,'FP1301',1,'15:10:00',3,'16:50:00',6500),(21,'FP3401',3,'21:40:00',4,'23:10:00',9450),(22,'FP4301',4,'18:10:00',3,'20:10:00',13050),(23,'FP1401',1,'17:40:00',4,'19:30:00',13050),(24,'FP1401',1,'14:40:00',4,'16:30:00',12050);
/*!40000 ALTER TABLE `flights` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(3,'2024_04_09_105754_create_airplane_seats_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passengers`
--

DROP TABLE IF EXISTS `passengers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `passengers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `document_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_from` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_back` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `passengers_booking_id_foreign` (`booking_id`),
  CONSTRAINT `passengers_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passengers`
--

LOCK TABLES `passengers` WRITE;
/*!40000 ALTER TABLE `passengers` DISABLE KEYS */;
INSERT INTO `passengers` VALUES (33,25,'Ivan','Ivanov','1990-02-20','1234567890','A1','A1'),(34,25,'Ivan','Gorbunov','1990-03-20','1224567890','A2','A2'),(35,26,'Ivan','Ivanov','1990-02-20','1234567890','A3','A3'),(36,26,'Ivan','Gorbunov','1990-03-20','1224567890','A4','A4'),(37,27,'Ivan','Ivanov','1990-02-20','1234567890','A5','A5'),(38,27,'Ivan','Gorbunov','1990-03-20','1224567890','A6','A6'),(39,28,'Ivan','Ivanov','1990-02-20','1234567890','B1','B1'),(40,28,'Ivan','Gorbunov','1990-03-20','1224567890','B2','B2'),(42,28,'Danil','Popkov','2024-04-04','0123456789','B3','B3'),(43,29,'test','test','1990-02-20','0987654321','A1','A1'),(44,29,'Danil','Popkov','1990-03-20','0123456789','A2','A2'),(45,30,'test','test','1990-02-20','0987654321',NULL,NULL),(46,30,'Danil','Popkov','1990-03-20','0123456789',NULL,NULL);
/*!40000 ALTER TABLE `passengers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title_promotion` varchar(255) NOT NULL,
  `body_promotion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Пользователь'),(2,'Администратор');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL DEFAULT '1',
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  UNIQUE KEY `document_number` (`document_number`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (8,2,'Danil','Popkov','89588441320',NULL,'$2y$12$0Y8ZGYEdmT02H6kWZdJT/uk9GY7WOi9DDYnNo9nfqtZk6SV3sjykG','0123456789'),(10,1,'Ivan','Ivanov','89588441321',NULL,'$2y$12$SXuLWGWLWNlgl.akwJes7eCKkSgSXH2mUn68mmHHTPG2jXYmJ1oh6','0123456781'),(11,1,'test','test','12345678901',NULL,'$2y$12$NenFobTJdoj1pj332lNuz.V3KdG.jpMoDnvVTmVPYkMqqm5WYsjmW','0987654321');
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

-- Dump completed on 2024-04-10  0:56:33
