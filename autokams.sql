-- MariaDB dump 10.19-11.0.2-MariaDB, for osx10.17 (x86_64)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version	11.0.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mere_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_497DD63439DEC40E` (`mere_id`),
  CONSTRAINT `FK_497DD63439DEC40E` FOREIGN KEY (`mere_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorie`
--

LOCK TABLES `categorie` WRITE;
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
INSERT INTO `categorie` VALUES
(1,NULL,'sol',NULL,NULL),
(2,1,'carrelage',NULL,NULL),
(3,NULL,'hello',NULL,NULL),
(4,NULL,'Tuyau',NULL,NULL),
(5,NULL,'Moquette',NULL,NULL),
(6,3,'genre',NULL,NULL),
(7,NULL,'mur',NULL,NULL),
(8,NULL,'',NULL,NULL),
(9,NULL,'',NULL,NULL),
(10,NULL,'',NULL,NULL),
(11,NULL,'',NULL,NULL),
(12,NULL,'',NULL,NULL),
(13,NULL,'',NULL,NULL),
(14,NULL,'Ohatra',NULL,NULL),
(15,NULL,'construction',NULL,NULL),
(16,NULL,'ciment',NULL,NULL);
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `note` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES
(1,'Jean Ives','00261322230361',NULL,NULL),
(2,'John Doe','00261322230361',NULL,NULL),
(3,'Sebastien Cauet','00261322230361',NULL,NULL),
(4,'Hiaro Nathanael','00261322230361',NULL,NULL),
(5,'Mathieu Andrianjafy','00261322230361',NULL,NULL),
(6,'Paul Hubert','00261322230361',NULL,NULL),
(7,'hiaro hafa',NULL,NULL,NULL);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20230827154628','2023-09-01 13:16:08',148),
('DoctrineMigrations\\Version20230828131421','2023-09-01 13:16:08',138),
('DoctrineMigrations\\Version20230828134449','2023-09-01 13:16:08',136),
('DoctrineMigrations\\Version20230905094710','2023-09-05 09:47:26',270),
('DoctrineMigrations\\Version20230906165345','2023-09-06 16:53:57',1175),
('DoctrineMigrations\\Version20230907095533','2023-09-07 09:55:47',98),
('DoctrineMigrations\\Version20230907180841','2023-09-07 18:08:52',797),
('DoctrineMigrations\\Version20230928170231','2023-09-28 17:02:42',398),
('DoctrineMigrations\\Version20231009075527','2023-10-09 07:55:41',291),
('DoctrineMigrations\\Version20231016134408','2023-10-16 13:44:20',175),
('DoctrineMigrations\\Version20231019182813','2023-10-19 18:28:25',472),
('DoctrineMigrations\\Version20231020120110','2023-10-20 12:01:23',151);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paiement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vente_id` int(11) NOT NULL,
  `montant` double NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B1DC7A1E7DC7170A` (`vente_id`),
  CONSTRAINT `FK_B1DC7A1E7DC7170A` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paiement`
--

LOCK TABLES `paiement` WRITE;
/*!40000 ALTER TABLE `paiement` DISABLE KEYS */;
INSERT INTO `paiement` VALUES
(1,8,5000000,'2023-10-20 11:53:24'),
(2,9,100000,'2023-10-20 12:28:46'),
(3,10,2500000,'2023-10-30 11:03:12'),
(4,11,2860000,'2023-10-30 11:19:20');
/*!40000 ALTER TABLE `paiement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prix_unite`
--

DROP TABLE IF EXISTS `prix_unite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prix_unite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) NOT NULL,
  `quantification_id` int(11) NOT NULL,
  `valeur` double NOT NULL,
  `daty` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FD2BAF46F347EFB` (`produit_id`),
  KEY `IDX_FD2BAF467E8C260A` (`quantification_id`),
  CONSTRAINT `FK_FD2BAF467E8C260A` FOREIGN KEY (`quantification_id`) REFERENCES `quantification` (`id`),
  CONSTRAINT `FK_FD2BAF46F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prix_unite`
--

LOCK TABLES `prix_unite` WRITE;
/*!40000 ALTER TABLE `prix_unite` DISABLE KEYS */;
INSERT INTO `prix_unite` VALUES
(1,1,3,10000,'2023-10-03 05:56:37'),
(2,1,6,10000,'2023-10-09 05:44:22'),
(3,1,6,11000,'2023-10-09 05:53:57'),
(4,3,11,90,'2023-10-09 05:58:57'),
(5,1,12,23001,'2023-10-09 13:13:34'),
(6,1,6,75000,'2023-10-09 13:14:36'),
(7,12,8,30000,'2023-10-17 05:36:58'),
(8,4,1,100000,'2023-10-17 07:18:25'),
(9,4,12,10000,'2023-10-17 07:18:48'),
(10,2,3,10000,'2023-10-17 07:58:12'),
(11,2,3,24000,'2023-10-17 08:01:11'),
(12,1,5,15000,'2023-10-17 08:44:33'),
(13,1,12,20000,'2023-10-17 08:49:18'),
(14,1,5,16000,'2023-10-17 08:54:29'),
(15,1,1,12000,'2023-10-17 08:55:08'),
(16,1,5,10,'2023-10-17 09:34:35'),
(17,1,5,10,'2023-10-17 09:35:34'),
(18,1,5,10,'2023-10-17 09:38:21'),
(19,1,5,1,'2023-10-17 09:42:34'),
(20,1,5,10,'2023-10-17 09:42:58'),
(21,1,5,1,'2023-10-17 09:43:54'),
(22,1,5,10,'2023-10-17 09:44:52'),
(23,1,5,100,'2023-10-17 09:47:04'),
(24,1,5,100000,'2023-10-17 13:11:24'),
(25,1,5,120000,'2023-10-17 13:14:19'),
(26,1,12,600,'2023-10-17 13:14:21'),
(27,1,1,6000,'2023-10-17 13:14:21'),
(28,16,2,2000,'2023-10-19 05:31:04'),
(29,16,3,20,'2023-10-19 05:31:12'),
(30,6,1,19000,'2023-10-19 18:37:30');
/*!40000 ALTER TABLE `prix_unite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantification_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `prix` double DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_29A5EC277E8C260A` (`quantification_id`),
  CONSTRAINT `FK_29A5EC277E8C260A` FOREIGN KEY (`quantification_id`) REFERENCES `quantification` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produit`
--

LOCK TABLES `produit` WRITE;
/*!40000 ALTER TABLE `produit` DISABLE KEYS */;
INSERT INTO `produit` VALUES
(1,5,'Carrelage à motif','La quantification est à changer',120000,NULL),
(2,3,'Nouveau','C\'est un produit très rare',24000,'42e2a5bf87b91e77b0c3d4e2002a34d7.png'),
(3,8,'Paul Hubert',NULL,NULL,NULL),
(4,1,'Ciment Mafonja','Ce ciment est très mafonja 💪',100000,NULL),
(5,13,'Mafonja','Yaaa',NULL,NULL),
(6,1,'Un produit','dddd',19000,NULL),
(7,1,'Nouveau','Produit vaovao',NULL,'defaultProduit.jpg'),
(8,NULL,'Autre','Death stranding',NULL,'defaultProduit.jpg'),
(9,NULL,'test','Hello',NULL,'25f2e340f4d4d92ad368db9d4a32d60d.png'),
(10,NULL,'test2','Test du webcam',NULL,'871e420fd4d41241f8d503311181ccbb.jpg'),
(11,3,'Nouveau','C\'est un produit très rare',NULL,'defaultProduit.jpg'),
(12,8,'Hafa mintsy','Nous avons changé l\'image',30000,NULL),
(13,3,'Nouveau','C\'est un produit très rare',NULL,'defaultProduit.jpg'),
(14,3,'Nouveau','C\'est un produit très rare',NULL,'defaultProduit.jpg'),
(15,NULL,'nouveau3','',NULL,'e893e868be2c3688141fa62391c7c431.png'),
(16,2,'Anakiray','Description maivna',2000,'07670981084d7344d991f9495fcc6935.jpg');
/*!40000 ALTER TABLE `produit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produit_categorie`
--

DROP TABLE IF EXISTS `produit_categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produit_categorie` (
  `produit_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  PRIMARY KEY (`produit_id`,`categorie_id`),
  KEY `IDX_CDEA88D8F347EFB` (`produit_id`),
  KEY `IDX_CDEA88D8BCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_CDEA88D8BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CDEA88D8F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produit_categorie`
--

LOCK TABLES `produit_categorie` WRITE;
/*!40000 ALTER TABLE `produit_categorie` DISABLE KEYS */;
INSERT INTO `produit_categorie` VALUES
(2,5),
(4,7),
(5,16),
(11,5),
(11,7),
(12,1),
(13,5),
(13,7),
(14,5),
(14,7),
(16,1),
(16,5);
/*!40000 ALTER TABLE `produit_categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produit_prix`
--

DROP TABLE IF EXISTS `produit_prix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produit_prix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) NOT NULL,
  `prix` double NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DE7797B1F347EFB` (`produit_id`),
  CONSTRAINT `FK_DE7797B1F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produit_prix`
--

LOCK TABLES `produit_prix` WRITE;
/*!40000 ALTER TABLE `produit_prix` DISABLE KEYS */;
/*!40000 ALTER TABLE `produit_prix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quantification`
--

DROP TABLE IF EXISTS `quantification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quantification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `symbole` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quantification`
--

LOCK TABLES `quantification` WRITE;
/*!40000 ALTER TABLE `quantification` DISABLE KEYS */;
INSERT INTO `quantification` VALUES
(1,'Kilogramme','kg'),
(2,'Metre','m'),
(3,'Centimètre','cm'),
(4,'Litre','L'),
(5,'Pièce','pcs'),
(6,'Vaiseau','Vs'),
(7,'Mètre-carré','m2'),
(8,'Livre','Lb'),
(9,'Yoo','yb'),
(10,'Nouveau','nv'),
(11,'Omega','Ohm'),
(12,'Hectogramme','hg'),
(13,'Sac','sac');
/*!40000 ALTER TABLE `quantification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quantification_equivalence`
--

DROP TABLE IF EXISTS `quantification_equivalence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quantification_equivalence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) NOT NULL,
  `quantification_id` int(11) NOT NULL,
  `valeur` double NOT NULL,
  `prix` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A1C613BDF347EFB` (`produit_id`),
  KEY `IDX_A1C613BD7E8C260A` (`quantification_id`),
  CONSTRAINT `FK_A1C613BD7E8C260A` FOREIGN KEY (`quantification_id`) REFERENCES `quantification` (`id`),
  CONSTRAINT `FK_A1C613BDF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quantification_equivalence`
--

LOCK TABLES `quantification_equivalence` WRITE;
/*!40000 ALTER TABLE `quantification_equivalence` DISABLE KEYS */;
INSERT INTO `quantification_equivalence` VALUES
(2,1,12,200,600),
(5,1,1,20,6000),
(6,4,12,10,10000),
(7,16,3,100,20);
/*!40000 ALTER TABLE `quantification_equivalence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `plain_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES
(1,'mcloic','$2y$13$DnA58F/jme4muezM0KdhvenM2/slAKiASjrUn.umESxsDF7PaW0zq','Loïc','Rasoarahona','mcloic@gmail.com',NULL),
(2,'magali','$2y$13$u3S7dl1jcgKa6p0E6VuwzuZKtlf0A4U8.gVIMCiTMMIhGVCYFFOKW','rakotonandrasana','magali',NULL,NULL),
(3,'loicy','$2y$13$bNjYLmnN/E.ca.5LimWQF.rdCgkMIeg1PnYXZejMWcrzkFMP1MET6','loic','mcquincyan','email@example.com',NULL);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vente`
--

DROP TABLE IF EXISTS `vente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `prix` double NOT NULL,
  `note` longtext DEFAULT NULL,
  `payed` tinyint(1) NOT NULL,
  `daty` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_888A2A4C19EB6921` (`client_id`),
  CONSTRAINT `FK_888A2A4C19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vente`
--

LOCK TABLES `vente` WRITE;
/*!40000 ALTER TABLE `vente` DISABLE KEYS */;
INSERT INTO `vente` VALUES
(1,1,0,'tsy misy',0,'0000-00-00 00:00:00'),
(2,1,0,'tsy misy',0,'0000-00-00 00:00:00'),
(3,7,0,'tsy misy',0,'0000-00-00 00:00:00'),
(4,1,0,'Mbola tsisy ndray koa',0,'0000-00-00 00:00:00'),
(5,NULL,10000000,NULL,1,'0000-00-00 00:00:00'),
(6,5,119000,NULL,1,'0000-00-00 00:00:00'),
(7,5,600000,NULL,0,'0000-00-00 00:00:00'),
(8,3,6200000,NULL,0,'0000-00-00 00:00:00'),
(9,6,355000,NULL,0,'2023-10-20 12:28:46'),
(10,6,5600000,NULL,0,'2023-10-30 11:03:11'),
(11,6,2860000,NULL,1,'2023-10-30 11:19:20');
/*!40000 ALTER TABLE `vente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vente_detail`
--

DROP TABLE IF EXISTS `vente_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vente_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) NOT NULL,
  `unite_id` int(11) DEFAULT NULL,
  `quantite` double NOT NULL,
  `prix` double NOT NULL,
  `note` longtext DEFAULT NULL,
  `vente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_211297AAF347EFB` (`produit_id`),
  KEY `IDX_211297AAEC4A74AB` (`unite_id`),
  KEY `IDX_211297AA7DC7170A` (`vente_id`),
  CONSTRAINT `FK_211297AA7DC7170A` FOREIGN KEY (`vente_id`) REFERENCES `vente` (`id`),
  CONSTRAINT `FK_211297AAEC4A74AB` FOREIGN KEY (`unite_id`) REFERENCES `quantification` (`id`),
  CONSTRAINT `FK_211297AAF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vente_detail`
--

LOCK TABLES `vente_detail` WRITE;
/*!40000 ALTER TABLE `vente_detail` DISABLE KEYS */;
INSERT INTO `vente_detail` VALUES
(1,1,5,3,120000,NULL,11),
(2,4,1,25,100000,NULL,11);
/*!40000 ALTER TABLE `vente_detail` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-03  1:17:08
