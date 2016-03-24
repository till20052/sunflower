-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sunflower
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

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
-- Table structure for table `apps`
--

DROP TABLE IF EXISTS `apps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL DEFAULT 'home',
  `controller` varchar(255) NOT NULL DEFAULT 'index',
  `method` varchar(255) NOT NULL DEFAULT 'execute',
  `lang_key` varchar(2) NOT NULL DEFAULT 'ru',
  `language` varchar(2) NOT NULL DEFAULT 'ru',
  `db_key` varchar(255) NOT NULL DEFAULT 'master',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apps`
--

LOCK TABLES `apps` WRITE;
/*!40000 ALTER TABLE `apps` DISABLE KEYS */;
INSERT INTO `apps` VALUES (1,'socnet-fstgen-pro','volya-ua.org','socnet-fstgen-pro','home','index','execute','ua','ua','master'),(2,'vstavay-fstgen-pro','euro-revolution.org','vstavay-fstgen-pro','home','index','execute','ua','ua','master'),(3,'volya-v1','volya.ua','volya-v1','home','index','execute','ua','ua','master');
/*!40000 ALTER TABLE `apps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apps_db`
--

DROP TABLE IF EXISTS `apps_db`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps_db` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `db_key` varchar(255) NOT NULL DEFAULT 'master',
  `hostname` varchar(255) NOT NULL DEFAULT 'localhost',
  `port` int(11) NOT NULL DEFAULT '3306',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `database` varchar(255) NOT NULL,
  `driver` varchar(255) NOT NULL DEFAULT 'mysql',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apps_db`
--

LOCK TABLES `apps_db` WRITE;
/*!40000 ALTER TABLE `apps_db` DISABLE KEYS */;
INSERT INTO `apps_db` VALUES (1,1,'master','localhost',3306,'socnet','8RK6AMsSpc7dmA7V','socnet-fstgen-pro','mysql'),(2,2,'master','localhost',3306,'vstavay','p56D8VKZtY9wr3yv','vstavay-fstgen-pro','mysql'),(3,3,'master','localhost',3306,'volya-v1','G2fe4WQdYKKVbP8W','volya-v1','mysql');
/*!40000 ALTER TABLE `apps_db` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apps_langs`
--

DROP TABLE IF EXISTS `apps_langs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps_langs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apps_langs`
--

LOCK TABLES `apps_langs` WRITE;
/*!40000 ALTER TABLE `apps_langs` DISABLE KEYS */;
INSERT INTO `apps_langs` VALUES (1,1,'ua'),(2,1,'ru'),(3,1,'en'),(4,2,'ua'),(5,3,'ua');
/*!40000 ALTER TABLE `apps_langs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apps_options`
--

DROP TABLE IF EXISTS `apps_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apps_options`
--

LOCK TABLES `apps_options` WRITE;
/*!40000 ALTER TABLE `apps_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `apps_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ru` text NOT NULL,
  `ua` text NOT NULL,
  `en` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-24 23:51:02
