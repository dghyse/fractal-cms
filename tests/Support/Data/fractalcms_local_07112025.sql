/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.0.2-MariaDB, for osx10.19 (x86_64)
--
-- Host: localhost    Database: fractalcms
-- ------------------------------------------------------
-- Server version	12.0.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `authAssignment`
--

DROP TABLE IF EXISTS `authAssignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `authAssignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authAssignment`
--

LOCK TABLES `authAssignment` WRITE;
/*!40000 ALTER TABLE `authAssignment` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `authAssignment` VALUES
('ADMIN','1',1761047648);
/*!40000 ALTER TABLE `authAssignment` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `authItem`
--

DROP TABLE IF EXISTS `authItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `authItem` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `authitem_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `authRules` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authItem`
--

LOCK TABLES `authItem` WRITE;
/*!40000 ALTER TABLE `authItem` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `authItem` VALUES
('ADMIN',1,NULL,NULL,NULL,1761047616,1761047616),
('AUTHOR',1,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:ITEM:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:ITEM:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:ITEM:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:ITEM:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:ITEM:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:ITEM:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:TYPE:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:TYPE:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:TYPE:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:TYPE:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:TYPE:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONFIG:TYPE:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:ITEM:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:ITEM:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:ITEM:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:ITEM:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:ITEM:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:ITEM:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('CONTENT:TAGACTIVATION',2,NULL,NULL,NULL,1762246466,1762246466),
('CONTENT:TAGCREATE',2,NULL,NULL,NULL,1762246466,1762246466),
('CONTENT:TAGDELETE',2,NULL,NULL,NULL,1762246466,1762246466),
('CONTENT:TAGLIST',2,NULL,NULL,NULL,1762246466,1762246466),
('CONTENT:TAGMANAGE',2,NULL,NULL,NULL,1762246466,1762246466),
('CONTENT:TAGUPDATE',2,NULL,NULL,NULL,1762246466,1762246466),
('CONTENT:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616),
('ITEM:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('ITEM:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('ITEM:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('ITEM:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('ITEM:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('ITEM:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616),
('MENU:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('MENU:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('MENU:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('MENU:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('MENU:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('MENU:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616),
('PARAMTEER:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('PARAMTEER:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('PARAMTEER:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('PARAMTEER:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('PARAMTEER:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('PARAMTEER:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616),
('TAG:ACTIVATION',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:CREATE',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:DELETE',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:ITEM:ACTIVATION',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:ITEM:CREATE',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:ITEM:DELETE',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:ITEM:LIST',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:ITEM:MANAGE',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:ITEM:UPDATE',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:LIST',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:MANAGE',2,NULL,NULL,NULL,1762246466,1762246466),
('TAG:UPDATE',2,NULL,NULL,NULL,1762246466,1762246466),
('USER:ACTIVATION',2,NULL,NULL,NULL,1761047616,1761047616),
('USER:CREATE',2,NULL,NULL,NULL,1761047616,1761047616),
('USER:DELETE',2,NULL,NULL,NULL,1761047616,1761047616),
('USER:LIST',2,NULL,NULL,NULL,1761047616,1761047616),
('USER:MANAGE',2,NULL,NULL,NULL,1761047616,1761047616),
('USER:UPDATE',2,NULL,NULL,NULL,1761047616,1761047616);
/*!40000 ALTER TABLE `authItem` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `authItemChild`
--

DROP TABLE IF EXISTS `authItemChild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `authItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authItemChild`
--

LOCK TABLES `authItemChild` WRITE;
/*!40000 ALTER TABLE `authItemChild` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `authItemChild` VALUES
('ADMIN','AUTHOR'),
('CONFIG:ITEM:MANAGE','CONFIG:ITEM:ACTIVATION'),
('CONFIG:ITEM:MANAGE','CONFIG:ITEM:CREATE'),
('CONFIG:ITEM:MANAGE','CONFIG:ITEM:DELETE'),
('CONFIG:ITEM:MANAGE','CONFIG:ITEM:LIST'),
('ADMIN','CONFIG:ITEM:MANAGE'),
('CONFIG:ITEM:MANAGE','CONFIG:ITEM:UPDATE'),
('CONFIG:TYPE:MANAGE','CONFIG:TYPE:ACTIVATION'),
('CONFIG:TYPE:MANAGE','CONFIG:TYPE:CREATE'),
('CONFIG:TYPE:MANAGE','CONFIG:TYPE:DELETE'),
('CONFIG:TYPE:MANAGE','CONFIG:TYPE:LIST'),
('ADMIN','CONFIG:TYPE:MANAGE'),
('CONFIG:TYPE:MANAGE','CONFIG:TYPE:UPDATE'),
('CONTENT:MANAGE','CONTENT:ACTIVATION'),
('CONTENT:MANAGE','CONTENT:CREATE'),
('CONTENT:MANAGE','CONTENT:DELETE'),
('CONTENT:ITEM:MANAGE','CONTENT:ITEM:ACTIVATION'),
('CONTENT:ITEM:MANAGE','CONTENT:ITEM:CREATE'),
('CONTENT:ITEM:MANAGE','CONTENT:ITEM:DELETE'),
('CONTENT:ITEM:MANAGE','CONTENT:ITEM:LIST'),
('ADMIN','CONTENT:ITEM:MANAGE'),
('CONTENT:ITEM:MANAGE','CONTENT:ITEM:UPDATE'),
('CONTENT:MANAGE','CONTENT:LIST'),
('ADMIN','CONTENT:MANAGE'),
('CONTENT:TAGMANAGE','CONTENT:TAGACTIVATION'),
('CONTENT:TAGMANAGE','CONTENT:TAGCREATE'),
('CONTENT:TAGMANAGE','CONTENT:TAGDELETE'),
('CONTENT:TAGMANAGE','CONTENT:TAGLIST'),
('ADMIN','CONTENT:TAGMANAGE'),
('CONTENT:TAGMANAGE','CONTENT:TAGUPDATE'),
('CONTENT:MANAGE','CONTENT:UPDATE'),
('ITEM:MANAGE','ITEM:ACTIVATION'),
('ITEM:MANAGE','ITEM:CREATE'),
('ITEM:MANAGE','ITEM:DELETE'),
('ITEM:MANAGE','ITEM:LIST'),
('ADMIN','ITEM:MANAGE'),
('ITEM:MANAGE','ITEM:UPDATE'),
('MENU:MANAGE','MENU:ACTIVATION'),
('MENU:MANAGE','MENU:CREATE'),
('MENU:MANAGE','MENU:DELETE'),
('MENU:MANAGE','MENU:LIST'),
('ADMIN','MENU:MANAGE'),
('MENU:MANAGE','MENU:UPDATE'),
('PARAMTEER:MANAGE','PARAMTEER:ACTIVATION'),
('PARAMTEER:MANAGE','PARAMTEER:CREATE'),
('PARAMTEER:MANAGE','PARAMTEER:DELETE'),
('PARAMTEER:MANAGE','PARAMTEER:LIST'),
('ADMIN','PARAMTEER:MANAGE'),
('PARAMTEER:MANAGE','PARAMTEER:UPDATE'),
('TAG:MANAGE','TAG:ACTIVATION'),
('TAG:MANAGE','TAG:CREATE'),
('TAG:MANAGE','TAG:DELETE'),
('TAG:ITEM:MANAGE','TAG:ITEM:ACTIVATION'),
('TAG:ITEM:MANAGE','TAG:ITEM:CREATE'),
('TAG:ITEM:MANAGE','TAG:ITEM:DELETE'),
('TAG:ITEM:MANAGE','TAG:ITEM:LIST'),
('ADMIN','TAG:ITEM:MANAGE'),
('TAG:ITEM:MANAGE','TAG:ITEM:UPDATE'),
('TAG:MANAGE','TAG:LIST'),
('ADMIN','TAG:MANAGE'),
('TAG:MANAGE','TAG:UPDATE'),
('USER:MANAGE','USER:ACTIVATION'),
('USER:MANAGE','USER:CREATE'),
('USER:MANAGE','USER:DELETE'),
('USER:MANAGE','USER:LIST'),
('ADMIN','USER:MANAGE'),
('USER:MANAGE','USER:UPDATE');
/*!40000 ALTER TABLE `authItemChild` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `authRules`
--

DROP TABLE IF EXISTS `authRules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `authRules` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authRules`
--

LOCK TABLES `authRules` WRITE;
/*!40000 ALTER TABLE `authRules` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `authRules` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `configItems`
--

DROP TABLE IF EXISTS `configItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `configItems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  CONSTRAINT `configItems_chk_1` CHECK (json_valid(`config`))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configItems`
--

LOCK TABLES `configItems` WRITE;
/*!40000 ALTER TABLE `configItems` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `configItems` VALUES
(1,'hero','\"{\\\"title\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Titre\\\"},\\\"subtitle\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Sous-titre\\\"},\\\"description\\\":{\\\"type\\\":\\\"wysiwyg\\\",\\\"title\\\":\\\"Description\\\"},\\\"banner\\\":{\\\"type\\\":\\\"file\\\",\\\"title\\\":\\\"image en 500x300 (accueil), 1200x300 (article) \\\",\\\"accept\\\":\\\"png, jpeg, jpg, webp\\\"},\\\"altbanner\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Alt de la banner\\\"},\\\"imgCard\\\":{\\\"type\\\":\\\"file\\\",\\\"title\\\":\\\"image en 500x300\\\",\\\"accept\\\":\\\"png, jpeg, jpg, webp\\\",\\\"description\\\":\\\"\\\\\\\"Image pour les bloc \\\\\\\"Content\\\\\\\"\\\"},\\\"ctatitle1\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Titre du bouton CTA 1\\\",\\\"description\\\":\\\"Intitulé présent dans le bouton CTA 1\\\"},\\\"target1\\\":{\\\"type\\\":\\\"listcms\\\",\\\"title\\\":\\\"Article cible du bouton du hero CTA 1\\\",\\\"description\\\":\\\"Choisir un article\\\"},\\\"externurl1\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Url Externe pour le Bouton CTA 1\\\",\\\"description\\\":\\\"Url externe, le lien sera en target \\\\\\\"blank\\\\\\\"\\\"},\\\"ctatitle2\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Titre du bouton CTA 2\\\",\\\"description\\\":\\\"Intitulé présent dans le bouton CTA 2\\\"},\\\"target2\\\":{\\\"type\\\":\\\"listcms\\\",\\\"title\\\":\\\"Article cible du bouton du hero CTA 2\\\",\\\"description\\\":\\\"Choisir un article\\\"},\\\"externurl2\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Url Externe pour le Bouton CTA 2\\\",\\\"description\\\":\\\"Url externe, le lien sera en target \\\\\\\"blank\\\\\\\"\\\"}}\"','2025-10-22 13:59:02','2025-11-04 17:13:10'),
(2,'card-article','\"{\\\"title\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Titre\\\"},\\\"ctatitle\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"titre du CTA\\\"},\\\"target\\\":{\\\"type\\\":\\\"listcms\\\",\\\"title\\\":\\\"Cible de la carte\\\",\\\"description\\\":\\\"Choisir un article\\\"},\\\"url\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Lien externe\\\",\\\"description\\\":\\\"Indiquer une url vers laquelle rediriger l\'internaute\\\"},\\\"description\\\":{\\\"type\\\":\\\"wysiwyg\\\",\\\"title\\\":\\\"description\\\",\\\"description\\\":\\\"Texte affiché dans la carte\\\"},\\\"image\\\":{\\\"type\\\":\\\"file\\\",\\\"title\\\":\\\"image en 400x300px\\\",\\\"accept\\\":\\\"png, jpeg, jpg, webp\\\"},\\\"altimage\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Alt de l\'image\\\"}}\"','2025-10-22 14:45:38','2025-10-26 11:49:12'),
(3,'article','\"{\\\"title\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Titre\\\"},\\\"image\\\":{\\\"type\\\":\\\"file\\\",\\\"title\\\":\\\"image en 600x300 (haut), 400x200 (bas) \\\",\\\"accept\\\":\\\"png, jpeg, jpg, webp\\\"},\\\"altimage\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Alt de l\'image\\\"},\\\"description\\\":{\\\"type\\\":\\\"wysiwyg\\\",\\\"title\\\":\\\"description\\\",\\\"description\\\":\\\"Description au format text/html\\\"},\\\"ctatitle\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"titre du CTA\\\"},\\\"target\\\":{\\\"type\\\":\\\"listcms\\\",\\\"title\\\":\\\"Cible de la carte\\\",\\\"description\\\":\\\"Choisir un article\\\"},\\\"url\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Lien externe\\\",\\\"description\\\":\\\"Indiquer une url vers laquelle rediriger l\'internaute\\\"},\\\"direction\\\":{\\\"type\\\":\\\"radio\\\",\\\"title\\\":\\\"Position de l\'image\\\",\\\"values\\\":[{\\\"name\\\":\\\"Image en haut (600x300)\\\",\\\"value\\\":\\\"top\\\"},{\\\"name\\\":\\\"Image en bas (400x200)\\\",\\\"value\\\":\\\"bottom\\\"}]}}\"','2025-10-23 10:27:57','2025-10-25 10:03:08'),
(4,'titre-image-html','\"{\\\"title\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Titre\\\"},\\\"image\\\":{\\\"type\\\":\\\"file\\\",\\\"title\\\":\\\"image en 600x300 (haut), 400x200 (bas) \\\",\\\"accept\\\":\\\"png, jpeg, jpg, webp\\\"},\\\"altimage\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Alt de l\'image\\\"},\\\"description\\\":{\\\"type\\\":\\\"wysiwyg\\\",\\\"title\\\":\\\"description\\\",\\\"description\\\":\\\"Description au format text/html\\\"},\\\"direction\\\":{\\\"type\\\":\\\"radio\\\",\\\"title\\\":\\\"Position de l\'image\\\",\\\"values\\\":[{\\\"name\\\":\\\"Image en haut (600x300)\\\",\\\"value\\\":\\\"top\\\"},{\\\"name\\\":\\\"Image en bas (400x200)\\\",\\\"value\\\":\\\"bottom\\\"}]}}\"','2025-10-24 15:42:56','2025-10-25 11:25:02'),
(5,'form','\"{\\\"form\\\":{\\\"type\\\":\\\"forms\\\",\\\"title\\\":\\\"Formulaire à afficher\\\"},\\\"description\\\":{\\\"type\\\":\\\"wysiwyg\\\",\\\"title\\\":\\\"Description du formulaire\\\"},\\\"captcha\\\":{\\\"type\\\":\\\"radio\\\",\\\"description\\\":\\\"Active le recaptcha invisible (google)\\\",\\\"title\\\":\\\"Activer le captcha\\\",\\\"values\\\":[{\\\"name\\\":\\\"Oui\\\",\\\"value\\\":\\\"o\\\"},{\\\"name\\\":\\\"Non\\\",\\\"value\\\":\\\"n\\\"}]}}\"','2025-10-25 16:37:10','2025-10-25 16:37:10'),
(6,'bandeau-notification','\"{\\\"title\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Titre\\\"},\\\"icon\\\":{\\\"type\\\":\\\"file\\\",\\\"title\\\":\\\"icon SVG de préférence blanche en 64x64px\\\",\\\"accept\\\":\\\"svg\\\"},\\\"ctatitle\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"titre du CTA\\\"},\\\"target\\\":{\\\"type\\\":\\\"listcms\\\",\\\"title\\\":\\\"Cible du lien\\\",\\\"description\\\":\\\"Choisir un article\\\"},\\\"url\\\":{\\\"type\\\":\\\"string\\\",\\\"title\\\":\\\"Lien externe\\\",\\\"description\\\":\\\"Indiquer une url vers laquelle rediriger l\'internaute\\\"}}\"','2025-11-05 09:06:12','2025-11-05 09:29:57');
/*!40000 ALTER TABLE `configItems` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `configTypes`
--

DROP TABLE IF EXISTS `configTypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `configTypes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `config` varchar(255) DEFAULT NULL,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `config` (`config`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configTypes`
--

LOCK TABLES `configTypes` WRITE;
/*!40000 ALTER TABLE `configTypes` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `configTypes` VALUES
(1,'section','/content/','2025-10-21 16:43:01','2025-10-24 11:07:44'),
(2,'article','/content/article','2025-10-22 16:34:07','2025-10-22 16:34:07'),
(3,'contact','/content/contact','2025-10-25 16:45:19','2025-10-25 16:45:19'),
(4,'plan-du-site','/content/map','2025-10-26 14:56:36','2025-10-26 14:56:36'),
(5,'tag','/tag/','2025-11-04 14:01:31','2025-11-04 14:01:31');
/*!40000 ALTER TABLE `configTypes` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `contentItems`
--

DROP TABLE IF EXISTS `contentItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contentItems` (
  `contentId` bigint(20) NOT NULL,
  `itemId` bigint(20) NOT NULL,
  `order` int(11) DEFAULT 1,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`contentId`,`itemId`),
  KEY `contentItems_order_idx` (`order`),
  KEY `contentItems_items_fk` (`itemId`),
  CONSTRAINT `contentItems_contents_fk` FOREIGN KEY (`contentId`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `contentItems_items_fk` FOREIGN KEY (`itemId`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contentItems`
--

LOCK TABLES `contentItems` WRITE;
/*!40000 ALTER TABLE `contentItems` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `contentItems` VALUES
(1,1,0,'2025-10-22 14:02:36','2025-11-07 11:03:39'),
(1,2,2,'2025-10-22 14:46:40','2025-11-07 11:03:32'),
(1,3,3,'2025-10-22 14:49:27','2025-11-07 11:03:29'),
(1,14,4,'2025-10-23 16:15:37','2025-11-07 11:03:25'),
(1,129,1,'2025-11-07 11:00:04','2025-11-07 11:03:39'),
(3,8,0,'2025-10-23 14:49:04','2025-10-23 14:49:04'),
(3,9,1,'2025-10-23 14:55:32','2025-10-23 14:55:32'),
(3,10,2,'2025-10-23 15:01:32','2025-10-23 15:01:32'),
(3,11,4,'2025-10-23 15:03:25','2025-10-23 15:08:11'),
(3,12,3,'2025-10-23 15:08:06','2025-10-23 15:08:11'),
(3,19,5,'2025-10-23 16:49:06','2025-10-23 16:49:06'),
(3,21,6,'2025-10-23 16:59:55','2025-10-23 16:59:55'),
(3,22,7,'2025-10-23 17:02:40','2025-10-23 17:02:40'),
(5,15,0,'2025-10-23 16:30:09','2025-10-23 16:30:09'),
(5,16,2,'2025-10-23 16:31:08','2025-11-04 14:14:06'),
(5,17,3,'2025-10-23 16:31:53','2025-11-04 14:13:56'),
(5,18,4,'2025-10-23 16:32:36','2025-11-04 14:13:54'),
(5,104,5,'2025-10-26 14:09:06','2025-11-04 14:13:49'),
(5,110,1,'2025-11-04 14:10:01','2025-11-04 14:14:06'),
(6,90,0,'2025-10-25 15:40:13','2025-10-25 15:40:13'),
(6,91,1,'2025-10-25 15:40:42','2025-10-25 15:40:42'),
(7,23,0,'2025-10-24 11:10:30','2025-10-24 11:10:30'),
(7,25,1,'2025-10-24 11:15:23','2025-10-24 11:15:23'),
(7,26,2,'2025-10-24 11:18:25','2025-10-24 11:18:25'),
(7,27,3,'2025-10-24 11:21:54','2025-10-24 11:21:54'),
(7,28,4,'2025-10-24 11:28:00','2025-10-24 11:28:00'),
(7,31,5,'2025-10-24 11:43:09','2025-10-24 11:43:09'),
(7,56,6,'2025-10-25 10:51:09','2025-10-25 10:51:09'),
(7,76,7,'2025-10-25 14:07:49','2025-10-25 14:07:49'),
(8,29,0,'2025-10-24 11:38:40','2025-10-24 11:38:40'),
(8,30,1,'2025-10-24 11:39:01','2025-10-24 11:39:01'),
(8,40,2,'2025-10-24 14:07:42','2025-10-24 14:07:42'),
(8,47,3,'2025-10-24 15:53:42','2025-10-24 15:53:42'),
(9,32,0,'2025-10-24 11:47:28','2025-10-24 11:47:28'),
(9,33,1,'2025-10-24 11:47:58','2025-10-24 11:47:58'),
(9,35,2,'2025-10-24 11:52:55','2025-10-24 11:54:45'),
(9,36,3,'2025-10-24 11:56:47','2025-10-24 11:56:47'),
(10,37,0,'2025-10-24 13:52:13','2025-10-24 13:52:13'),
(10,38,1,'2025-10-24 13:53:22','2025-10-24 13:53:22'),
(10,39,2,'2025-10-24 13:57:09','2025-10-24 13:57:09'),
(10,41,3,'2025-10-24 15:36:41','2025-10-24 15:36:41'),
(10,44,4,'2025-10-24 15:44:39','2025-10-24 15:44:39'),
(10,45,5,'2025-10-24 15:46:33','2025-10-24 15:46:33'),
(10,50,6,'2025-10-25 09:49:37','2025-10-25 09:49:37'),
(10,51,7,'2025-10-25 09:58:44','2025-10-25 09:58:44'),
(11,46,0,'2025-10-24 15:53:04','2025-10-24 15:53:04'),
(11,48,1,'2025-10-24 15:55:47','2025-10-24 15:55:47'),
(11,53,2,'2025-10-25 10:06:47','2025-10-25 10:06:47'),
(11,54,3,'2025-10-25 10:18:20','2025-10-25 10:18:20'),
(12,55,0,'2025-10-25 10:50:26','2025-10-25 10:50:26'),
(12,71,1,'2025-10-25 13:54:23','2025-10-25 13:54:23'),
(12,118,2,'2025-11-04 15:25:55','2025-11-04 15:25:55'),
(13,57,1,'2025-10-25 10:55:11','2025-10-25 13:04:07'),
(13,58,2,'2025-10-25 11:16:39','2025-10-25 13:04:04'),
(13,59,3,'2025-10-25 11:29:57','2025-10-25 13:04:02'),
(13,61,4,'2025-10-25 11:40:32','2025-10-25 13:03:59'),
(13,62,5,'2025-10-25 11:44:18','2025-10-25 13:03:57'),
(13,63,6,'2025-10-25 11:46:58','2025-10-25 13:03:54'),
(13,64,7,'2025-10-25 11:48:42','2025-10-25 13:03:51'),
(13,65,8,'2025-10-25 11:50:03','2025-10-25 13:03:47'),
(13,66,0,'2025-10-25 13:03:41','2025-10-25 13:04:07'),
(14,67,0,'2025-10-25 13:06:52','2025-10-25 13:06:52'),
(14,68,1,'2025-10-25 13:08:06','2025-10-25 13:08:06'),
(14,69,2,'2025-10-25 13:11:19','2025-10-25 13:11:19'),
(14,70,3,'2025-10-25 13:15:39','2025-10-25 13:15:39'),
(16,75,0,'2025-10-25 14:06:38','2025-10-25 14:06:38'),
(16,83,1,'2025-10-25 14:48:57','2025-10-25 14:48:57'),
(16,87,2,'2025-10-25 15:08:03','2025-10-25 15:08:03'),
(17,77,0,'2025-10-25 14:39:16','2025-10-25 14:39:16'),
(17,78,1,'2025-10-25 14:39:40','2025-10-25 14:39:40'),
(17,79,2,'2025-10-25 14:41:21','2025-10-25 14:41:21'),
(17,80,3,'2025-10-25 14:42:54','2025-10-25 14:42:54'),
(17,81,4,'2025-10-25 14:45:10','2025-10-25 14:45:10'),
(17,82,5,'2025-10-25 14:47:54','2025-10-25 14:47:54'),
(17,84,6,'2025-10-25 14:51:11','2025-10-25 14:51:11'),
(18,85,0,'2025-10-25 14:56:38','2025-10-25 14:56:38'),
(18,86,1,'2025-10-25 15:01:56','2025-10-25 15:01:56'),
(18,88,2,'2025-10-25 15:14:33','2025-10-25 15:14:33'),
(18,89,3,'2025-10-25 15:27:39','2025-10-25 15:27:39'),
(19,93,0,'2025-10-25 15:47:59','2025-10-25 15:47:59'),
(19,94,1,'2025-10-25 15:49:33','2025-10-25 15:49:33'),
(19,95,2,'2025-10-25 15:52:57','2025-10-25 15:52:57'),
(19,96,3,'2025-10-25 15:55:44','2025-10-25 15:55:44'),
(19,97,4,'2025-10-25 15:58:32','2025-10-25 15:58:32'),
(19,98,5,'2025-10-25 16:05:14','2025-10-25 16:05:14'),
(19,99,6,'2025-10-25 16:08:34','2025-10-25 16:08:34'),
(20,100,0,'2025-10-25 16:39:47','2025-10-25 16:39:47'),
(20,101,1,'2025-10-25 16:40:07','2025-10-25 16:40:07'),
(22,103,0,'2025-10-26 11:35:36','2025-10-26 11:35:36'),
(22,105,1,'2025-10-26 14:19:57','2025-10-26 14:19:57'),
(22,106,2,'2025-10-26 14:42:48','2025-10-26 14:42:48'),
(22,107,3,'2025-10-26 14:43:11','2025-10-26 14:43:11'),
(23,108,0,'2025-10-26 14:56:55','2025-10-26 14:56:55'),
(24,109,0,'2025-11-04 14:05:12','2025-11-04 14:05:12'),
(24,114,1,'2025-11-04 15:01:24','2025-11-04 15:01:24'),
(24,115,2,'2025-11-04 15:03:13','2025-11-04 15:03:13'),
(24,117,3,'2025-11-04 15:12:14','2025-11-04 15:12:14'),
(25,119,0,'2025-11-04 15:42:09','2025-11-04 15:42:09'),
(25,120,1,'2025-11-04 15:47:09','2025-11-04 15:47:09'),
(25,121,2,'2025-11-04 15:51:20','2025-11-04 15:51:20'),
(25,122,3,'2025-11-04 15:53:24','2025-11-04 15:53:24'),
(25,123,4,'2025-11-04 15:55:04','2025-11-04 15:55:04'),
(25,124,5,'2025-11-04 16:40:41','2025-11-04 16:40:41'),
(25,125,6,'2025-11-04 16:44:37','2025-11-04 16:44:37'),
(25,126,7,'2025-11-04 16:51:45','2025-11-04 16:51:45'),
(25,127,8,'2025-11-04 16:52:43','2025-11-04 16:52:43');
/*!40000 ALTER TABLE `contentItems` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `contentTags`
--

DROP TABLE IF EXISTS `contentTags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contentTags` (
  `contentId` bigint(20) NOT NULL,
  `tagId` bigint(20) NOT NULL,
  `order` int(11) DEFAULT 1,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`contentId`,`tagId`),
  KEY `contentTags_order_idx` (`order`),
  KEY `contentTags_tags_fk` (`tagId`),
  CONSTRAINT `contentTags_contents_fk` FOREIGN KEY (`contentId`) REFERENCES `contents` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `contentTags_tags_fk` FOREIGN KEY (`tagId`) REFERENCES `tags` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contentTags`
--

LOCK TABLES `contentTags` WRITE;
/*!40000 ALTER TABLE `contentTags` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `contentTags` VALUES
(24,1,1,'2025-11-05 11:56:18','2025-11-05 11:56:18'),
(25,1,1,'2025-11-05 11:00:48','2025-11-05 11:00:48');
/*!40000 ALTER TABLE `contentTags` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slugId` bigint(20) DEFAULT NULL,
  `seoId` bigint(20) DEFAULT NULL,
  `configTypeId` bigint(20) DEFAULT NULL,
  `type` enum('section','article') NOT NULL,
  `pathKey` varchar(255) NOT NULL,
  `active` tinyint(1) DEFAULT 0,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contents_type_pathKey_idx` (`type`,`pathKey`),
  UNIQUE KEY `name` (`name`),
  KEY `contents_configTypeId_fk` (`configTypeId`),
  KEY `contents_seoId_fk` (`seoId`),
  KEY `contents_slugId_fk` (`slugId`),
  CONSTRAINT `contents_configTypeId_fk` FOREIGN KEY (`configTypeId`) REFERENCES `configTypes` (`id`),
  CONSTRAINT `contents_seoId_fk` FOREIGN KEY (`seoId`) REFERENCES `seos` (`id`),
  CONSTRAINT `contents_slugId_fk` FOREIGN KEY (`slugId`) REFERENCES `slugs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contents`
--

LOCK TABLES `contents` WRITE;
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `contents` VALUES
(1,'accueil',1,1,1,'section','1',1,'2025-10-21 13:54:13','2025-11-07 11:27:54'),
(3,'installation',3,3,2,'article','1.1.2',1,'2025-10-23 14:47:47','2025-10-27 09:53:14'),
(5,'fractal CMS',5,5,1,'section','1.1',1,'2025-10-23 15:40:51','2025-11-05 09:08:18'),
(6,'blog FractalCms',6,6,1,'section','1.2',1,'2025-10-23 15:43:24','2025-10-27 09:19:47'),
(7,'documentation complète',7,7,1,'section','1.1.1',1,'2025-10-24 11:09:19','2025-10-27 09:12:02'),
(8,'Configurations',8,8,1,'section','1.1.1.1',1,'2025-10-24 11:36:53','2025-10-27 09:12:57'),
(9,'Gestion des paramètres',9,9,2,'article','1.1.1.1.1',1,'2025-10-24 11:46:01','2025-10-27 09:13:49'),
(10,'Configuration des éléments',10,10,2,'article','1.1.1.1.2',1,'2025-10-24 13:50:54','2025-10-27 09:14:39'),
(11,'gestion des types d\'article',11,11,2,'article','1.1.1.1.3',1,'2025-10-24 15:51:31','2025-10-27 09:14:55'),
(12,'Contenus',12,12,1,'section','1.1.1.2',1,'2025-10-25 10:48:45','2025-11-05 11:53:05'),
(13,'Gestion des articles',13,13,2,'article','1.1.1.2.1',1,'2025-10-25 10:53:44','2025-10-27 09:16:56'),
(14,'Gestion des utilisateurs',14,14,2,'article','1.1.1.1.4',1,'2025-10-25 13:06:05','2025-10-25 14:37:49'),
(16,'Sujets avancés',16,16,1,'section','1.1.1.3',1,'2025-10-25 14:06:04','2025-10-27 09:17:59'),
(17,'Gestion des menus',17,17,2,'article','1.1.1.3.1',1,'2025-10-25 14:38:14','2025-10-27 09:17:29'),
(18,'Personnalisations',18,18,2,'article','1.1.1.3.2',1,'2025-10-25 14:55:30','2025-11-04 08:27:31'),
(19,'Blog FractalCMS: installation rapide',19,19,2,'article','1.2.1',1,'2025-10-25 15:45:25','2025-10-27 20:40:41'),
(20,'Contacter moi',20,20,3,'article','1.1',1,'2025-10-25 16:39:22','2025-10-27 10:00:40'),
(22,'A propos de WebCraftDG',22,22,2,'article','1.2',1,'2025-10-26 11:35:07','2025-10-27 10:27:06'),
(23,'sitemap',23,23,4,'article','1.3',1,'2025-10-26 14:53:05','2025-10-27 09:08:09'),
(24,'gestion des étiquettes (Tags)',24,24,2,'article','1.1.1.2.2',1,'2025-11-04 14:02:20','2025-11-05 11:56:18'),
(25,'Select Beautiful',26,26,2,'article','1.1.1.2.3',1,'2025-11-04 15:32:21','2025-11-05 11:00:48');
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `configItemId` bigint(20) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_configItemId_fk` (`configItemId`),
  CONSTRAINT `items_configItemId_fk` FOREIGN KEY (`configItemId`) REFERENCES `configItems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `items_chk_1` CHECK (json_valid(`data`))
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `items` VALUES
(1,1,1,'\"{\\\"title\\\":\\\"WebCraftDG\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Développeur passionné, j’ai créé FractalCMS comme un challenge personnel et une alternative simple et modulaire aux CMS existants.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/1/accueil.webp\\\",\\\"altbanner\\\":\\\"Image accueil\\\",\\\"imgCard\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"Découvrir FractalCMS\\\",\\\"target1\\\":\\\"/cms/content-5\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"voir le site blog avec Fractal CMS\\\",\\\"target2\\\":\\\"/cms/content-6\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-22 14:02:36','2025-11-05 09:18:48'),
(2,2,1,'\"{\\\"title\\\":\\\"A propos de WebCraftDG\\\",\\\"ctatitle\\\":\\\"En savoir plus sur WebCraftDG\\\",\\\"target\\\":\\\"/cms/content-22\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Pourquoi WebCraftDG ?, je vais essayer de répondre à des questions que je me suis posé et peut-être intéressé des visiteurs </p>\\\",\\\"image\\\":\\\"@webroot/data/items/2/apropos.webp\\\",\\\"altimage\\\":\\\"Image a propos de WebCraftDG\\\"}\"','2025-10-22 14:46:40','2025-10-27 09:46:51'),
(3,2,1,'\"{\\\"title\\\":\\\"FractalCMS\\\",\\\"ctatitle\\\":\\\"Découvrir FractalCMS\\\",\\\"target\\\":\\\"/cms/content-5\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>FractalCMS, je l\'ai conçu par challenge, une alternative simple aux CMS de marché, j\'ai voulu simplement réaliser un produit facile à prendre en main et configurer et par dessus tout, garder mon autonomie.</p>\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-22 14:49:27','2025-10-27 09:46:51'),
(8,1,1,'\"{\\\"title\\\":\\\"Installation rapide\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Avant de démarrer avec FractalCMS, assurez-vous d’avoir les bons prérequis techniques. Cette partie détaille les étapes nécessaires pour installer le CMS sur votre machine ou votre serveur.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/8/installation.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"Documentation complète\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"Voir le site blog avec FractalCMS\\\",\\\"target2\\\":\\\"/cms/content-6\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-23 14:49:04','2025-10-27 09:53:14'),
(9,3,1,'\"{\\\"title\\\":\\\"Prérequis techniques\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Backend</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><a href=\\\\\\\"https://www.php.net/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">PHP</a> &gt;= 8.2</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><a href=\\\\\\\"https://www.yiiframework.com/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">YiiFramwork</a> &gt;= 2.0</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Mariadb / Mysql</li></ol><p><br></p><h3>Frontend</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Nodejs :v24.8.0</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Nmp :11.6.0</li></ol>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-23 14:55:32','2025-10-26 07:53:11'),
(10,3,1,'\"{\\\"title\\\":\\\"Installation via Composer\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Avant tout, il faut partir d\'une application <a href=\\\\\\\"https://www.yiiframework.com/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">YIIframework</a> en créant un projet <a href=\\\\\\\"https://www.yiiframework.com/doc/guide/2.0/en/start-installation\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">Yii2</a>.</p><pre><code class=\\\\\\\"language-bash\\\\\\\">composer require dghyse\\\\\\\\fractal-cms</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-23 15:01:32','2025-10-26 07:53:11'),
(11,3,1,'\"{\\\"title\\\":\\\"Configuration du .env\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\"># prod | test | dev\\\\r\\\\nYII_ENV=prod\\\\r\\\\n# If debug is needed define YII DEBUG\\\\r\\\\nYII_DEBUG=0\\\\r\\\\n# If maintenance mode is needed define YII_MAINTENANCE\\\\r\\\\nYII_MAINTENANCE=0\\\\r\\\\n# Define the cookie validation key\\\\r\\\\nYII_COOKIE_VALIDATION_KEY=XXX\\\\r\\\\n# define the hostnames that are allowed to forward X-Forwarded-* header\\\\r\\\\n# Application version\\\\r\\\\nAPP_VERSION=1.0.0\\\\r\\\\n# Application mode\\\\r\\\\nAPP_ENV=prod\\\\r\\\\n\\\\r\\\\nDB_PORT=3306\\\\r\\\\nDB_HOST=localhost\\\\r\\\\nDB_DATABASE=votrebase\\\\r\\\\nDB_USER=user\\\\r\\\\nDB_PASSWORD=pwd\\\\r\\\\nDB_DRIVER=mysql</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-23 15:03:25','2025-10-26 07:53:11'),
(12,3,1,'\"{\\\"title\\\":\\\"Création d\'une base de données\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\">create database votrebase  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-23 15:08:06','2025-10-26 07:53:11'),
(14,2,1,'\"{\\\"title\\\":\\\"Site Blog avec FractalCMS\\\",\\\"ctatitle\\\":\\\"Voir le site blog avec FractalCMS\\\",\\\"target\\\":\\\"/cms/content-6\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Par la suite, j\'ai imaginé un produit qui pourrait créé de manière autonome un site basé sur une structure simple de FractalCMS et qui peut être modifié au bon vouloir de l\'utilisateur.</p>\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-23 16:15:37','2025-10-27 09:46:51'),
(15,1,1,'\"{\\\"title\\\":\\\"FractalCMS\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Développeur passionné, j’ai créé FractalCMS comme un challenge personnel et une alternative simple et modulaire aux CMS existants.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/15/cms.webp\\\",\\\"altbanner\\\":\\\"image  FractalCMS\\\",\\\"imgCard\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"Installation rapide de FractalCMS\\\",\\\"target1\\\":\\\"/cms/content-3\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"Voir la documentation complète\\\",\\\"target2\\\":\\\"/cms/content-7\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-23 16:30:09','2025-11-05 09:08:18'),
(16,2,1,'\"{\\\"title\\\":\\\"Qu\'est ce que FractalCMS ?\\\",\\\"ctatitle\\\":\\\"Lien vers le Github de FractalCMS\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"https://github.com/dghyse/fractal-cms\\\",\\\"description\\\":\\\"<h3><strong>FractalCMS</strong> est un CMS léger conçu pour gérer du contenu hiérarchisé de manière flexible et performante.</h3><p>Son principe fondateur repose sur une arborescence fractionnelle, permettant de représenter et manipuler des contenus imbriqués à profondeur illimitée, tout en gardant une structure simple et interrogeable en SQL.</p><p><br></p><h3>Cas d\'usage</h3><p><br></p><p>Avec <strong>FractalCMS, </strong>vous pouvez réaliser du site le plus simple (blog, site perso) à des sites plus complexes au limite de votre imagination.<strong> </strong></p>\\\",\\\"image\\\":\\\"@webroot/data/items/16/questceque_cms.webp\\\",\\\"altimage\\\":\\\"Quest-ce-que FractalCMS\\\"}\"','2025-10-23 16:31:08','2025-10-27 09:49:05'),
(17,2,1,'\"{\\\"title\\\":\\\"Pourquoi ce projet ?\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Voilà, j\'y arrive !! Mais, David ? ça t\'arrive de faire des projets perso ?</p><p>J\'ai pris du temps à me mettre un coup de pied au c.., mais avec l\'expérience acquise depuis mon changement d\'orientation professionnel en 2012, je me suis dit: \\\\\\\"toi aussi, tu peux faire des trucs mec !!\\\\\\\".</p><p>Je devais faire un site me présentant et pour cela, on m\'a appris à faire les choses bien. donc je me retrouve à chercher un CMS qui puisse m\'aider pour mon site. et en y réfléchissant, j\'ai pensé que le mieux pour présenter ce que je sais faire, et bien, c\'est de faire !!</p><p>Cela m\'a permis de surtout de pas être dépendant des autres, et faire faire un CMS à taille humaine.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/17/pourquoicms.webp\\\",\\\"altimage\\\":\\\"pourquoi ce projet\\\"}\"','2025-10-23 16:31:53','2025-10-26 11:54:29'),
(18,2,1,'\"{\\\"title\\\":\\\"Cas d\'usage - Site Blog avec FractalCMS\\\",\\\"ctatitle\\\":\\\"Voir le cas d\'usage site blog avec FractalCMS\\\",\\\"target\\\":\\\"/cms/content-6\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Avec <strong>FractalCMS, </strong>vous pouvez réaliser du site le plus simple (blog, site perso) à des sites plus complexes au limite de votre imagination.<strong> </strong></p>\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-23 16:32:36','2025-11-04 13:56:03'),
(19,3,1,'\"{\\\"title\\\":\\\"Paramétrage de l\'application\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Dans le fichier de configuration PHP.</p><pre><code class=\\\\\\\"language-php\\\\\\\">\'bootstrap\' =&gt; [\\\\r\\\\n        \'fractal-cms\',\\\\r\\\\n        //../..\\\\r\\\\n    ],\\\\r\\\\n    \'modules\' =&gt; [\\\\r\\\\n        \'fractal-cms\' =&gt; [\\\\r\\\\n            \'class\' =&gt; FractalCmsModule::class\\\\r\\\\n        ],\\\\r\\\\n        //../..\\\\r\\\\n    ],</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-23 16:49:06','2025-10-26 07:53:11'),
(21,3,1,'\"{\\\"title\\\":\\\"Initialiser Fractal CMS\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\">php yii.php migrate\\\\r\\\\nphp yii.php fractalCms:rbac/index\\\\r\\\\nphp yii.php fractalCms:admin/create\\\\r\\\\nphp yii.php fractalCms:init/index</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-23 16:59:55','2025-10-26 07:53:11'),
(22,3,1,'\"{\\\"title\\\":\\\"Accès au BackOffice\\\",\\\"image\\\":\\\"@webroot/data/items/22/Capture_d_e_cran_2025-10-23_a_17.05.29.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Avec les identifiants créés précédemment</p><pre><code class=\\\\\\\"language-bash\\\\\\\">https://localhost:8080/fractal-cms</code></pre>\\\",\\\"ctatitle\\\":\\\"Voir la documentation Complète de FractalCMS\\\",\\\"target\\\":\\\"/cms/content-7\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"bottom\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-23 17:02:40','2025-10-27 09:50:01'),
(23,1,1,'\"{\\\"title\\\":\\\"Documentation complète\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>FractalCMS repose sur quelques notions fondamentales. Ici, vous découvrirez les contenus, les menus, la gestion des rôles (RBAC) et les éléments qui structurent votre site.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/23/documentation.webp\\\",\\\"altbanner\\\":\\\"Image Documentation Complète FractalCMS\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-24 11:10:30','2025-10-27 09:12:02'),
(25,2,1,'\"{\\\"title\\\":\\\"Présentation\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>FractalCMS est un CMS léger conçu pour gérer du contenu hiérarchisé de manière flexible et performante.</p><p>Son principe fondateur repose sur une arborescence fractionnelle, permettant de représenter et manipuler des contenus imbriqués à profondeur illimitée, tout en gardant une structure simple et interrogeable en SQL.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/25/pre_sentation_intro.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 11:15:23','2025-10-26 13:26:15'),
(26,2,1,'\"{\\\"title\\\":\\\"Objectifs\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>FractalCMS n’a pas vocation à concurrencer les solutions existantes comme WordPress ou Drupal.</p><p>Il s’agit avant tout d’un projet personnel, pensé comme un terrain d’expérimentation pour :</p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>tester des idées d’architecture,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>conserver la main sur les choix techniques,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>et disposer d’un outil léger, adapté à un blog, site, portfolio développeur.</li></ol>\\\",\\\"image\\\":\\\"@webroot/data/items/26/objectifs.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 11:18:25','2025-10-26 13:16:31'),
(27,2,1,'\"{\\\"title\\\":\\\"Stack utilisée\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Backend : <a href=\\\\\\\"https://www.php.net/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">PHP</a> + <a href=\\\\\\\"https://www.mysql.com/fr/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">MySQL</a> / <a href=\\\\\\\"https://mariadb.org/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">MariaDb</a></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><a href=\\\\\\\"https://www.yiiframework.com/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">Yiiframework</a></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Frontend : <a href=\\\\\\\"https://docs.aurelia.io/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">Aurelia 2</a> + <a href=\\\\\\\"https://getbootstrap.com/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">BootstrapCSS</a></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Éditeur : <a href=\\\\\\\"(https://github.com/josdejong/jsoneditor\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">JSONEditor</a> / <a href=\\\\\\\"https://quilljs.com/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">QuillJS</a> pour la gestion des contenus</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Accessibilité : Gestion du SEO</li></ol>\\\",\\\"image\\\":\\\"@webroot/data/items/27/stacks.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 11:21:54','2025-10-26 13:03:05'),
(28,2,1,'\"{\\\"title\\\":\\\"Exemple d’utilisation\\\",\\\"ctatitle\\\":\\\"Blog avec FractalCMS\\\",\\\"target\\\":\\\"/cms/content-6\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Vous voulez un site fonctionnel prêt en quelques minutes ?  </p><p><br></p><p>Cet article contient un blog clé en main :</p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Installation rapide</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Articles et menus déjà créés</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Documentation intégrée</li></ol>\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 11:28:00','2025-10-26 12:52:50'),
(29,1,1,'\"{\\\"title\\\":\\\"Configurations\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Avant toutes création de contenu, il est nécessaire de paramétrer quelques concepts, les paramètres, la configuration des éléments, la configuration des articles </p>\\\",\\\"banner\\\":\\\"@webroot/data/items/29/configurration.webp\\\",\\\"altbanner\\\":\\\"Image page configuration\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-24 11:38:40','2025-10-27 09:12:57'),
(30,2,1,'\"{\\\"title\\\":\\\"Gestion des paramètres\\\",\\\"ctatitle\\\":\\\"Documentation\\\",\\\"target\\\":\\\"/cms/content-9\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Nous avons parfois besoin de récupérer des identifiants, des valeurs, des informations etc...</p><p>La gestion des paramètres est utile dans ces cas précis où nous devons indiquer une valeur qui pourrait évoluer</p><p>selon l\'environnement dans lequel nous sommes.</p><p><br></p><p><strong>Exemple</strong> : Identifiant technique différent entre la <strong>production</strong> et la <strong>pré-producio</strong>n.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/30/gestion_parametres_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 11:39:01','2025-10-26 13:47:37'),
(31,2,1,'\"{\\\"title\\\":\\\"Configurations\\\",\\\"ctatitle\\\":\\\"Configurer FractalCMS\\\",\\\"target\\\":\\\"/cms/content-8\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Avant de commencer à créer, il faut paramétrer ...</p>\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 11:43:09','2025-10-26 12:52:50'),
(32,1,1,'\"{\\\"title\\\":\\\"Gestion des paramètres\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Commençons par paramétrer FractalCMS avant de pouvoir nous amuser</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/32/gestion_parametres_art.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-24 11:47:28','2025-10-26 13:31:46'),
(33,3,1,'\"{\\\"title\\\":\\\"Interface\\\",\\\"image\\\":\\\"@webroot/data/items/33/parametre_interface.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Interface de création et mise à jour des paramètres</p><p><br></p><h3>Editer / Ajouter</h3><p><br></p><p>L\'édition d\'un article se réalise en cliquant sur le <strong>stylet</strong> de la ligne.</p><p>La création se réalise en cliquant sur le bouton <strong>Ajouter</strong>.</p>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"top\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-24 11:47:58','2025-10-26 13:31:35'),
(35,3,1,'\"{\\\"title\\\":\\\"Formulaire de création\\\",\\\"image\\\":\\\"@webroot/data/items/35/parametre_creer.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Groupe</strong> : Groupe principal du paramètre</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Nom</strong> : Nom du paramètre</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Valeur</strong> : Valeur du paramètre (string ou integer) ...</li></ol>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"bottom\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-24 11:52:55','2025-10-26 13:31:35'),
(36,3,1,'\"{\\\"title\\\":\\\"Utilisation des paramètres\\\",\\\"image\\\":\\\"@webroot/data/items/36/parametre_creation_entete.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-php\\\\\\\">   public function actionIndex()\\\\r\\\\n    {\\\\r\\\\n        try {\\\\r\\\\n            Yii::debug(\'Trace :\'.__METHOD__, __METHOD__);\\\\r\\\\n            $content = $this-&gt;getContent();\\\\r\\\\n            //Recherche du premier élément \\\\\\\"entete\\\\\\\" du \\\\\\\"Content\\\\\\\"\\\\r\\\\n            $itemEntete = $content-&gt;getItems()\\\\r\\\\n                -&gt;andWhere([\'configItemId\' =&gt; Cms::getParameter(\'ITEM\', \'ENTETE\')])\\\\r\\\\n                -&gt;one();\\\\r\\\\n            return $this-&gt;render(\'index\',\\\\r\\\\n                [\\\\r\\\\n                    \'content\' =&gt; $content,\\\\r\\\\n                    \'entete\' =&gt; $itemEntete,\\\\r\\\\n                    ]);\\\\r\\\\n        } catch (Exception $e) {\\\\r\\\\n            Yii::error($e-&gt;getMessage(), __METHOD__);\\\\r\\\\n            throw $e;\\\\r\\\\n        }\\\\r\\\\n    }</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-24 11:56:47','2025-10-26 13:31:35'),
(37,1,1,'\"{\\\"title\\\":\\\"Configuration des éléments\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Tous les articles peuvent avoir des éléments. Ces éléments permettent de définir les informations qui seront utilisées pour générer le HTML finale.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/37/gestion_contenus_art.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-24 13:52:13','2025-10-26 13:34:47'),
(38,3,1,'\"{\\\"title\\\":\\\"Interface\\\",\\\"image\\\":\\\"@webroot/data/items/38/item_interface.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Interface pour l\'ajout d\'une configuration</p><p><br></p><h3>Editer / Ajouter</h3><p><br></p><p>L\'édition d\'un article se réalise en cliquant sur le <strong>stylet</strong> de la ligne.</p><p>La création se réalise en cliquant sur le bouton <strong>Ajouter</strong>.</p>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"top\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-24 13:53:22','2025-10-26 13:34:38'),
(39,3,1,'\"{\\\"title\\\":\\\"Formulaire de création\\\",\\\"image\\\":\\\"@webroot/data/items/39/item_formulaire.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Champs du formulaire</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Nom</strong> : nom de la configuration, cette valeur doit-être unique</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong><a href=\\\\\\\"https://github.com/josdejong/jsoneditor\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">Configuration Json</a></strong> : Ajout des attributs et leur définition qui sera utiliser pour générer le HTML de l\'élément dans l\'article et définir les attributs à utiliser</li></ol><p><br></p><h3>Paramétrage d\'un attribut</h3><p><br></p><p>Chaque attribut doit comporter au moins ces paramètres pour être utilisable.</p><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Son nom</strong> : nom de l\'objet intitulé de l\'attribut dans model final</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Type</strong> : type de l\'attribut</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Title</strong> : intitulé à afficher dans l\'article</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Description</strong> : Description rapide expliquant l\'utilisation</li></ol><p><br></p><h3>Types d\'attribut autorisés</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>\\\\\\\"<strong>string</strong>\\\\\\\" : Champ input type text</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>\\\\\\\"<strong>text</strong>\\\\\\\" : Champ input type textarea</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>\\\\\\\"<strong>file</strong>\\\\\\\" : champ input type file</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span> \\\\\\\"<strong>radio</strong>\\\\\\\" : champ input type radio</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span> \\\\\\\"<strong>checkbox</strong>\\\\\\\" : champ input type checkbox</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>\\\\\\\"<strong>wysiwyg</strong>\\\\\\\" : champ input type text avec une interface <strong><a href=\\\\\\\"https://quilljs.com\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">wysiwyg</a></strong></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>\\\\\\\"<strong>listcms</strong>\\\\\\\" : champ input dropdown list contenant les articles de FractalCMS ainsi que les<strong> controller/action</strong></li></ol><h3>Exemple </h3><pre><code class=\\\\\\\"language-javascript\\\\\\\">{\\\\r\\\\n  \\\\\\\"title\\\\\\\": {\\\\r\\\\n      \\\\\\\"type\\\\\\\": \\\\\\\"string\\\\\\\",\\\\r\\\\n      \\\\\\\"title\\\\\\\" : \\\\\\\"Titre de la section\\\\\\\"\\\\r\\\\n  }\\\\r\\\\n}</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-24 13:57:09','2025-10-26 13:34:38'),
(40,2,1,'\"{\\\"title\\\":\\\"Configuration des éléments\\\",\\\"ctatitle\\\":\\\"Documentation\\\",\\\"target\\\":\\\"/cms/content-10\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Tous les articles peuvent avoir des éléments. Ces éléments permettent de définir les informations</p><p>qui seront utilisées pour générer le HTML finale.</p><p><br></p><p>Chaque élément doit-être configuré avant de pouvoir être visible dans l\'article.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/40/gestion_contenus_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 14:07:42','2025-10-26 13:34:10'),
(41,3,1,'\"{\\\"title\\\":\\\"Exemple de création d\'une configuration\\\",\\\"image\\\":\\\"@webroot/data/items/41/item_creation.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Création d\'une configuration <strong>entete</strong>.</p><p><br></p><pre><code class=\\\\\\\"language-javascript\\\\\\\">{\\\\r\\\\n  \\\\\\\"title\\\\\\\": {\\\\r\\\\n    \\\\\\\"type\\\\\\\": \\\\\\\"string\\\\\\\",\\\\r\\\\n    \\\\\\\"title\\\\\\\": \\\\\\\"Titre\\\\\\\"\\\\r\\\\n  },\\\\r\\\\n  \\\\\\\"subtitle\\\\\\\": {\\\\r\\\\n    \\\\\\\"type\\\\\\\": \\\\\\\"string\\\\\\\",\\\\r\\\\n    \\\\\\\"title\\\\\\\": \\\\\\\"Sous-titre\\\\\\\"\\\\r\\\\n  },\\\\r\\\\n  \\\\\\\"description\\\\\\\": {\\\\r\\\\n    \\\\\\\"type\\\\\\\": \\\\\\\"wysiwyg\\\\\\\",\\\\r\\\\n    \\\\\\\"title\\\\\\\": \\\\\\\"Description\\\\\\\"\\\\r\\\\n  },\\\\r\\\\n  \\\\\\\"banner\\\\\\\": {\\\\r\\\\n    \\\\\\\"type\\\\\\\": \\\\\\\"file\\\\\\\",\\\\r\\\\n    \\\\\\\"title\\\\\\\": \\\\\\\"image en 1200x250\\\\\\\",\\\\r\\\\n    \\\\\\\"accept\\\\\\\": \\\\\\\"png, jpeg, jpg\\\\\\\"\\\\r\\\\n  },\\\\r\\\\n  \\\\\\\"alt\\\\\\\": {\\\\r\\\\n    \\\\\\\"type\\\\\\\": \\\\\\\"string\\\\\\\",\\\\r\\\\n    \\\\\\\"title\\\\\\\": \\\\\\\"Alt de l\'image\\\\\\\"\\\\r\\\\n  }\\\\r\\\\n}</code></pre>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"top\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-24 15:36:41','2025-10-26 13:34:38'),
(44,4,1,'\"{\\\"title\\\":\\\"Utilisation dans l\'article\\\",\\\"image\\\":\\\"@webroot/data/items/44/item_entete_ajout.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Choix et ajout de l\'élément dans l\'article</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-24 15:44:39','2025-10-26 13:34:38'),
(45,4,1,'\"{\\\"title\\\":\\\"\\\",\\\"image\\\":\\\"@webroot/data/items/45/item_entet_ajout.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Désormais, l\'élément peut-être configuré et enregistré. les informations pourront être utilisées sur le <strong>front</strong>.</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-24 15:46:33','2025-10-26 13:34:38'),
(46,1,1,'\"{\\\"title\\\":\\\"Gestion des types d\'article\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Le configuration du type d\'élément faite partie des concepts important de FractalCMS. C\'est grâce à cette configuration qu\'un article (Content) pourra être dirigé vers le bon Controller et la bonne  Action et permettre ainsi de construire une vue adapté à vos besoin.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/46/gestion_types_art.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-24 15:53:03','2025-10-26 13:38:22'),
(47,2,1,'\"{\\\"title\\\":\\\"Configuration des types d\'article\\\",\\\"ctatitle\\\":\\\"Documentation\\\",\\\"target\\\":\\\"/cms/content-11\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Le configuration du type d\'élément faite partie des concepts important de FractalCMS. C\'est grâce à cette configuration qu\'un article (Content) pourra être dirigé vers le bon Controller et la bonne  Action et permettre ainsi de construire une vue adapté à vos besoin.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/47/gestion_types_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-24 15:53:42','2025-10-26 13:38:00'),
(48,3,1,'\"{\\\"title\\\":\\\"Interface\\\",\\\"image\\\":\\\"@webroot/data/items/48/interface_config_element.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Prérequis</h3><p><br></p><p>Dans votre application Yii. Ajouté un <strong>Controller</strong> qui étant <strong>fractalCms\\\\\\\\controllers\\\\\\\\CmsController</strong>, dans lequel vous allez créer l\'action désirée.</p><pre><code class=\\\\\\\"language-php\\\\\\\">namespace webapp\\\\\\\\controllers;\\\\r\\\\n\\\\r\\\\nuse fractalCms\\\\\\\\controllers\\\\\\\\CmsController;\\\\r\\\\nuse fractalCms\\\\\\\\models\\\\\\\\Content;\\\\r\\\\nuse Yii;\\\\r\\\\nuse Exception;\\\\r\\\\n\\\\r\\\\n/**\\\\r\\\\n * ContentController class\\\\r\\\\n *\\\\r\\\\n * @author David Ghyse &lt;dghyse@redcat.fr&gt;\\\\r\\\\n * @version XXX\\\\r\\\\n * @package webapp\\\\\\\\controllers\\\\r\\\\n * @since XXX\\\\r\\\\n */\\\\r\\\\nclass ContentController extends CmsController\\\\r\\\\n{\\\\r\\\\n\\\\r\\\\n    /**\\\\r\\\\n     * @return \\\\\\\\yii\\\\\\\\web\\\\\\\\Response|string\\\\r\\\\n     * @since XXX\\\\r\\\\n     */\\\\r\\\\n    public function actionIndex()\\\\r\\\\n    {\\\\r\\\\n        try {\\\\r\\\\n            Yii::debug(\'Trace :\'.__METHOD__, __METHOD__);\\\\r\\\\n            /** Content $content **/\\\\r\\\\n            $content = $this-&gt;getContent();\\\\r\\\\n            $itemsQuery = $content-&gt;getItems();\\\\r\\\\n            return $this-&gt;render(\'index\',\\\\r\\\\n                [\\\\r\\\\n                    \'content\' =&gt; $content,\\\\r\\\\n                    \'sections\' =&gt; $sections\\\\r\\\\n                    ]);\\\\r\\\\n        } catch (Exception $e) {\\\\r\\\\n            Yii::error($e-&gt;getMessage(), __METHOD__);\\\\r\\\\n            throw $e;\\\\r\\\\n        }\\\\r\\\\n    }\\\\r\\\\n}</code></pre><h3>Editer / Ajouter</h3><p><br></p><p>L\'édition d\'un article se réalise en cliquant sur le <strong>stylet</strong> de la ligne.</p><p>La création se réalise en cliquant sur le bouton <strong>Ajouter</strong>.</p>\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\",\\\"code\\\":\\\"\\\"}\"','2025-10-24 15:55:47','2025-10-25 10:16:44'),
(50,4,1,'\"{\\\"title\\\":\\\"Utilisation dans e controlleur\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Dans le controleur</h3><p><br></p><pre><code class=\\\\\\\"language-php\\\\\\\">public function actionIndex()\\\\r\\\\n{\\\\r\\\\n    try {\\\\r\\\\n        Yii::debug(\'Trace :\'.__METHOD__, __METHOD__);\\\\r\\\\n        $content = $this-&gt;getContent();\\\\r\\\\n        $itemEntete = $content-&gt;getItems()-&gt;andWhere([\'configItemId\' =&gt; Cms::getParameter(\'ITEM\', \'ENTETE\')])-&gt;one();\\\\r\\\\n        $itemsQuery = $content-&gt;getItems()-&gt;andWhere([\\\\r\\\\n            \'not\', [\'configItemId\' =&gt; [\\\\r\\\\n                Cms::getParameter(\'ITEM\', \'ENTETE\'),\\\\r\\\\n                ]]]);\\\\r\\\\n        return $this-&gt;render(\'index\',\\\\r\\\\n            [\\\\r\\\\n                \'content\' =&gt; $content,\\\\r\\\\n                \'entete\' =&gt; $itemEntete,\\\\r\\\\n                ]);\\\\r\\\\n    } catch (Exception $e) {\\\\r\\\\n        Yii::error($e-&gt;getMessage(), __METHOD__);\\\\r\\\\n        throw $e;\\\\r\\\\n    }\\\\r\\\\n}</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 09:49:37','2025-10-26 13:34:38'),
(51,4,1,'\"{\\\"title\\\":\\\"utilisation dans la vue (index)\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-php\\\\\\\">&lt;?php\\\\r\\\\n/**\\\\r\\\\n * main.php\\\\r\\\\n *\\\\r\\\\n * PHP Version 8.2+\\\\r\\\\n *\\\\r\\\\n * @version XXX\\\\r\\\\n * @package webapp\\\\\\\\views\\\\\\\\layouts\\\\r\\\\n *\\\\r\\\\n * @var $this yii\\\\\\\\web\\\\\\\\View\\\\r\\\\n * @var $content \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Content\\\\r\\\\n * @var $entete \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item\\\\r\\\\n */\\\\r\\\\nuse fractalCms\\\\\\\\helpers\\\\\\\\Html;\\\\r\\\\n\\\\r\\\\n$title = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;title : $content-&gt;name;\\\\r\\\\n$subtitle = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;subtitle : null;\\\\r\\\\n$banner = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;banner : null;\\\\r\\\\n$description = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;description : null;\\\\r\\\\n$this-&gt;title = trim(($content?-&gt;seo?-&gt;title) ?? $title);</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 09:58:44','2025-10-26 13:34:38'),
(53,4,1,'\"{\\\"title\\\":\\\"Formulaire de création\\\",\\\"image\\\":\\\"@webroot/data/items/53/config_article_creer.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>A ce stade, nous voyons apparaitre la liste des types qui ont été ajoutés dans l\'application. Nous allons créé le type <strong>home</strong> que nous allons diriger vers le contrôleur <strong>ContentController</strong> et l\'action <strong>actionIndex</strong>.</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 10:06:47','2025-10-26 13:38:14'),
(54,4,1,'\"{\\\"title\\\":\\\"Dans la liste\\\",\\\"image\\\":\\\"@webroot/data/items/54/config_article_list.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Désormais, tout article ou section qui aura le type <strong>home</strong> sera dirigé vers l\'action <strong>actionIndex</strong> du contrôleur <strong>ContentController</strong>.</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 10:18:20','2025-10-26 13:38:14'),
(55,1,1,'\"{\\\"title\\\":\\\"Les contenus\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Dans cette documentation nous allons voir le coeur de FractalCMS, la création des articles et leur utilisation sur le front</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/55/contenus.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"imgCard\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 10:50:26','2025-11-05 11:39:02'),
(56,2,1,'\"{\\\"title\\\":\\\"Les contenus\\\",\\\"ctatitle\\\":\\\"Créer des contenus\\\",\\\"target\\\":\\\"/cms/content-12\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Dans cette documentation nous allons voir le coeur de FractalCMS, la création des articles et leur utilisation sur le front</p>\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-25 10:51:09','2025-10-26 12:52:50'),
(57,3,1,'\"{\\\"title\\\":\\\"Interface\\\",\\\"image\\\":\\\"@webroot/data/items/57/article_list.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Prérequis</h3><p><br></p><p>Avant de créer un article (<strong>Content</strong>), veuillez veuillez vous rendre dans la documentation <strong>Gestion des types d\'article </strong></p><h3><br></h3><h3>Définition</h3><p>Les articles suivent une structure définie par l\'attribut <strong>pathKey</strong>. Lors de l\'initialisation de FractalCMS le <strong>Content</strong> \\\\\\\"<strong>main</strong>\\\\\\\" a été créé. Tous les autres <strong>Content</strong> devront être des enfants ou petits enfants de \\\\\\\"<strong>main</strong>\\\\\\\". Dans la pratique le <strong>Content</strong> \\\\\\\"<strong>main</strong>\\\\\\\" est la section qui va définir la page <strong>accueil</strong> du site.</p><p><br></p><h3>Editer / Ajouter</h3><p><br></p><p>L\'édition d\'un article se réalise en cliquant sur le <strong>stylet</strong> de la ligne.</p><p>La création se réalise en cliquant sur le bouton <strong>Ajouter</strong>.</p>\\\",\\\"ctatitle\\\":\\\"Gestion des types d\'article\\\",\\\"target\\\":\\\"/cms/content-11\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 10:55:11','2025-10-25 13:03:24'),
(58,4,1,'\"{\\\"title\\\":\\\"Formulaire de création : Identification de l\'article\\\",\\\"image\\\":\\\"@webroot/data/items/58/article_partie_h.png\\\",\\\"altimage\\\":\\\"création article partie haute\\\",\\\"description\\\":\\\"<h3>Les attributs</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Actif</strong> : l\'article doit-être actif pour être visible sur le front</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Nom</strong> : Nom de l\'article (cette valeur doit être unique dans le site)</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Configuration de l\'article</strong> : liste de choix liée aux configurations créés</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Model</strong> : indique si l\'article est une <strong>section</strong> ou un <strong>article</strong></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Parent</strong> : Hiérarchie de l\'article</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Url</strong> : Url à partir de laquelle l\'article sera accessible sur le front</li></ol><p><br></p><h3>Définitions</h3><p><br></p><h4><strong>Configuration de l\'article</strong></h4><p><br></p><p>Cette option permet de définir vers quelle <strong>contrôleur/action</strong> l\'url de l\'article sera dirigé afin</p><p>de construire la vue et l\'envoyer au Front.</p><p><br></p><h4><strong>Model</strong></h4><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Section : </strong>Modèle du plus haut niveau dans la hiérarchie, il peut appartenir à une autre <strong>section</strong> mais ne peut-être sous un article.</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Article :</strong>Modèle du plus bas niveau dans la hiérarchie, il ne peut-être que l\'enfant d\'un modèle <strong>section</strong></li></ol><p><br></p><h4><strong>Hiérarchie et url</strong></h4><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Parent</strong> : Position dans la hiérarchie de FractalCMS.</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Url (valeur unique)</strong> : L\'url est le point d\'entrée sur le front (navigateur) afin d\'accéder à la page de l\'article</li></ol>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 11:16:39','2025-10-25 11:26:37'),
(59,4,1,'\"{\\\"title\\\":\\\"Formulaire de création : SEO\\\",\\\"image\\\":\\\"@webroot/data/items/59/article_seo.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Introduction</h3><p><br></p><p>Le SEO, défini les valeurs à indiquer dans la page afin de valoriser des données récupérées pour l\'indéxation du site. </p><p>FractalCMS met à disposition le <strong>behavior</strong> <strong>fractalCms\\\\\\\\behaviors\\\\\\\\Seo</strong> qui peut-être utilisé afin de générer le SEO.</p><p><br></p><h3>Les attributs</h3><p><br></p><p><strong>Actif</strong> : Activé le SEO</p><p><strong>Titre</strong> : titre visible sur l\'onglet du navigateur</p><p><strong>Description</strong> : valeur du <strong>meta name:description</strong></p><p><strong>Image</strong> : valeur du <strong>meta</strong> <strong>name:image</strong></p><p><strong>Sitemap</strong> : paramètre inscrit dans le <strong>sitemap.xml</strong> pour l\'url de cette article</p><p><strong>Meta données</strong> : Permet d\'activer les <strong>metas</strong> données supplémentaire</p><p><br></p><h4>Sitemap</h4><p><br></p><p>FractalCMS met à disposition l\'action<strong> fractalCms\\\\\\\\actions\\\\\\\\SitemapAction</strong> qui se charge de générer le fichier sitemap.xml.</p><p><br></p><h4>Meta données</h4><p><br></p><p>Le jsonLd doit-être créé selon les articles et le contexte du site, c\'est à vous de le construire.</p><p><br></p><h4>Exemple utilisation du Behavior <strong>fractalCms\\\\\\\\behaviors\\\\\\\\Seo</strong></h4><p><br></p><p><strong>Dans le contrôleur ajouter</strong></p><p><br></p><pre><code class=\\\\\\\"language-php\\\\\\\">public function behaviors()\\\\r\\\\n{\\\\r\\\\n    $behaviors = parent::behaviors();\\\\r\\\\n    $behaviors[\'seo\'] = [\\\\r\\\\n        \'class\' =&gt; Seo::class\\\\r\\\\n    ];\\\\r\\\\n    return $behaviors;\\\\r\\\\n}</code></pre><p><br></p><p>Si le SEO est activé dans les paramètre du formulaire de l\'article. Les données SEO seront automatiquement</p><p>insérées.</p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 11:29:57','2025-10-25 11:37:13'),
(61,3,1,'\"{\\\"title\\\":\\\"Formulaire de création : Gestion des éléments\\\",\\\"image\\\":\\\"@webroot/data/items/61/article_gestion_elements.png\\\",\\\"altimage\\\":\\\"Formulaire de création et de gestion des éléments\\\",\\\"description\\\":\\\"<p>Ici, ce trouve la coeur de la page. chaque article qu\'il soit <strong>section</strong> ou <strong>article</strong> peut comporter des éléments. Chaque élément est basé sur une configuration voir <strong>Configuration des éléments</strong></p>\\\",\\\"ctatitle\\\":\\\"Configuration des éléments\\\",\\\"target\\\":\\\"/cms/content-10\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 11:40:32','2025-10-25 11:43:55'),
(62,4,1,'\"{\\\"title\\\":\\\"\\\",\\\"image\\\":\\\"@webroot/data/items/62/article_add_item.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Ajout d\'un élément</h3><p><br></p><p>Dans la liste en bas à droite, il faut sélectionner l\'élément choisi et cliquer sur <strong>+</strong>.</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 11:44:18','2025-10-25 11:46:22'),
(63,4,1,'\"{\\\"title\\\":\\\"\\\",\\\"image\\\":\\\"@webroot/data/items/63/item_entet_ajout.png\\\",\\\"altimage\\\":\\\"ajout élément entête\\\",\\\"description\\\":\\\"<p>L\'élément peut-être valorisé. Les informations enregistrées pourront être utilisées sur le <strong>FRONT</strong> de votre site.</p>\\\",\\\"direction\\\":\\\"bottom\\\"}\"','2025-10-25 11:46:58','2025-10-25 11:48:11'),
(64,4,1,'\"{\\\"title\\\":\\\"Récupération des données : dans le controlleur\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-php\\\\\\\">public function actionIndex()\\\\r\\\\n{\\\\r\\\\n    try {\\\\r\\\\n        Yii::debug(\'Trace :\'.__METHOD__, __METHOD__);\\\\r\\\\n        $content = $this-&gt;getContent();\\\\r\\\\n        //Recherche du premier élément \\\\\\\"entete\\\\\\\" du \\\\\\\"Content\\\\\\\"\\\\r\\\\n        $itemEntete = $content-&gt;getItems()\\\\r\\\\n            -&gt;andWhere([\'configItemId\' =&gt; Cms::getParameter(\'ITEM\', \'ENTETE\')])\\\\r\\\\n            -&gt;one();\\\\r\\\\n        return $this-&gt;render(\'index\',\\\\r\\\\n            [\\\\r\\\\n                \'content\' =&gt; $content,\\\\r\\\\n                \'entete\' =&gt; $itemEntete,\\\\r\\\\n                ]);\\\\r\\\\n    } catch (Exception $e) {\\\\r\\\\n        Yii::error($e-&gt;getMessage(), __METHOD__);\\\\r\\\\n        throw $e;\\\\r\\\\n    }\\\\r\\\\n}</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 11:48:42','2025-10-25 11:49:55'),
(65,4,1,'\"{\\\"title\\\":\\\"Récupération des données : Dans la vue\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-xml\\\\\\\">&lt;?php\\\\r\\\\n/**\\\\r\\\\n * main.php\\\\r\\\\n *\\\\r\\\\n * PHP Version 8.2+\\\\r\\\\n *\\\\r\\\\n * @version XXX\\\\r\\\\n * @package webapp\\\\\\\\views\\\\\\\\layouts\\\\r\\\\n *\\\\r\\\\n * @var $this yii\\\\\\\\web\\\\\\\\View\\\\r\\\\n * @var $content \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Content\\\\r\\\\n * @var $entete \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item\\\\r\\\\n *\\\\r\\\\n */\\\\r\\\\nuse fractalCms\\\\\\\\helpers\\\\\\\\Html;\\\\r\\\\n\\\\r\\\\n$title = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;title : $content-&gt;name;\\\\r\\\\n$subtitle = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;subtitle : null;\\\\r\\\\n$banner = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;banner : null;\\\\r\\\\n$description = ($entete instanceof \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item) ? $entete-&gt;description : null;\\\\r\\\\n$this-&gt;title = $title;\\\\r\\\\n\\\\r\\\\n?&gt;\\\\r\\\\n&lt;main id=\\\\\\\"main\\\\\\\" role=\\\\\\\"main\\\\\\\"  tabindex=\\\\\\\"-1\\\\\\\" portfolio-focus=\\\\\\\"main\\\\\\\"&gt;\\\\r\\\\n    &lt;!-- Hero avec image --&gt;\\\\r\\\\n    &lt;section id=\\\\\\\"home\\\\\\\" class=\\\\\\\"relative text-white\\\\\\\"&gt;\\\\r\\\\n        &lt;!-- Image de fond --&gt;\\\\r\\\\n        &lt;div class=\\\\\\\"absolute inset-0 h-72\\\\\\\"&gt;\\\\r\\\\n            &lt;?php\\\\r\\\\n            if (empty($banner) === false) {\\\\r\\\\n                echo Html::img($banner, [\\\\r\\\\n                    \'width\' =&gt; 1200, \'height\' =&gt; 300,\\\\r\\\\n                    \'alt\' =&gt; \'Image hero\',\\\\r\\\\n                    \'class\' =&gt; \'w-full h-full object-cover\'\\\\r\\\\n                ]);\\\\r\\\\n            }\\\\r\\\\n            ?&gt;\\\\r\\\\n            &lt;!-- Overlay --&gt;\\\\r\\\\n            &lt;div class=\\\\\\\"absolute inset-0 bg-blue-800 opacity-70\\\\\\\"&gt;&lt;/div&gt;\\\\r\\\\n        &lt;/div&gt;\\\\r\\\\n\\\\r\\\\n        &lt;!-- Contenu --&gt;\\\\r\\\\n        &lt;div class=\\\\\\\"relative container mx-auto px-6 h-72 flex flex-col justify-center items-center text-center\\\\\\\"&gt;\\\\r\\\\n            &lt;h1 class=\\\\\\\"text-3xl md:text-5xl font-extrabold\\\\\\\"&gt;&lt;?php echo $title;?&gt;&lt;/h1&gt;\\\\r\\\\n            &lt;div class=\\\\\\\"mt-2 text-lg text-blue-100\\\\\\\"&gt;\\\\r\\\\n                &lt;?php echo $description;?&gt;\\\\r\\\\n            &lt;/div&gt;\\\\r\\\\n        &lt;/div&gt;\\\\r\\\\n    &lt;/section&gt;\\\\r\\\\n&lt;/main&gt;</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 11:50:03','2025-10-25 11:51:17'),
(66,1,1,'\"{\\\"title\\\":\\\"Gestion des articles\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Les articles suivent une structure définie par l\'attribut pathKey. Lors de l\'initialisation de FractalCMS le Content \\\\\\\"main\\\\\\\" a été créé.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/66/gestion_elements_articles_art.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 13:03:41','2025-10-26 13:56:36'),
(67,1,1,'\"{\\\"title\\\":\\\"Gestion des utilisateurs\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Ajouter, gérer l\'accès au bas office de FractalCMS, ajouter ou supprimer des droits</p>\\\",\\\"banner\\\":\\\"\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 13:06:52','2025-10-25 13:10:53'),
(68,4,1,'\"{\\\"title\\\":\\\"Interface\\\",\\\"image\\\":\\\"@webroot/data/items/68/utilisateurs_liste.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Prérequis</h3><p><br></p><p>Afin d\'accéder pour la première fois à l\'interface administrateur, il faut créer un utilisateur. Pour cela, la commande suivante permet de créer cet utilisateur.</p><p><br></p><pre><code class=\\\\\\\"language-bash\\\\\\\">php yii.php fractalCms:admin/create</code></pre><h3><br></h3><h3>Editer / Ajouter</h3><p><br></p><p>L\'édition d\'un utilisateur se réalise en cliquant sur le stylet de la ligne.</p><p>La création se réalise en cliquant sur le bouton \'Ajouter\'.</p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 13:08:06','2025-10-25 13:10:53'),
(69,4,1,'\"{\\\"title\\\":\\\"Formulaire de création : utilisateur, nom, prénom\\\",\\\"image\\\":\\\"@webroot/data/items/69/utilisateur_nom_prenom.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Les attributs</h3><p><br></p><p>Dans cette interface, il faut indiquer.</p><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>identifiant (email) </strong>: Email de connexion</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>nom</strong> : Nom de l\'utilisateur</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>prénom</strong> : Prénom de l\'utilisateur</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>mot de passe</strong> : Le mot de passe est soumit à des règles de validations indiquées dans le formulaire.</li></ol><p><br></p><p>La case à cocher <strong>Activer </strong>permet d\'activer ou désactiver un utilisateur. tant que l\'utilisateur n\'est pas activer, il ne pourra</p><p>pas se connecter.</p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 13:11:19','2025-10-25 13:17:16'),
(70,4,1,'\"{\\\"title\\\":\\\"Formulaire de création : Les permissions\\\",\\\"image\\\":\\\"@webroot/data/items/70/utilisateur_droits.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>La configuration des droits et permissions permet de restreindre l\'accès à certaines fonctionnalités de FractalCMS aux utilisateurs.</p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 13:15:39','2025-10-25 13:16:47'),
(71,2,1,'\"{\\\"title\\\":\\\"Gestion des articles\\\",\\\"ctatitle\\\":\\\"voir la documentation\\\",\\\"target\\\":\\\"/cms/content-13\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Les articles permettent la construction de la structure de votre site. A vous d\'adapter le code de votre site afin d\'être en accord avec votre vision.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/71/gestion_elements_articles_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-25 13:54:23','2025-11-04 15:27:38'),
(75,1,1,'\"{\\\"title\\\":\\\"sujets avancés\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>FractalCMS offre des fonctionnalités afin de vous aider à construite une structure opérationnelle</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/75/sujets-avances.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 14:06:38','2025-10-26 13:00:00'),
(76,2,1,'\"{\\\"title\\\":\\\"Sujets avancés\\\",\\\"ctatitle\\\":\\\"Aller plus loin\\\",\\\"target\\\":\\\"/cms/content-16\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>FractalCMS offre des fonctionnalités afin de vous aider à construite une structure opérationnelle</p>\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-25 14:07:49','2025-10-26 12:52:50'),
(77,1,1,'\"{\\\"title\\\":\\\"Gestion des menus\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Dans FractalCMS, il est possible de créer des menus. c\'est menu pourront être ensuite récupéré sur le site et affiché sur la page.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/77/gestion_menus_art.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 14:39:16','2025-10-26 14:00:19'),
(78,4,1,'\"{\\\"title\\\":\\\"Interface\\\",\\\"image\\\":\\\"@webroot/data/items/78/menu_interface.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Editer / Ajouter</h3><p>L\'édition d\'un article se réalise en cliquant sur le <strong>stylet</strong> de la ligne. La création se réalise en cliquant sur le bouton <strong>Ajouter</strong>.</p><p><br></p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 14:39:40','2025-10-25 14:41:13'),
(79,4,1,'\"{\\\"title\\\":\\\"Création d\'un nouveau menu : étape 1\\\",\\\"image\\\":\\\"@webroot/data/items/79/menu_creer_etape1.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Lors de l\'ajout d\'un menu, il est demandé en étape 1 le nom de ce menu.</p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>actif</strong> : Permet d\'activer le menu</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span><strong>Nom</strong> : c\'est le nom qui permettra de le trouver, cette valeur est <strong>unique</strong></li></ol>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 14:41:21','2025-10-25 14:42:46'),
(80,4,1,'\"{\\\"title\\\":\\\"Création d\'un nouveau menu : étape 2\\\",\\\"image\\\":\\\"@webroot/data/items/80/menu_creer_etape2.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Après avoir saisie un nom unique et valider le formulaire. Le formulaire ce met à jour afin de permettre d\'ajouter des <strong>élements du menu</strong>,  en cliquant sur le bouton <strong>Ajouter un élément</strong>.</p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 14:42:54','2025-10-25 14:44:40'),
(81,4,1,'\"{\\\"title\\\":\\\"Création d\'un nouveau menu : ajout d\'un élément\\\",\\\"image\\\":\\\"@webroot/data/items/81/menu_creer_un_element.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Les attributs du formulaire</p><p><br></p><p><strong>Nom</strong> : Nom de l\'élément</p><p><strong>Route CMS </strong>: Lien vers un article actif de <strong>FractalCMS</strong></p><p><strong>Route locale</strong> : Lien vers une action d\'un contrôleur hors <strong>FractalCMS</strong> de votre application Web</p><p><strong>Parent</strong> : L\'élément créé peut-être un enfant d\'un autre élément.</p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 14:45:10','2025-10-25 14:47:22'),
(82,4,1,'\"{\\\"title\\\":\\\"Création d\'un nouveau menu : Exemple créer menu \\\\\\\"header\\\\\\\"\\\",\\\"image\\\":\\\"@webroot/data/items/82/menu_creer_ajout_element_accueil.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 14:47:54','2025-10-25 14:51:49'),
(83,2,1,'\"{\\\"title\\\":\\\"Gestion des menus\\\",\\\"ctatitle\\\":\\\"En savoir plus\\\",\\\"target\\\":\\\"/cms/content-17\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Dans <strong>FractalCMS</strong>, il est possible de créer des menus. ce chapitre explique comment faire.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/83/gestion_menus_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-25 14:48:57','2025-10-26 14:00:41'),
(84,4,1,'\"{\\\"title\\\":\\\"Récupérer le menu sur votre application Web\\\",\\\"image\\\":\\\"@webroot/data/items/84/menu_creer_header.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Il est possible d\'adapter la donnée de retour afin de correspondre à votre logique.</p><pre><code class=\\\\\\\"language-php\\\\\\\">{\\\\r\\\\n    try {\\\\r\\\\n        $result = [];\\\\r\\\\n            $menuId = Cms::getParameter(\'MENU\', $name);\\\\r\\\\n            $menu = Menu::findOne($menuId);\\\\r\\\\n            if ($menu instanceof Menu) {\\\\r\\\\n                $menuItemsQuery = $menu-&gt;getMenuItemChild();\\\\r\\\\n                $result = $this-&gt;build($menuItemsQuery);\\\\r\\\\n            }\\\\r\\\\n        return $result;\\\\r\\\\n    } catch (Exception $e) {\\\\r\\\\n        Yii::error($e-&gt;getMessage(), __METHOD__);\\\\r\\\\n        throw $e;\\\\r\\\\n    }\\\\r\\\\n}\\\\r\\\\n\\\\r\\\\nprotected function build(ActiveQuery $menuItemsQuery)\\\\r\\\\n{\\\\r\\\\n    try {\\\\r\\\\n        $result  = [];\\\\r\\\\n        /** @var MenuItem $menuItem */\\\\r\\\\n        foreach ($menuItemsQuery-&gt;each() as $menuItem) {\\\\r\\\\n            $part = [];\\\\r\\\\n            $contentTarget = $menuItem-&gt;getContent()-&gt;andWhere([\'active\' =&gt; 1])-&gt;one();\\\\r\\\\n            if ($contentTarget instanceof Content || empty($menuItem-&gt;route) === false) {\\\\r\\\\n                $route = ($contentTarget !== null) ? $contentTarget-&gt;getRoute() : $menuItem-&gt;route;\\\\r\\\\n                $part[\'name\'] = $menuItem-&gt;name;\\\\r\\\\n                $part[\'route\'] = $route;\\\\r\\\\n                $subMenuQuery = $menuItem-&gt;getMenuItems();\\\\r\\\\n                if ($subMenuQuery-&gt;count() &gt; 0 ) {\\\\r\\\\n                    $part[\'child\'] = $this-&gt;build($menuItem-&gt;getMenuItems());\\\\r\\\\n                }\\\\r\\\\n                $result[] = $part;\\\\r\\\\n            }\\\\r\\\\n        }\\\\r\\\\n        return $result;\\\\r\\\\n    } catch (Exception $e) {\\\\r\\\\n        Yii::error($e-&gt;getMessage(), __METHOD__);\\\\r\\\\n        throw $e;\\\\r\\\\n    }\\\\r\\\\n\\\\r\\\\n}</code></pre><p><br></p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-10-25 14:51:11','2025-10-25 14:54:17'),
(85,1,1,'\"{\\\"title\\\":\\\"Personnalisation de la vue des éléments de l\'article\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Dans FractalCMS, nous pouvons personnaliser la vue qui sera utilisée pour générer le HTML de l\'élément dans la partie Gestion des éléments du formulaire de création d\'un article.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/85/personnaliser_art.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 14:56:38','2025-10-26 14:03:45'),
(86,4,1,'\"{\\\"title\\\":\\\"Propriété \\\\\\\"viewItemPath\\\\\\\"\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>La propriété public <strong>viewItemPath</strong> du module <strong>FractalCMS</strong> peut-être valorisé dans le fichier de configuration. La propriété est valorisée par défaut par  <strong>@webapp/views/fractal-cms</strong>.</p><p><br></p><pre><code class=\\\\\\\"language-php\\\\\\\">/* fichier de configuration */\\\\r\\\\n/*../..*/\\\\r\\\\n\'bootstrap\' =&gt; [\\\\r\\\\n    \'log\',\\\\r\\\\n    \'fractal-cms\'\\\\r\\\\n],\\\\r\\\\n\'modules\' =&gt; [\\\\r\\\\n    \'fractal-cms\' =&gt; [\\\\r\\\\n        \'class\' =&gt; FractalCmsModule::class,\\\\r\\\\n        \'viewItemPath\'=&gt; \'@webapp/views/fractal-cms\'\\\\r\\\\n    ]\\\\r\\\\n],\\\\r\\\\n/*../..*/</code></pre><p><br></p><p>Si une vue est détectée dans ce répertoire, c\'est elle qui sera utiliser dans le formulaire de création d\'un article partie <strong>Gestion des éléments</strong>.</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 15:01:56','2025-10-25 15:14:12'),
(87,2,1,'\"{\\\"title\\\":\\\"Personnalisations\\\",\\\"ctatitle\\\":\\\"Personnalisation de FractalCMS\\\",\\\"target\\\":\\\"/cms/content-18\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Dans ce chapitre, nous verrons les possibilités de personnalisés certains concepts de <strong>FractalCMS</strong>.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/87/personnaliser_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-25 15:08:03','2025-10-26 14:03:24'),
(88,4,1,'\"{\\\"title\\\":\\\"Règle de nommages des vues\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Le nom du fichier doit correspondre à la valeur de la propriété <strong>name</strong> de la<strong> Configuration de l\'élémen</strong>t. Les noms comportant des \\\\\\\"<strong>-\\\\\\\"</strong> seront automatiquement remplacés par des<strong> \\\\\\\"_\\\\\\\"</strong>.</p><p><br></p><h3>Exemple pour l\'entête</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Nom : <strong>entete</strong></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Valeur de la propriété name de la configuration : <strong>entete</strong></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Nom de la vue :<strong> entete.php</strong></li></ol><p><br></p><h3>Exemple pour Image HTML</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Nom :<strong> image-html</strong></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Valeur de la propriété <strong>name</strong> de la configuration : <strong>image-html</strong></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Nom de la vue : <strong>image_html.php</strong></li></ol>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 15:14:33','2025-10-25 15:18:58'),
(89,4,1,'\"{\\\"title\\\":\\\"Exemple du fichier image_html.php\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-php\\\\\\\">&lt;?php\\\\r\\\\n/**\\\\r\\\\n * image_html.php\\\\r\\\\n *\\\\r\\\\n * PHP Version 8.2+\\\\r\\\\n *\\\\r\\\\n * @version XXX\\\\r\\\\n * @package webapp\\\\\\\\views\\\\\\\\layouts\\\\r\\\\n *\\\\r\\\\n * @var $this yii\\\\\\\\web\\\\\\\\View\\\\r\\\\n * @var $model \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Item\\\\r\\\\n * @var $content \\\\\\\\fractalCms\\\\\\\\models\\\\\\\\Content\\\\r\\\\n */\\\\r\\\\n\\\\r\\\\nuse fractalCms\\\\\\\\helpers\\\\\\\\Html;\\\\r\\\\nuse yii\\\\\\\\helpers\\\\\\\\ArrayHelper;\\\\r\\\\nuse fractalCms\\\\\\\\helpers\\\\\\\\Cms;\\\\r\\\\n?&gt;\\\\r\\\\n&lt;?php\\\\r\\\\nforeach ($model-&gt;configItem-&gt;configArray as $attribute =&gt; $data):?&gt;\\\\r\\\\n    &lt;div class=\\\\\\\"col form-group p-0 mt-1\\\\\\\"&gt;\\\\r\\\\n        &lt;?php\\\\r\\\\n        $title = ($data[\'title\']) ?? \'\';\\\\r\\\\n        $description = ($data[\'description\']) ?? \'\';\\\\r\\\\n        $options = ($data[\'options\']) ?? null;\\\\r\\\\n        $accept = ($data[\'accept\']) ?? null;\\\\r\\\\n        switch ($data[\'type\']) {\\\\r\\\\n            case Html::CONFIG_TYPE_FILE:\\\\r\\\\n            case Html::CONFIG_TYPE_FILES:\\\\r\\\\n                echo Html::tag(\'cms-file-upload\', \'\', [\\\\r\\\\n                    \'title.bind\' =&gt; \'\\\\\\\\\'\'.$title.\'\\\\\\\\\'\',\\\\r\\\\n                    \'name\' =&gt; Html::getInputName($content, \'items[\'.$model-&gt;id.\'][\'.$attribute.\']\'),\\\\r\\\\n                    \'value\' =&gt; $model-&gt;$attribute,\\\\r\\\\n                    \'upload-file-text\' =&gt; \'Ajouter une fichier\',\\\\r\\\\n                    \'file-type\' =&gt; $accept\\\\r\\\\n                ]);\\\\r\\\\n                break;\\\\r\\\\n            case Html::CONFIG_TYPE_WYSIWYG:\\\\r\\\\n                echo Html::activeLabel($content, \'items[\'.$model-&gt;id.\'][\'.$attribute.\']\', [\'label\' =&gt; $title, \'class\' =&gt; \'form-label\']);\\\\r\\\\n                echo Html::activeHiddenInput($content, \'items[\'.$model-&gt;id.\'][\'.$attribute.\']\', [\'value\' =&gt; $model-&gt;$attribute, \'class\' =&gt; \'wysiwygInput\']);\\\\r\\\\n                $inputNameId = Html::getInputId($content, \'items[\'.$model-&gt;id.\'][\'.$attribute.\']\');\\\\r\\\\n                echo Html::tag(\'div\', \'\',\\\\r\\\\n                    [\\\\r\\\\n                        \'cms-wysiwyg-editor\' =&gt; \'input-id.bind:\\\\\\\\\'\'.$inputNameId.\'\\\\\\\\\'\',\\\\r\\\\n                    ]);\\\\r\\\\n                break;\\\\r\\\\n        }\\\\r\\\\n        ?&gt;\\\\r\\\\n    &lt;/div&gt;\\\\r\\\\n\\\\r\\\\n    &lt;?php if (empty($description) === false):?&gt;\\\\r\\\\n        &lt;div class=\\\\\\\"col p-0\\\\\\\"&gt;\\\\r\\\\n            &lt;p class=\\\\\\\"fw-lighter fst-italic\\\\\\\"&gt;\\\\r\\\\n                &lt;?php echo $description;?&gt;\\\\r\\\\n            &lt;/p&gt;\\\\r\\\\n        &lt;/div&gt;\\\\r\\\\n    &lt;?php endif;?&gt;\\\\r\\\\n&lt;?php endforeach;?&gt;</code></pre><p><br></p><p><strong>Les noms et ids des champs doivent être scrupuleusement respectés.</strong></p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 15:27:39','2025-11-04 08:27:31'),
(90,1,1,'\"{\\\"title\\\":\\\"Site Blog FractalCMS\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Exemple d\'utilisation de FracalCMS, site clé en main et auto configuré</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/90/blog.webp\\\",\\\"altbanner\\\":\\\"image Blog Fractal CMS\\\",\\\"ctatitle1\\\":\\\"Installation rapide\\\",\\\"target1\\\":\\\"/cms/content-19\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"FractalCMS\\\",\\\"target2\\\":\\\"/cms/content-5\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 15:40:13','2025-10-27 09:19:47'),
(91,2,1,'\"{\\\"title\\\":\\\"Blog clé en main\\\",\\\"ctatitle\\\":\\\"Installation rapide\\\",\\\"target\\\":\\\"/cms/content-19\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Ce projet est basé sur <strong>FractalCMS</strong>.  Installation rapide en quelques commandes. Permet en peu de temps de créer un site WEB sur un squelette fonctionnel complet.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/91/installation_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-25 15:40:42','2025-10-26 13:11:31'),
(93,1,1,'\"{\\\"title\\\":\\\"Blog avec FractalCMS : Installation rapide\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Installation rapide en quelques commandes, permet la création d\'un site Web contenu un CMS léger et fonctionnel.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/93/installation.webp\\\",\\\"altbanner\\\":\\\"Image Blog avec FractalCMS - installation rapide\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 15:47:59','2025-10-27 09:21:10'),
(94,4,1,'\"{\\\"title\\\":\\\"Etape 1 : composer : Installer le projet\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\">composer create-project dghyse/blog-fractal-cms mon-blog</code></pre><p><br></p><h3>Ce dépôt contient un preset “Blog” :</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Installation en 5 commandes,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Création automatique des <strong>tables, des menus et des articles</strong>,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Contenu de départ intégré : <strong>une documentation directement lisible dans le blog</strong>,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Images, textes et structure prêts à l’emploi,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Un<strong> site clé en main</strong> immédiatement fonctionnel après installation.</li></ol>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 15:49:33','2025-10-25 16:02:01'),
(95,4,1,'\"{\\\"title\\\":\\\"Etape 2 : paramétrage du .env\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\"># prod | test | dev\\\\r\\\\nYII_ENV=prod\\\\r\\\\n# If debug is needed define YII DEBUG\\\\r\\\\nYII_DEBUG=0\\\\r\\\\n# If maintenance mode is needed define YII_MAINTENANCE\\\\r\\\\nYII_MAINTENANCE=0\\\\r\\\\n# Define the cookie validation key\\\\r\\\\nYII_COOKIE_VALIDATION_KEY=XXX\\\\r\\\\n# define the hostnames that are allowed to forward X-Forwarded-* header\\\\r\\\\n# Application version\\\\r\\\\nAPP_VERSION=1.0.0\\\\r\\\\n# Application mode\\\\r\\\\nAPP_ENV=prod\\\\r\\\\n\\\\r\\\\nDB_PORT=3306\\\\r\\\\nDB_HOST=localhost\\\\r\\\\nDB_DATABASE=blog_cms\\\\r\\\\nDB_USER=user\\\\r\\\\nDB_PASSWORD=MDP\\\\r\\\\nDB_DRIVER=mysql\\\\r\\\\nDB_SCHEMA_CACHE=1\\\\r\\\\nDB_SCHEMA_CACHE_DURATION=3600\\\\r\\\\nDB_SCHEMA=</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 15:52:57','2025-10-27 20:40:41'),
(96,4,1,'\"{\\\"title\\\":\\\"Etape 3 : paramétrage de l\'application\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Ce site est près à l\'emploi, le fichier <strong>common.php</strong> est déjà paramétré pour le fonctionnement correct du site.</p><p><br></p><pre><code class=\\\\\\\"language-php\\\\\\\">/*common/config/common.php*/\\\\r\\\\n\\\\r\\\\nuse fractalCms\\\\\\\\Module as FractalCmsModule;\\\\r\\\\nuse yii\\\\\\\\web\\\\\\\\View as YiiView;\\\\r\\\\nuse fractalCms\\\\\\\\components\\\\\\\\View;\\\\r\\\\n\\\\r\\\\n  \'container\' =&gt; [\\\\r\\\\n        \'definitions\' =&gt; [\\\\r\\\\n            YiiView::class =&gt; View::class\\\\r\\\\n        ],\\\\r\\\\n        \'singletons\' =&gt; [\\\\r\\\\n            CacheInterface::class =&gt; DummyCache::class,\\\\r\\\\n            Connection::class =&gt; [\\\\r\\\\n                \'charset\' =&gt; \'utf8\',\\\\r\\\\n                \'dsn\' =&gt; getstrenv(\'DB_DRIVER\').\':host=\' . getstrenv(\'DB_HOST\') . \';port=\' . getstrenv(\'DB_PORT\') . \';dbname=\' . getstrenv(\'DB_DATABASE\'),\\\\r\\\\n                \'username\' =&gt; getstrenv(\'DB_USER\'),\\\\r\\\\n                \'password\' =&gt; getstrenv(\'DB_PASSWORD\'),\\\\r\\\\n                \'tablePrefix\' =&gt; getstrenv(\'DB_TABLE_PREFIX\'),\\\\r\\\\n                \'enableSchemaCache\' =&gt; getboolenv(\'DB_SCHEMA_CACHE\'),\\\\r\\\\n                \'schemaCacheDuration\' =&gt; getintenv(\'DB_SCHEMA_CACHE_DURATION\'),\\\\r\\\\n            ],\\\\r\\\\n            \\\\\\\\webapp\\\\\\\\helpers\\\\\\\\MenuBuilder::class =&gt; [\\\\r\\\\n                \'class\' =&gt; \\\\\\\\webapp\\\\\\\\helpers\\\\\\\\MenuBuilder::class\\\\r\\\\n            ],\\\\r\\\\n            /*../..*/\\\\r\\\\n        ]\\\\r\\\\n    ],\\\\r\\\\n    \'bootstrap\' =&gt; [\\\\r\\\\n        \'fractal-cms\',\\\\r\\\\n        /*../..*/\\\\r\\\\n    ],\\\\r\\\\n    \'modules\' =&gt; [\\\\r\\\\n        \'fractal-cms\' =&gt; [\\\\r\\\\n            \'class\' =&gt; FractalCmsModule::class\\\\r\\\\n        ],\\\\r\\\\n        /*../..*/\\\\r\\\\n    ],</code></pre><p><br></p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 15:55:43','2025-10-25 16:02:01'),
(97,4,1,'\"{\\\"title\\\":\\\"Etape 4 : commandes de paramétrage\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\">php yii.php migrate\\\\r\\\\n/* Create Rbac */\\\\r\\\\nphp yii.php fractalCms:rbac/index\\\\r\\\\n/* Create Admin (suivre les instructions )*/\\\\r\\\\nphp yii.php fractalCms:admin/create\\\\r\\\\n/* INIT FractalCMs */\\\\r\\\\nphp yii.php fractalCms:init/index\\\\r\\\\n/* reate Blog */\\\\r\\\\nphp yii.php blog/build-cms-site</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 15:58:32','2025-10-25 16:02:01'),
(98,4,1,'\"{\\\"title\\\":\\\"Résultat attendu\\\",\\\"image\\\":\\\"@webroot/data/items/98/image_blog.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Une fois toutes les étapes terminées, ouvrez votre navigateur sur : <a href=\\\\\\\"http://localhost:8080\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">http://localhost:8080</a> <strong>(url relative à la configuration de votre serveur).</strong></p><p><br></p><h3>Vous obtiendrez un<strong> blog prêt à l’emploi</strong> avec :</h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>une page d’accueil déjà configurée,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>des articles créés automatiquement,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>un menu généré,</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>et une documentation intégrée directement dans le blog.</li></ol>\\\",\\\"direction\\\":\\\"bottom\\\"}\"','2025-10-25 16:05:14','2025-10-26 18:31:35'),
(99,4,1,'\"{\\\"title\\\":\\\"Accès au BackOffice de FractalCMS\\\",\\\"image\\\":\\\"@webroot/data/items/99/login.png\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Une fois toutes les étapes terminées, ouvrez votre navigateur sur :<a href=\\\\\\\" http://localhost:8080/fractal-cms\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\"> http://localhost:8080/fractal-cms</a> <strong>(url relative à la configuration de votre serveur)</strong>.</p><p>Les identifiants ont été créés précédemment avec le commande</p><pre><code class=\\\\\\\"language-bash\\\\\\\">php yii.php fractalCms:admin/create</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-25 16:08:34','2025-10-26 18:31:35'),
(100,1,1,'\"{\\\"title\\\":\\\"Me contacter via le formulaire\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"\\\",\\\"banner\\\":\\\"@webroot/data/items/100/contact.webp\\\",\\\"altbanner\\\":\\\"Image Contacter moi\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-25 16:39:47','2025-10-27 10:00:40'),
(101,5,1,'\"{\\\"form\\\":\\\"form-contact\\\",\\\"description\\\":\\\"<h3><strong>A propos</strong></h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Veuillez remplir ce formulaire avec votre email et votre demande.</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Vos informations ne sont pas enregistrées : elles servent uniquement à générer un email de confirmation et à me permettre de répondre à votre message.</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Après validation du formulaire, une email de confirmation vous sera envoyé.</li></ol><h3><br></h3><h3><strong>Protection anti-spam</strong></h3><p><br></p><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Ce site est protégé par reCAPTCHA. Les <strong><u><a href=\\\\\\\"https://policies.google.com/privacy\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">règles de confidentialité</a></u></strong> et <strong><u><a href=\\\\\\\"https://policies.google.com/terms\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">les conditions d’utilisation</a></u></strong><u> </u>de Google s’appliquent.</li></ol>\\\",\\\"captcha\\\":\\\"\\\"}\"','2025-10-25 16:40:07','2025-10-25 16:40:44'),
(103,1,1,'\"{\\\"title\\\":\\\"A propos de WebCraftDG\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Pourquoi WebCraftDG, je vais essayer de répondre à des questions que je me suis posé et peut-être intéressé des visiteurs </p>\\\",\\\"banner\\\":\\\"@webroot/data/items/103/apropos_art.webp\\\",\\\"altbanner\\\":\\\"A propos de WebCraftDG\\\",\\\"ctatitle1\\\":\\\"Découvrir FactalCMS\\\",\\\"target1\\\":\\\"/cms/content-5\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"Voir le site blog avec FractalCMS\\\",\\\"target2\\\":\\\"/cms/content-6\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-26 11:35:36','2025-10-27 10:27:06'),
(104,2,1,'\"{\\\"title\\\":\\\"A venir dans FractalCMS\\\",\\\"ctatitle\\\":\\\"\\\",\\\"target\\\":\\\"\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Dans mes prochaines évolutions</h3><ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Ajouter les tags et catégories (permettre de rechercher plusieurs éléments de <strong>FractalCMS</strong> selon un Tag (étiquette).</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Créer une API  RESTFul complète dans <strong>FractalCMS</strong></li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Ajouter les tests unitaires</li></ol>\\\",\\\"image\\\":\\\"@webroot/data/items/104/a_venir.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-10-26 14:09:06','2025-10-27 14:13:54'),
(105,4,1,'\"{\\\"title\\\":\\\"Prise de conscience\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Après plusieurs années passées en tant que développeur d’applications web dans une petite SSII, j’ai réalisé que mon travail était toujours dicté par les besoins des clients.</p><p>Je n’avais jamais pris le temps de créer quelque chose pour moi, ni de le construire exactement comme je l’entendais.</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-26 14:19:57','2025-10-26 14:42:41'),
(106,4,1,'\"{\\\"title\\\":\\\"Nouvelle situation\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Puis un jour, on a frappé à ma porte : le licenciement économique.</p><p>Je me suis retrouvé face à une page blanche, sans savoir quoi faire de mon avenir… et surtout de ce temps “libre”.</p><p><br></p><p>Je me suis dit : « Mince, David, t’es pas plus bête qu’un autre. Utilise tes compétences pour toi, fais ton site, mets-le en ligne ! »</p><p>Après tout, j’avais une solide expérience : concevoir un site de A à Z, configurer un serveur, paramétrer un DNS (rien de compliqué)… Pourquoi ne pas m’en servir ?</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-26 14:42:48','2025-10-26 14:43:07'),
(107,4,1,'\"{\\\"title\\\":\\\"Lancement\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>De là, l’idée a germé : utiliser un CMS pour créer mon site.</p><p>Je maîtrisais déjà un CMS que j’avais beaucoup utilisé, mais je le trouvais trop complexe pour un simple portfolio. Et puis, une pensée m’a traversé : « Ce serait amusant de créer mon propre CMS ! »</p><p><br></p><p>C’est ainsi qu’est né FractalCMS, développé en même temps que mon site portfolio.</p><p>Petit à petit, le projet a pris de l’ampleur : permettre à n’importe quel utilisateur de créer un site complet basé sur FractalCMS.</p><p>Et me voilà aujourd’hui avec ce site vitrine pour partager ce parcours et ce projet</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-10-26 14:43:11','2025-10-26 14:43:25'),
(108,1,1,'\"{\\\"title\\\":\\\"Plan du site\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"\\\",\\\"banner\\\":\\\"@webroot/data/items/108/plan-du-site_art.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-10-26 14:56:55','2025-10-26 14:57:11'),
(109,1,1,'\"{\\\"title\\\":\\\"Gestion des étiquettes (Tags)\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Les étiquettes permettent un rangement intuitif des contenus de fractalCMS. les articles peuvent être liés à une étiquette permettant de les classés, trouvés, répertoriés facilement.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/109/gestion_tag_article.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"imgCard\\\":\\\"@webroot/data/items/109/gestion_tag_card.webp\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-11-04 14:05:12','2025-11-05 11:56:18'),
(110,2,1,'\"{\\\"title\\\":\\\"Nouveauté version v1.6.1\\\",\\\"ctatitle\\\":\\\"Voir les nouveautés de la version v1.6.1\\\",\\\"target\\\":\\\"/cms/tag-1\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Ajout du système d\'<strong>étiquettes (tags)</strong> pour organiser les contenus.</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Amélioration du composant <strong>Select Beautiful</strong> (thèmes, gestion du multi, etc.).</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Documentation mise à jour.</li></ol>\\\",\\\"image\\\":\\\"@webroot/data/items/110/new_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-11-04 14:10:01','2025-11-04 17:10:08'),
(111,1,1,'\"{\\\"title\\\":\\\"Nouveauté version v1.6.1\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Ajout du système d\'<strong>étiquettes (tags)</strong> pour organiser les contenus.</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Amélioration du composant <strong>Select Beautiful</strong> (thèmes, gestion du multi, etc.).</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Documentation mise à jour.</li></ol><p><br></p>\\\",\\\"banner\\\":\\\"@webroot/data/items/111/new.webp\\\",\\\"altbanner\\\":\\\"image nouveauté\\\",\\\"imgCard\\\":\\\"\\\",\\\"ctatitle1\\\":\\\"\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-11-04 14:46:56','2025-11-05 09:21:16'),
(114,4,1,'\"{\\\"title\\\":\\\"Interface\\\",\\\"image\\\":\\\"@webroot/data/items/114/tag_interface.webp\\\",\\\"altimage\\\":\\\"image étiquette (tag) interface\\\",\\\"description\\\":\\\"<h3>Définition</h3><p><br></p><p>Dans FractalCMS, il est possible de créer des étiquettes (Tag).</p><p>Un contenu qu\'il soit une <strong>section</strong> ou un <strong>article</strong> peut-être lié à un ou plusieurs étiquettes.</p><p><br></p><h3>Editer / Ajouter</h3><p><br></p><p>L\'édition d\'une étiquette se réalise en cliquant sur le stylet de la ligne.</p><p>La création se réalise en cliquant sur le bouton \'Ajouter\'.</p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 15:01:24','2025-11-05 09:16:08'),
(115,3,1,'\"{\\\"title\\\":\\\"Formulaire de création (Partie haute)\\\",\\\"image\\\":\\\"@webroot/data/items/115/tag_form_partie_haute.webp\\\",\\\"altimage\\\":\\\"image formulaire de création partie haute\\\",\\\"description\\\":\\\"<p><strong>Actif</strong> : l\'étiquette doit-être actif pour être visible sur le front</p><p><strong>Nom</strong> : Nom de l\'étiquette (cette valeur doit être unique dans le site)</p><p><strong>Configuration de l\'étiquette</strong> : liste de choix liée aux configurations créés dans Configuration du type des articles</p><p><br></p><h3>Définition</h3><p><br></p><p><strong>Configuration de l\'étiquette (types d\'article)</strong></p><p><br></p><p>Cette option permet de définir vers quelle <strong>contrôleur/action</strong> l\'url de l\'étiquette sera dirigé afin</p><p>de construire la vue et l\'envoyer au Front.</p>\\\",\\\"ctatitle\\\":\\\"Voir les types d\'article\\\",\\\"target\\\":\\\"/cms/content-11\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-11-04 15:03:13','2025-11-04 15:09:32'),
(117,3,1,'\"{\\\"title\\\":\\\"Formulaire de création (reste du formulaire)\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Toutes les parties suivantes du formulaire fonctionnent comme le formulaire de création d\'un article. La documentation est disponible vers le lien suivant.</p>\\\",\\\"ctatitle\\\":\\\"voir la documentation de la gestion des articles\\\",\\\"target\\\":\\\"/cms/content-13\\\",\\\"url\\\":\\\"\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 15:12:14','2025-11-04 15:14:04'),
(118,2,1,'\"{\\\"title\\\":\\\"Gestion des étiquettes (Tags)\\\",\\\"ctatitle\\\":\\\"Voir la documentation\\\",\\\"target\\\":\\\"/cms/content-24\\\",\\\"url\\\":\\\"\\\",\\\"description\\\":\\\"<p>Les étiquettes permettent un rangement intuitif des contenus de fractalCMS.</p>\\\",\\\"image\\\":\\\"@webroot/data/items/118/gestion_tag_card.webp\\\",\\\"altimage\\\":\\\"\\\"}\"','2025-11-04 15:25:55','2025-11-05 11:39:02'),
(119,1,1,'\"{\\\"title\\\":\\\"Package \\\\\\\"Select beautiful\\\\\\\"\\\",\\\"subtitle\\\":\\\"\\\",\\\"description\\\":\\\"<p>Un composant Select Beautiful et accessible pour <a href=\\\\\\\"https://docs.aurelia.io/\\\\\\\" rel=\\\\\\\"noopener noreferrer\\\\\\\" target=\\\\\\\"_blank\\\\\\\">Aurelia 2</a>, conçu pour être simple, élégant et extensible.</p><p>Compatible multi-sélection, recherche intégrée, navigation clavier et ARIA live pour l’accessibilité.</p>\\\",\\\"banner\\\":\\\"@webroot/data/items/119/select_article.webp\\\",\\\"altbanner\\\":\\\"\\\",\\\"imgCard\\\":\\\"@webroot/data/items/119/select_card.webp\\\",\\\"ctatitle1\\\":\\\"Lien Github\\\",\\\"target1\\\":\\\"\\\",\\\"externurl1\\\":\\\"https://github.com/dghyse/aurelia-select-beautiful\\\",\\\"ctatitle2\\\":\\\"\\\",\\\"target2\\\":\\\"\\\",\\\"externurl2\\\":\\\"\\\"}\"','2025-11-04 15:42:09','2025-11-05 10:15:59'),
(120,4,1,'\"{\\\"title\\\":\\\"Installation\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\">npm install @fractalcms/aurelia-select-beautiful</code></pre><p><br></p><h3>Ajouter le composant dans dans l\'application Aurelia</h3><p><br></p><pre><code class=\\\\\\\"language-javascript\\\\\\\">import Aurelia from \'aurelia\';\\\\r\\\\nimport { MyApp } from \'./my-app\';\\\\r\\\\nimport { SelectBeautiful } from \'@fractalcms/aurelia-select-beautiful\';\\\\r\\\\n\\\\r\\\\nAurelia\\\\r\\\\n  .register(SelectBeautiful)\\\\r\\\\n  .app(MyApp)\\\\r\\\\n  .start();</code></pre><p><br></p><h3>Ajout des style CSS</h3><p><br></p><p>Le composant ne surcharge aucun style global et ne s’injecte pas automatiquement.</p><p>Pour appliquer ses styles, importez simplement la feuille CSS dans votre fichier global SCSS ou CSS :</p><p><br></p><pre><code class=\\\\\\\"language-css\\\\\\\">@import \\\\\\\"@fractalcms/aurelia-select-beautiful/dist/styles/select-beautiful.css\\\\\\\";</code></pre><p><br></p><p>Cette méthode est compatible avec Webpack, Vite et Aurelia CLI.</p><p>Elle garantit que vos styles de site ne sont jamais altérés.</p><p><br></p>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 15:47:09','2025-11-04 16:38:48'),
(121,4,1,'\"{\\\"title\\\":\\\"Exemple d\'utilisation\\\",\\\"image\\\":\\\"@webroot/data/items/121/image_select_green.webp\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<h3>Select multiple avec le thème \\\\\\\"green\\\\\\\"</h3><p><br></p><pre><code class=\\\\\\\"language-xml\\\\\\\">&lt;select multiple fractalcms-select-beautiful.bind=\\\\\\\"{ theme: \'green\', multiple: true }\\\\\\\"&gt;\\\\r\\\\n  &lt;option value=\\\\\\\"1\\\\\\\"&gt;Option 1&lt;/option&gt;\\\\r\\\\n  &lt;option value=\\\\\\\"2\\\\\\\"&gt;Option 2&lt;/option&gt;\\\\r\\\\n&lt;/select&gt;</code></pre><p><br></p><h3>Select simple </h3><p><br></p><pre><code class=\\\\\\\"language-xml\\\\\\\">&lt;select fractalcms-select-beautiful.bind=\\\\\\\"{multiple: false }\\\\\\\"&gt;\\\\r\\\\n  &lt;option value=\\\\\\\"1\\\\\\\"&gt;Option 1&lt;/option&gt;\\\\r\\\\n  &lt;option value=\\\\\\\"2\\\\\\\"&gt;Option 2&lt;/option&gt;\\\\r\\\\n&lt;/select&gt;</code></pre><p><br></p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-11-04 15:51:20','2025-11-05 10:57:28'),
(122,4,1,'\"{\\\"title\\\":\\\"Fonctionnalités incluses\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Recherche textuelle dynamique</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Navigation clavier (↑, ↓, Entrée, Échap)</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Sélection multiple avec suppression</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Accessibilité ARIA (live region, multiselect, etc.)</li></ol>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 15:53:24','2025-11-04 15:53:57'),
(123,4,1,'\"{\\\"title\\\":\\\"Thème CSS\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`soft` *(par défaut)* =&gt; Clair, doux et neutre               =&gt; `theme: \'soft\'`  </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`dark`                =&gt; Fond sombre, texte clair            =&gt; `theme: \'dark\'`  </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`blue`                =&gt; Accent bleu professionnel           =&gt; `theme: \'blue\'`  </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`red`                 =&gt; Accent rouge moderne                =&gt; `theme: \'red\'`   </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`green`               =&gt; Accent vert équilibré               =&gt; `theme: \'green\'` </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`custom`              =&gt; Thème utilisateur (voir ci-dessous) =&gt; `theme: \'custom\'`</li></ol>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 15:55:04','2025-11-04 15:57:43'),
(124,4,1,'\"{\\\"title\\\":\\\"Créer un thème personnalisé\\\",\\\"image\\\":\\\"@webroot/data/items/124/image_select_custom.webp\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<p>Le thème <strong>custom</strong> est utilisé afin de permettre l\'ajout d\'une CSS personnalisée.</p><p>Ce mode vous permet d’adapter le composant à la charte graphique de votre application sans modifier le code source du package.</p><p><br></p><pre><code class=\\\\\\\"language-xml\\\\\\\">&lt;select fractalcms-select-beautiful.bind=\\\\\\\"{theme: \'custom\'}\\\\\\\" multiple&gt;\\\\r\\\\n  &lt;option value=\\\\\\\"1\\\\\\\"&gt;Bleu&lt;/option&gt;\\\\r\\\\n  &lt;option value=\\\\\\\"2\\\\\\\"&gt;Vert&lt;/option&gt;\\\\r\\\\n&lt;/select&gt;</code></pre><p><br></p><p>Exemple de style à ajouter dans vos Styles CSS</p><p><br></p><pre><code class=\\\\\\\"language-css\\\\\\\">.theme-custom.select-beautiful {\\\\r\\\\n  .select-beautiful--item---item {\\\\r\\\\n    border: 2px solid #ff9900;\\\\r\\\\n    color: #ff9900;\\\\r\\\\n  }\\\\r\\\\n\\\\r\\\\n  .select-beautiful--item---item-close {\\\\r\\\\n    background-color: #fff5e6;\\\\r\\\\n    color: #ff9900;\\\\r\\\\n    border: 2px solid #ff9900;\\\\r\\\\n  }\\\\r\\\\n\\\\r\\\\n  .select-beautiful--search---input {\\\\r\\\\n    border: 2px solid #ff9900;\\\\r\\\\n  }\\\\r\\\\n\\\\r\\\\n  .select-beautiful--search---list--items {\\\\r\\\\n    border: 2px solid #ff9900;\\\\r\\\\n\\\\r\\\\n    &amp;---option[aria-selected=\\\\\\\"true\\\\\\\"] {\\\\r\\\\n      background-color: #ff9900;\\\\r\\\\n      color: white;\\\\r\\\\n    }\\\\r\\\\n  }\\\\r\\\\n}</code></pre><p><br></p>\\\",\\\"direction\\\":\\\"top\\\"}\"','2025-11-04 16:40:41','2025-11-05 11:00:48'),
(125,4,1,'\"{\\\"title\\\":\\\"Options\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>multiple</strong>`            : <strong>type</strong>:`boolean`  , <strong>valeur par défau</strong>t : `true`                                      , <strong>description</strong> : Active le multiple                   </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>theme</strong>`               : <strong>type</strong>:`Enum`, <strong>valeur par défaut</strong> : `\'soft\'`                                    , <strong>description</strong> : Selection du thème                        </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>searchPlaceholder</strong>`   : <strong>type</strong>:`string`   , valeur par défaut : `\'Rechercher\'`                              , <strong>description</strong> : Texte de recherche              </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>searchInputName</strong>`     : <strong>type</strong>:`string`   , <strong>valeur par défaut </strong>: `\'model[search]\'`                           , <strong>description</strong> : Nom de l\'input de recherche                     </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>removeText</strong>`          : <strong>type</strong>:`string`   , <strong>valeur par défaut</strong> : `\'retiré\'`                                  , <strong>description</strong> : ARIA texte quand une options est enlevée           </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>removeAllText</strong>`       : <strong>type</strong>:`string`   , <strong>valeur par défaut</strong> : `\'Toutes les sélections ont été supprimées\'`, <strong>description</strong> : ARIA texte quand toutes les options sont enlevées           </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>addText</strong>`             : <strong>type</strong>:`string`   , <strong>valeur par défaut</strong> : `\'ajouté\'`                                  , <strong>description</strong> : ARIA texte quand une option est ajoutée             </li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>`<strong>eventChangeItemName</strong>` : <strong>type</strong>:`string`   , <strong>valeur par défaut</strong> : `\'fractalcms-select-change\'`                , <strong>description</strong> : Nom de l\'évènement envoyé au changement du select</li></ol>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 16:44:37','2025-11-04 16:51:17'),
(126,4,1,'\"{\\\"title\\\":\\\"Accessibilité (A11y)\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<ol><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>aria-live pour annoncer les ajouts/suppressions</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>aria-multiselectable et aria-selected sur les options</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>aria-activedescendant pour la navigation clavier</li><li data-list=\\\\\\\"bullet\\\\\\\"><span class=\\\\\\\"ql-ui\\\\\\\" contenteditable=\\\\\\\"false\\\\\\\"></span>Support total pour lecteurs d’écran</li></ol>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 16:51:45','2025-11-04 16:52:19'),
(127,4,1,'\"{\\\"title\\\":\\\"Développement local\\\",\\\"image\\\":\\\"\\\",\\\"altimage\\\":\\\"\\\",\\\"description\\\":\\\"<pre><code class=\\\\\\\"language-bash\\\\\\\"># Installation des dépendances\\\\r\\\\nnpm install\\\\r\\\\n\\\\r\\\\n# Build complet\\\\r\\\\nnpm run build\\\\r\\\\n\\\\r\\\\n# Watch (TypeScript + SCSS)\\\\r\\\\nnpm run watch</code></pre>\\\",\\\"direction\\\":\\\"\\\"}\"','2025-11-04 16:52:43','2025-11-04 16:54:18'),
(129,6,1,'\"{\\\"title\\\":\\\"Nouveautés\\\",\\\"icon\\\":\\\"@webroot/data/items/129/new.svg\\\",\\\"ctatitle\\\":\\\"Nouveatés\\\",\\\"target\\\":\\\"/cms/tag-1\\\",\\\"url\\\":\\\"\\\"}\"','2025-11-07 11:00:04','2025-11-07 11:03:20');
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `menuItems`
--

DROP TABLE IF EXISTS `menuItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `menuItems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menuId` bigint(20) DEFAULT NULL,
  `menuItemId` bigint(20) DEFAULT NULL,
  `contentId` bigint(20) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `order` decimal(2,1) DEFAULT NULL,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menuItems_menus_fk` (`menuId`),
  KEY `menuItems_menuItems_fk` (`menuItemId`),
  KEY `menuItems_contents_fk` (`contentId`),
  KEY `menuItems_order_idx` (`order`),
  CONSTRAINT `menuItems_contents_fk` FOREIGN KEY (`contentId`) REFERENCES `contents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `menuItems_menuItems_fk` FOREIGN KEY (`menuItemId`) REFERENCES `menuItems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `menuItems_menus_fk` FOREIGN KEY (`menuId`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menuItems`
--

LOCK TABLES `menuItems` WRITE;
/*!40000 ALTER TABLE `menuItems` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `menuItems` VALUES
(1,1,NULL,1,'','Accueil',1.0,'2025-10-22 16:41:09','2025-10-22 16:41:09'),
(3,2,NULL,1,'','Accueil',1.0,'2025-10-22 17:10:08','2025-10-22 17:10:08'),
(10,1,NULL,5,'','FractalCMS',2.1,'2025-10-25 15:38:00','2025-10-25 15:38:00'),
(11,1,NULL,6,'','Blog avec FractalCMS',3.0,'2025-10-25 15:38:14','2025-10-25 15:38:14'),
(12,2,NULL,6,'','voir le site blog avec FractalCMS',5.0,'2025-10-25 16:25:30','2025-10-27 10:10:33'),
(13,2,NULL,5,'','Découvrir FractalCMS',5.0,'2025-10-25 16:25:48','2025-10-27 10:09:59'),
(14,1,NULL,20,'','Contacter moi',5.0,'2025-10-25 16:41:15','2025-10-25 16:41:15'),
(15,2,NULL,20,'','Contacter moi',6.0,'2025-10-25 16:41:34','2025-10-25 16:41:34'),
(16,2,NULL,23,'','Plan du site',5.0,'2025-10-26 14:57:33','2025-10-26 14:57:33'),
(17,1,10,7,'','Documentation complète',1.1,'2025-10-26 15:04:12','2025-10-27 10:09:39'),
(19,1,11,19,'','Installation rapide',1.0,'2025-10-26 15:05:10','2025-10-26 15:17:37'),
(20,1,10,3,'','Installation rapide',1.0,'2025-10-26 15:14:17','2025-10-27 10:05:52'),
(21,1,NULL,22,'','A propos de WebCraftDg',4.0,'2025-10-26 15:19:44','2025-11-05 13:02:15'),
(24,2,NULL,22,'','A propos de WebCraftDg',4.0,'2025-11-05 13:01:42','2025-11-05 13:01:42');
/*!40000 ALTER TABLE `menuItems` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `menus` VALUES
(1,'header',1,'2025-10-22 16:36:47','2025-11-05 13:02:46'),
(2,'footer',1,'2025-10-22 17:09:57','2025-11-05 13:02:01');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migration` VALUES
('fractalCms\\migrations\\m250822_144858_initDatabase',1761047524),
('fractalCms\\migrations\\m250830_084517_updateCmsDb',1761047524),
('fractalCms\\migrations\\m250901_145226_addSlug',1761047525),
('fractalCms\\migrations\\m250904_122046_addMenuTable',1761047526),
('fractalCms\\migrations\\m250908_140744_addTableParameters',1761047527),
('fractalCms\\migrations\\m250909_160622_addTableSeo',1761047527),
('fractalCms\\migrations\\m250928_145624_updateTableSeo',1761047528),
('fractalCms\\migrations\\m250929_112442_updateTableSeo',1761047529),
('fractalCms\\migrations\\m250930_104620_updateTableMenuItem',1761047529),
('fractalCms\\migrations\\m251001_072806_updateTableSeo',1761047529),
('fractalCms\\migrations\\m251009_154758_updateTableContent',1761047531),
('fractalCms\\migrations\\m251017_135749_upateMenuItem',1761047532),
('fractalCms\\migrations\\m251027_131244_addTablesTags',1762246442),
('m000000_000000_base',1761047518),
('m140506_102106_rbac_init',1761047520),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1761047520),
('m180523_151638_rbac_updates_indexes_without_prefix',1761047521),
('m200409_110543_rbac_update_mssql_trigger',1761047521);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `parameters`
--

DROP TABLE IF EXISTS `parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parameters` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parameters_group_name_idx` (`group`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parameters`
--

LOCK TABLES `parameters` WRITE;
/*!40000 ALTER TABLE `parameters` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `parameters` VALUES
(1,'CONTENT','MAIN','1','2025-10-21 16:44:08','2025-10-21 16:44:08'),
(2,'ITEM','HERO','1','2025-10-22 14:02:02','2025-10-22 14:02:02'),
(3,'ITEM','CARD_ARTICLE','2','2025-10-22 14:52:38','2025-10-22 14:52:38'),
(4,'MENU','HEADER','1','2025-10-22 16:43:15','2025-10-22 16:43:15'),
(5,'MENU','FOOTER','2','2025-10-22 17:14:34','2025-10-22 17:14:34'),
(6,'ITEM','ARTICLE','3','2025-10-23 11:11:50','2025-10-23 11:11:50'),
(7,'ITEM','FORM','5','2025-10-25 16:37:25','2025-10-25 16:37:25'),
(8,'ITEM','BANDEAU_NOTIFICATION','6','2025-11-05 09:22:05','2025-11-05 09:22:05');
/*!40000 ALTER TABLE `parameters` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `seos`
--

DROP TABLE IF EXISTS `seos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `changefreq` varchar(15) DEFAULT 'monthly',
  `priority` float DEFAULT 0.5,
  `noFollow` tinyint(1) DEFAULT 0,
  `ogMeta` tinyint(1) DEFAULT 1,
  `twitterMeta` tinyint(1) DEFAULT 1,
  `addJsonLd` tinyint(1) DEFAULT 1,
  `imgPath` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seos`
--

LOCK TABLES `seos` WRITE;
/*!40000 ALTER TABLE `seos` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `seos` VALUES
(1,'WebCraftDG','Développeur passionné, j’ai créé FractalCMS comme un challenge personnel et une alternative simple et modulaire aux CMS existants.','monthly',1,0,1,1,1,'@webroot/data/seo/1/accueil.webp',1,'2025-10-21 16:43:32','2025-11-07 11:27:54'),
(3,'Installation de FractalCMS, Gestion de contenus','Avant de démarrer avec FractalCMS, assurez-vous d’avoir les bons prérequis techniques. Cette partie détaille les étapes nécessaires pour installer le CMS sur votre machine ou votre serveur.','monthly',0.5,0,1,1,1,'',1,'2025-10-23 14:47:47','2025-10-27 09:53:14'),
(5,'Fractal Cms, CMS, gestion de contenus','Développeur passionné, j’ai créé FractalCMS comme un challenge personnel et une alternative simple et modulaire aux CMS existants.','monthly',0.5,0,1,1,1,'@webroot/data/seo/5/cms.webp',1,'2025-10-23 15:40:51','2025-11-05 09:08:18'),
(6,'Site blog FractalCMS, site Web, CMS, gestion de contenus','Exemple d\'utilisation de FracalCMS, site clé en main et auto configuré','monthly',0.5,0,1,1,1,'',1,'2025-10-23 15:43:24','2025-10-27 09:19:47'),
(7,'Documentation complète, FractalCMS, gestion de contenus','Documentation complète de FractalCMS.','monthly',0.5,0,1,1,1,'',1,'2025-10-24 11:09:19','2025-10-27 09:12:02'),
(8,'Configuration - Documentation complète, FractalCMS','Avant toutes création de contenu, il est nécessaire de paramétrer quelques concepts, les paramètres, la configuration des éléments, la configuration des articles ','monthly',0.5,0,1,1,1,'@webroot/data/seo/8/configurration.webp',1,'2025-10-24 11:36:53','2025-10-27 09:12:57'),
(9,'Gestion des paramètres - Documentation complète, FractalCMS, gestion de contenus','Commençons par paramétrer FractalCMS avant de pouvoir nous amuser','monthly',0.5,0,1,1,1,'@webroot/data/seo/9/gestion_parametres_art.webp',1,'2025-10-24 11:46:01','2025-10-27 09:13:49'),
(10,'Gestion des éléments - Documentation complète, FractalCMS, gestion de contenus','Tous les articles peuvent avoir des éléments. Ces éléments permettent de définir les informations qui seront utilisées pour générer le HTML finale.','monthly',0.5,0,1,1,1,'@webroot/data/seo/10/gestion_contenus_card.webp',1,'2025-10-24 13:50:54','2025-10-27 09:14:39'),
(11,'Gestion des types d\'articles - Documentation complète, FractalCMS, gestion de contenus','Le configuration du type d\'élément faite partie des concepts important de FractalCMS. C\'est grâce à cette configuration qu\'un article (Content) pourra être dirigé vers le bon Controller et la bonne  Action et permettre ainsi de construire une vue adapté à vos besoin.','monthly',0.5,0,1,1,1,'@webroot/data/seo/11/gestion_types_card.webp',0,'2025-10-24 15:51:31','2025-10-27 09:14:55'),
(12,'Contenus - Documentation complète, FractalCMS','Dans cette documentation nous allons voir le coeur de FractalCMS, la création des articles et leur utilisation sur le front','monthly',0.5,0,1,1,1,'@webroot/data/seo/12/contenus.webp',0,'2025-10-25 10:48:45','2025-11-05 11:53:05'),
(13,'Gestion des articles - Documentation complète, FractalCMS, gestion de contenus','Les articles suivent une structure définie par l\'attribut pathKey. Lors de l\'initialisation de FractalCMS le Content \"main\" a été créé.','monthly',0.5,0,1,1,1,'@webroot/data/seo/13/gestion_elements_articles_card.webp',1,'2025-10-25 10:53:44','2025-10-27 09:16:56'),
(14,'WebCraftDG : Fractal CMS : Documentation complète : configurations : gestion des utilisateurs','Ajouter, gérer l\'accès au bas office de FractalCMS, ajouter ou supprimer des droits','monthly',0.5,0,1,1,1,'',0,'2025-10-25 13:06:05','2025-10-25 14:37:49'),
(16,'Sujets avancés - Documentation complète, FractalCMS','FractalCMS offre des fonctionnalités afin de vous aider à construite une structure opérationnelle','monthly',0.5,0,1,1,1,'@webroot/data/seo/16/sujets-avances.webp',1,'2025-10-25 14:06:04','2025-10-27 09:17:59'),
(17,'Gestion des menus - Documentation complète -sujets avancés, FractalCMS, gestion de contenus','Dans FractalCMS, il est possible de créer des menus. c\'est menu pourront être ensuite récupéré sur le site et affiché sur la page.','monthly',0.5,0,1,1,1,'@webroot/data/seo/17/gestion_menus_card.webp',1,'2025-10-25 14:38:14','2025-10-27 09:17:29'),
(18,'Personnalisation - Documentation complète -sujets avancés, FractalCMS, gestion de contenus','Dans FractalCMS, nous pouvons personnaliser la vue qui sera utilisée pour générer le HTML de l\'élément dans la partie Gestion des éléments du formulaire de création d\'un article.','monthly',0.5,0,1,1,1,'@webroot/data/seo/18/personnaliser_card.webp',1,'2025-10-25 14:55:30','2025-11-04 08:27:31'),
(19,'Blog FractalCMS installation rapide - FractalCMS, site WEB, gestion de contenus','Blog avec FractalCMS, installation rapide en quelques commande.','monthly',0.5,0,1,1,1,'',1,'2025-10-25 15:45:25','2025-10-27 20:40:41'),
(20,'Me contacter via le formulaire - WebCraftDG','Me contacter via le formulaire, FractalCMS, CMS, Blog site avec FractalCMS','monthly',0.5,0,1,1,1,'@webroot/data/seo/20/contact_card.webp',1,'2025-10-25 16:39:22','2025-10-27 10:00:40'),
(22,'A propos de WebCraftDG','Pourquoi WebCraftDG, je vais essayer de répondre à des questions que je me suis posé et peut-être intéressé des visiteurs ','monthly',0.5,0,1,1,1,'@webroot/data/seo/22/apropos.webp',1,'2025-10-26 11:35:07','2025-10-27 10:27:06'),
(23,'Plan du site','Voici le plan du site WebCraftDG.fr','monthly',0.5,0,1,1,1,'@webroot/data/seo/23/plan-du-site_card.webp',0,'2025-10-26 14:53:05','2025-10-27 09:08:09'),
(24,'Gestion des étiquettes (Tags) - Documentation complète, FractalCMS, gestion de contenus','Les étiquettes permettent un rangement intuitif des contenus de fractalCMS. les articles peuvent être liés à une étiquette permettant de les classés, trouvés, répertoriés facilement','monthly',0.5,0,1,1,1,'@webroot/data/seo/24/gestion_tag.webp',1,'2025-11-04 14:02:20','2025-11-05 11:56:18'),
(25,'Etiquette nouveauté (v1.6.1)- Documentation complète, FractalCMS, Nouveautés','Ajout du système de tags pour organiser les contenus. Amélioration du composant Select Beautiful (thèmes, gestion du multi, etc.). Documentation mise à jour.','monthly',0.5,0,1,1,1,'@webroot/data/seo/25/new.webp',0,'2025-11-04 14:26:18','2025-11-05 09:21:27'),
(26,'Select Beautiful - Accessibilité, Documentation complète, FractalCMS, gestion de contenus','Cette implémentation est lié à un problème que je me suis posé, j\'ai voulu rendre l\'ajout d\'une ou plusieurs étiquettes sur un article simple et accessible','monthly',0.5,0,1,1,1,'@webroot/data/seo/26/select_card.webp',0,'2025-11-04 15:32:21','2025-11-05 11:00:48');
/*!40000 ALTER TABLE `seos` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `slugs`
--

DROP TABLE IF EXISTS `slugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `slugs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `host` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slugs`
--

LOCK TABLES `slugs` WRITE;
/*!40000 ALTER TABLE `slugs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `slugs` VALUES
(1,NULL,'accueil',1,'2025-10-21 13:54:13','2025-10-21 16:43:32'),
(3,NULL,'installation-rapide',1,'2025-10-23 14:47:47','2025-10-27 09:53:14'),
(5,NULL,'doc-fractal-cms',1,'2025-10-23 15:40:51','2025-10-23 16:21:11'),
(6,NULL,'blog-fractal-cms',1,'2025-10-23 15:43:24','2025-10-23 15:44:48'),
(7,NULL,'documentation-complete',1,'2025-10-24 11:09:19','2025-10-24 11:09:19'),
(8,NULL,'configurations',1,'2025-10-24 11:36:53','2025-10-24 11:36:53'),
(9,NULL,'gestion-des-parametres',1,'2025-10-24 11:46:01','2025-10-24 11:46:01'),
(10,NULL,'configuration-des-elements',1,'2025-10-24 13:50:54','2025-10-24 13:50:54'),
(11,NULL,'gestion-des-types-article',1,'2025-10-24 15:51:31','2025-10-24 15:53:01'),
(12,NULL,'contenus',1,'2025-10-25 10:48:45','2025-10-25 10:48:45'),
(13,NULL,'gestion-des-articles',1,'2025-10-25 10:53:44','2025-10-25 10:53:44'),
(14,NULL,'gestion-des-utilisateurs',1,'2025-10-25 13:06:05','2025-10-25 13:06:05'),
(16,NULL,'sujet-avances',1,'2025-10-25 14:06:04','2025-10-25 14:06:04'),
(17,NULL,'gestion-des-menus',1,'2025-10-25 14:38:14','2025-10-25 14:38:14'),
(18,NULL,'personnalisations',1,'2025-10-25 14:55:30','2025-10-25 14:55:30'),
(19,NULL,'blog-fractalcms-installation-rapide',1,'2025-10-25 15:45:25','2025-10-25 15:47:12'),
(20,NULL,'contacter-moi',1,'2025-10-25 16:39:22','2025-10-25 16:39:22'),
(22,NULL,'a-propos-de-webcraftdg',1,'2025-10-26 11:35:07','2025-10-26 11:39:23'),
(23,NULL,'plan-du-site',1,'2025-10-26 14:53:05','2025-10-26 14:54:24'),
(24,NULL,'gestion-des-etiquettes-tags',1,'2025-11-04 14:02:20','2025-11-04 14:04:43'),
(25,NULL,'nouveaute',1,'2025-11-04 14:26:18','2025-11-04 14:26:18'),
(26,NULL,'select-beautiful-nouveaute',1,'2025-11-04 15:32:21','2025-11-04 15:34:51');
/*!40000 ALTER TABLE `slugs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `tagItems`
--

DROP TABLE IF EXISTS `tagItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tagItems` (
  `tagId` bigint(20) NOT NULL,
  `itemId` bigint(20) NOT NULL,
  `order` int(11) DEFAULT 1,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`tagId`,`itemId`),
  KEY `tagItems_order_idx` (`order`),
  KEY `tagItems_items_fk` (`itemId`),
  CONSTRAINT `tagItems_items_fk` FOREIGN KEY (`itemId`) REFERENCES `items` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `tagItems_tags_fk` FOREIGN KEY (`tagId`) REFERENCES `tags` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tagItems`
--

LOCK TABLES `tagItems` WRITE;
/*!40000 ALTER TABLE `tagItems` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `tagItems` VALUES
(1,111,0,'2025-11-04 14:46:56','2025-11-04 14:46:56');
/*!40000 ALTER TABLE `tagItems` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slugId` bigint(20) DEFAULT NULL,
  `seoId` bigint(20) DEFAULT NULL,
  `configTypeId` bigint(20) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `tags_configTypeId_fk` (`configTypeId`),
  KEY `tags_seoId_fk` (`seoId`),
  KEY `tags_slugId_fk` (`slugId`),
  CONSTRAINT `tags_configTypeId_fk` FOREIGN KEY (`configTypeId`) REFERENCES `configTypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `tags_seoId_fk` FOREIGN KEY (`seoId`) REFERENCES `seos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `tags_slugId_fk` FOREIGN KEY (`slugId`) REFERENCES `slugs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `tags` VALUES
(1,'Nouveauté',25,25,5,1,'2025-11-04 14:26:18','2025-11-05 09:21:27');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `authKey` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 0,
  `dateCreate` datetime DEFAULT NULL,
  `dateUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `authKey` (`authKey`),
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `users_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'admin@webcraftdg.fr','$2y$13$ldhkuyQfznqTJE/gBheK2uHQZGA6d4pvJqgxtTo3uCuknsFsm9Jza','Webcraftdg','Admin','0gx-_IOv4_VKo0dFymZb_j85oh7JqIKh',NULL,1,'2025-10-21 13:54:08','2025-10-21 13:54:08');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-11-07 14:21:16
