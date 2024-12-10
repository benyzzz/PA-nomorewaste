-- MySQL dump 10.13  Distrib 5.7.24, for Win64 (x86_64)
--
-- Host: localhost    Database: nmw
-- ------------------------------------------------------
-- Server version	5.7.24

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
-- Table structure for table `collectes`
--

DROP TABLE IF EXISTS `collectes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collectes` (
  `id_collecte` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_collecte` date NOT NULL,
  `id_commercant` bigint(20) unsigned NOT NULL,
  `id_utilisateur` bigint(20) unsigned DEFAULT NULL,
  `poids` decimal(10,2) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT '1',
  `code_barre` varchar(50) NOT NULL,
  `valeur_estimée` decimal(10,2) NOT NULL,
  `description` text,
  `etat` enum('vendu','en cours','à la vente') NOT NULL,
  `quantite_reservee` int(11) DEFAULT '0',
  PRIMARY KEY (`id_collecte`),
  UNIQUE KEY `code_barre` (`code_barre`),
  KEY `id_commercant` (`id_commercant`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `collectes_ibfk_1` FOREIGN KEY (`id_commercant`) REFERENCES `commercants` (`id_commercant`),
  CONSTRAINT `collectes_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collectes`
--

LOCK TABLES `collectes` WRITE;
/*!40000 ALTER TABLE `collectes` DISABLE KEYS */;
INSERT INTO `collectes` VALUES (7,'2024-08-07',7,7,300.00,10,'123456789012',100.00,NULL,'en cours',0),(8,'2024-08-07',8,2,450.00,20,'123456789013',150.00,NULL,'en cours',0),(9,'2024-08-07',9,3,200.00,5,'123456789014',80.00,NULL,'vendu',0),(10,'2024-08-07',10,7,500.00,25,'123456789015',200.00,NULL,'en cours',0),(11,'2024-08-07',11,5,100.00,3,'123456789016',50.00,NULL,'en cours',0),(12,'2024-08-07',12,6,350.00,15,'123456789017',120.00,NULL,'vendu',0),(28,'2024-08-01',7,7,5.00,2,'1234567890123',20.50,'Panier de légumes frais','en cours',0),(29,'2024-08-02',8,7,3.00,0,'2345678901234',15.00,'Panier de fruits de saison','en cours',1),(30,'2024-08-03',9,7,6.00,1,'3456789012345',25.00,'Panier mixte de fruits et légumes','à la vente',0),(31,'2024-08-04',10,26,2.50,0,'4567890123456',10.00,'Panier de produits locaux','en cours',1),(32,'2024-08-05',11,7,4.00,0,'5678901234567',18.00,'Panier de fromages et charcuteries','en cours',0),(33,'2024-09-04',12,14,3.50,0,'6789012345678',12.00,'Panier de pain et pâtisseries','en cours',0),(34,'2024-08-30',13,27,7.00,0,'7890123456789',30.00,'Panier de viande et légumes bio','vendu',1),(35,'2024-08-08',15,NULL,5.00,2,'8901234567890',22.00,'Panier de produits de la ferme','à la vente',0),(36,'2024-08-09',16,NULL,4.50,1,'9012345678901',19.50,'Panier de fruits et légumes bios','à la vente',0),(37,'2024-08-10',17,7,6.00,1,'0123456789012',28.00,'Panier de produits laitiers','à la vente',1),(38,'2024-08-11',7,7,3.00,0,'1234509876543',14.00,'Panier de produits du marché','en cours',0),(39,'2024-08-31',11,14,5.00,0,'2345609876543',21.00,'Panier de fruits exotiques','en cours',1),(40,'2024-08-13',9,7,4.00,0,'3456709876543',16.50,'Panier de légumes bios variés','en cours',0),(41,'2024-08-14',8,7,5.50,0,'4567809876543',24.00,'Panier de viande et légumes locaux','en cours',0),(42,'2024-08-15',10,7,6.00,0,'5678909876543',29.50,'Panier complet de la ferme','en cours',1),(49,'2024-08-29',23,14,6.00,9,'',12.00,'Melon','en cours',0),(52,'2024-08-29',23,14,6.00,1,'7854963254',12.00,'test','vendu',0),(54,'2024-08-23',23,NULL,2.00,9,'e202425482',2.00,'test','en cours',0),(55,'2024-08-31',24,27,0.20,5,'78360766',10.50,'Des bons Humburger , vous allez vous régaler !','vendu',0),(57,'2024-08-30',7,26,5.00,1,'1234567890',15.00,'Panier de fruits et légumes','vendu',1);
/*!40000 ALTER TABLE `collectes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commercants`
--

DROP TABLE IF EXISTS `commercants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commercants` (
  `id_commercant` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(100) NOT NULL,
  `adresse` text NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_adhesion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_site` bigint(20) unsigned NOT NULL,
  `ville` varchar(100) DEFAULT 'Undifine',
  `siret` varchar(14) NOT NULL,
  `type_magasin` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_commercant`),
  UNIQUE KEY `email` (`email`),
  KEY `id_site` (`id_site`),
  CONSTRAINT `commercants_ibfk_1` FOREIGN KEY (`id_site`) REFERENCES `sites` (`id_site`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commercants`
--

LOCK TABLES `commercants` WRITE;
/*!40000 ALTER TABLE `commercants` DISABLE KEYS */;
INSERT INTO `commercants` VALUES (7,'Boulangerie Parisienne','12 Rue du Pain','0102030405','contact@boulangerieparisienne.fr','2024-01-14 23:00:00',1,'Paris','',NULL,''),(8,'Fromagerie de Nantes','34 Rue du Fromage','0102030406','contact@fromagerienantes.fr','2024-02-19 23:00:00',4,'Nantes','',NULL,''),(9,'Primeur de Marseille','56 Rue des Fruits','0102030407','contact@primeurmarseille.fr','2024-03-17 23:00:00',3,'Marseille','',NULL,''),(10,'Poissonnerie Limoges','78 Rue des Poissons','0102030408','contact@poissonnerielimoges.fr','2024-04-21 22:00:00',2,'Limoges','',NULL,''),(11,'Boucherie Porto','90 Rue de la Viande','0102030409','contact@boucherieporto.pt','2024-05-11 22:00:00',5,'Porto','',NULL,''),(12,'Épicerie Dublin','123 Rue des Épices','0102030410','contact@epiceriedublin.ie','2024-06-29 22:00:00',6,'Dublin','',NULL,''),(13,'coucou','TEST','0783051047','bendemoi02@gmail.com','2024-08-15 22:00:00',1,'Paris','12345678974589','Boulangerie',''),(15,'coucou','TEST','0783051047','bendemoi09@gmail.com','2024-08-15 22:00:00',4,'Nantes','12345678974589','encore moi',''),(16,'test2','test2','0101010101','efrgth@gmail.com','2024-08-15 22:00:00',4,'Nantes','78945612312369','tets2',''),(17,'tets3','test3','0202020202','test@gmail.com','2024-08-15 22:00:00',4,'Nantes','12345678998625','test3',''),(18,'Carrefour','11 Avenue Messi','0125487590','clement@gmail.com','2024-08-22 17:19:34',1,'Undifine','14785236985417','melon',''),(19,'Lidl','Lidl Paris','1458215698','lidl@gmail.com','2024-08-22 17:20:52',6,'Undifine','78541256359874','Lidl',''),(20,'Auchan','Edimbourg','1425876931','sui@gmail.com','2024-08-22 17:22:38',6,'Undifine','74589623225418','Miel',''),(21,'Monop\"','Porto la','0125478999','shi@gmail.com','2024-08-22 17:24:58',5,NULL,'74589632145896','Monoprix Posto Rico',''),(22,'Leclerc','11 rue Koala','0125568894','popo@gmail.com','2024-08-23 09:29:25',1,NULL,'78889654112563','Pasta','$2y$10$K9.FHVG0WR7m5F0/prqB4OAm55NrsRzRnLLJh/wnyJKSH1DmUUqRK'),(23,'Franprix','12 rue du lait','0122547896','xdcfvgby@gmail.com','2024-08-23 09:51:12',2,NULL,'14447859652325','lait','$2y$10$YI.jy9koR5VCwXYvvkicEemg3jgOnWp26zW14XfiD3li3BPPlwPCO'),(24,'Fast Good Cuisine','5 avenue Hubert Latham','0763589610','facilecuisine@gmail.com','2024-08-29 12:36:14',4,NULL,'78885412365895','Fast Food','$2y$10$K2elyArEfupjT5UgBSzhPuC74DIsjBLQvu23FqiPovM6m3ss/n4OG');
/*!40000 ALTER TABLE `commercants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id_service` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_service` varchar(100) NOT NULL,
  `description` text,
  `date_service` date DEFAULT NULL,
  `quantite` int(11) DEFAULT '1',
  `id_utilisateur` bigint(20) unsigned DEFAULT NULL,
  `id_utilisateur_beneficiaire` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type_service` enum('normal','echange') DEFAULT 'normal',
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `echange_contre` varchar(255) DEFAULT NULL,
  `etat` varchar(20) DEFAULT 'en attente',
  `id_service_autre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_service`),
  KEY `id_utilisateur` (`id_utilisateur`),
  CONSTRAINT `services_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (21,'Collecte de nourriture','Collecte des invendus des commerçants pour redistribution','2024-08-01',10,1,0,'normal',NULL,NULL,NULL,NULL,'en attente',NULL),(22,'Transport vers entrepôt','Transport des denrées alimentaires vers l\'entrepôt principal','2024-08-02',5,2,0,'normal',NULL,NULL,NULL,NULL,'en attente',NULL),(23,'Préparation des paniers repas','Préparation des paniers repas pour distribution','2024-08-03',15,3,0,'normal',NULL,NULL,NULL,NULL,'en attente',NULL),(24,'Distribution des paniers repas','Distribution des paniers repas aux bénéficiaires','2024-08-04',20,4,0,'normal',NULL,NULL,NULL,NULL,'en attente',NULL),(25,'Vérification des stocks','Vérification et mise à jour des stocks dans l\'entrepôt','2024-08-05',8,5,0,'normal',NULL,NULL,NULL,NULL,'en attente',NULL),(26,'Suivi logistique','Suivi logistique des opérations de collecte et de distribution','2024-08-06',12,6,0,'normal',NULL,NULL,NULL,NULL,'en attente',NULL),(38,'Réparation de vélo','Réparation de vélo à domicile, service rapide et efficace.','2024-08-23',3,2,0,'normal','2024-08-23','2024-08-30','Marseille',NULL,'en attente',NULL),(41,'Cours de guitare','Donne des cours de guitare pour débutants.','2024-08-26',2,3,0,'normal','2024-08-26','2024-08-29','Lyon',NULL,'en attente',NULL),(42,'Entretien de jardin','Service d\'entretien de jardin et de pelouse.','2024-08-24',3,5,0,'normal','2024-08-24','2024-08-30','Lille',NULL,'en attente',NULL),(46,'Cours de couture','Apprenez les bases de la couture.','2024-08-25',3,7,0,'normal','2024-08-25','2024-08-30','Toulouse',NULL,'en attente',NULL),(48,'Réparation de vélo','Réparation de vélo à domicile, service rapide et efficace.','2024-08-23',3,2,0,'normal','2024-08-23','2024-08-30','Marseille',NULL,'en attente',NULL),(51,'Cours de guitare','Donne des cours de guitare pour débutants.','2024-08-26',2,3,0,'normal','2024-08-26','2024-08-29','Lyon',NULL,'en attente',NULL),(52,'Entretien de jardin','Service d\'entretien de jardin et de pelouse.','2024-08-24',3,5,0,'normal','2024-08-24','2024-08-30','Lille',NULL,'en attente',NULL),(56,'Cours de couture','Apprenez les bases de la couture.','2024-08-25',3,7,0,'normal','2024-08-25','2024-08-30','Toulouse',NULL,'en attente',NULL),(57,'Alors petit test','ceci est un test',NULL,1,7,0,'normal','2024-08-28','2024-08-30','Paris','','en attente',NULL),(58,'TEST','test',NULL,1,7,15,'echange','2024-08-29','2024-08-31','Paris','réel','accepté',45),(59,'Jeux vidéo ','Je vous avance en lvl !',NULL,100,26,0,'normal','2004-08-30','2024-08-31','Paris','','en attente',NULL),(60,'Jeux vidéo ','J\'avance ton rank sur CSGO',NULL,1,26,0,'echange','2024-08-29','2024-08-31','Paris','Des habits LV','en attente',58);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sites` (
  `id_site` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_site` varchar(100) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_site`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
INSERT INTO `sites` VALUES (1,'Paris','123 Rue de Paris, 75001 Paris, France'),(2,'Limoges','456 Rue de Limoges, 87000 Limoges, France'),(3,'Marseille','789 Avenue de Marseille, 13001 Marseille, France'),(4,'Nantes','321 Boulevard de Nantes, 44000 Nantes, France'),(5,'Porto','654 Rua de Porto, 4000-001 Porto, Portugal'),(6,'Dublin','987 Main Street, Dublin 1, Ireland');
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stockages`
--

DROP TABLE IF EXISTS `stockages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stockages` (
  `id_stockage` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_panier` varchar(100) NOT NULL,
  `kilos` decimal(10,2) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `date_stockage` date NOT NULL,
  `id_service` int(11) NOT NULL,
  `id_site` int(11) NOT NULL,
  PRIMARY KEY (`id_stockage`),
  UNIQUE KEY `id_stockage` (`id_stockage`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stockages`
--

LOCK TABLES `stockages` WRITE;
/*!40000 ALTER TABLE `stockages` DISABLE KEYS */;
INSERT INTO `stockages` VALUES (1,'Panier repas famille',10.00,30.00,'2024-08-01',1,0),(2,'Panier fruits et légumes',5.00,20.00,'2024-08-02',2,0),(3,'Panier repas atelier',8.00,25.00,'2024-08-03',3,0),(4,'Panier 1',300.00,100.00,'2024-08-07',1,1),(5,'Panier 2',450.00,150.00,'2024-08-07',2,2),(6,'Panier 3',200.00,80.00,'2024-08-07',3,3),(7,'Panier 4',500.00,200.00,'2024-08-07',4,4),(8,'Panier 5',100.00,50.00,'2024-08-07',5,5),(9,'Panier 6',350.00,120.00,'2024-08-07',6,6);
/*!40000 ALTER TABLE `stockages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateurs` (
  `id_utilisateur` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `description` text,
  `adresse` text,
  `ville` varchar(100) DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `code_postal` varchar(20) DEFAULT NULL,
  `telephone` varchar(14) NOT NULL,
  `nouveau_role` varchar(50) DEFAULT NULL,
  `message_demande` text,
  `statut_demande` enum('en attente','acceptée','rejetée') DEFAULT 'en attente',
  `date_demande` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateurs`
--

LOCK TABLES `utilisateurs` WRITE;
/*!40000 ALTER TABLE `utilisateurs` DISABLE KEYS */;
INSERT INTO `utilisateurs` VALUES (1,'Dupont','Jean','jean.dupont@example.com','password123','Admin','Gestionnaire principal','123 Rue Exemple','Paris','France','75001','',NULL,NULL,'en attente','2024-08-29 09:38:46'),(2,'Martin','Claire','claire.martin@example.com','password123','Salarié','Responsable de site','456 Rue Exemple','Nantes','France','44000','',NULL,NULL,'en attente','2024-08-29 09:38:46'),(3,'Durand','Luc','luc.durand@example.com','password123','Salarié','Gestion des stocks','789 Rue Exemple','Marseille','France','13000','',NULL,NULL,'en attente','2024-08-29 09:38:46'),(4,'Moreau','Sophie','sophie.moreau@example.com','password123','Salarié','Responsable logistique','321 Rue Exemple','Limoges','France','87000','',NULL,NULL,'en attente','2024-08-29 09:38:46'),(5,'Leroy','Paul','paul.leroy@example.com','password123','Salarié','Comptable','654 Rue Exemple','Porto','Portugal','4000-123','',NULL,NULL,'en attente','2024-08-29 09:38:46'),(6,'Rousseau','Emma','emma.rousseau@example.com','password123','Salarié','Support client','987 Rue Exemple','Dublin','Irlande','D01 F5P2','',NULL,NULL,'en attente','2024-08-29 09:38:46'),(7,'benjamin','Benjamin','benjamin.didritverdier@emotors.com','$2y$10$BSqbwRvyVTHO5h9sc.WqBeMYOq4GcVG9DKSJgPWbgECw6nJmFU2zq','Utilisateur','Salut moi je suis du Portugal !','test','Porto','Portugal','78360','0783051047',NULL,NULL,'en attente','2024-08-29 09:38:46'),(14,'test','test','test123@gmail.com','$2y$10$R0VoEJeg7n0P2Isa.5B.aeKILfQ1tXPMj/BLHLjZCG4w3E/Ndm8gq','Bénévole','test','MOI','moi ','moi','78360','0101010101',NULL,NULL,'en attente','2024-08-29 09:38:46'),(15,'Issam','Issam','issam@test.com','$2y$10$6fqmsycYh3yu6ENWmsb9ZOzC9LJHubZcYcoFtF3nWeeP0b2azsi8y','Utilisateur','coucou je suis nouveau hate de commencer l\'aventure No more Waste ! ','11 Avenue Paris','Paris','France','75001','0665265870',NULL,NULL,'en attente','2024-08-29 09:38:46'),(16,'Yaya','Ya','yaya@gmail.com','$2y$10$KlA0AcdUbv0HyGMlLprP8eQt0eFf081wfSTNgf3XKE5DzsTiaBmLi','Utilisateur','ya','11 Avenue Ronaldo','Paris','France','75400','0125478958',NULL,NULL,'en attente','2024-08-29 09:38:46'),(18,'messi','Angel','messi@gmail.com','$2y$10$SKbpXZN6ZICUMuOoqcZgx.FKmjKHsWx19ciVkgcfxPaKxmzN5Zm2y','Utilisateur','GOAT','41 Argentina','Dublin','Ireland','96000','011111111',NULL,NULL,'en attente','2024-08-29 09:38:46'),(19,'pourquoi','po','pourquoi@gmail.com','$2y$10$g6rKGPXzUG1g2uRon80m8OQTZ0sIFimRQPAY1PphtQQKCWKmWwq2K','Utilisateur','ojkzefiza','jzifjaijnei','iejfoznefi','aeifnoieznf','78546','7896541236',NULL,NULL,'en attente','2024-08-29 09:38:46'),(20,'encore','encore','encore@gmail.com','$2y$10$1nWr6Aa95b/pdG0c6GKAxe9SG2Vaxh0kIUza0jFATKiB.Ipg3iBIe','Utilisateur','ezfa','ijzjofa','h','huihiuohn','74521','1478596325',NULL,NULL,'en attente','2024-08-29 09:38:46'),(21,'test8','test8','test8@gmail.om','$2y$10$OMSYdO1EoR.W9xWGDfnVi.uPUgQob808IASS9vdxHw/SSPD6k.lEi','Utilisateur','test8','test8test','test','Francia','78541','4178569547',NULL,NULL,'en attente','2024-08-29 09:38:46'),(22,'ctfvgybhunj','test','fcvgbhjnk@gmail.com','$2y$10$rwDGGC48KDscArMt97BR2.RpRH4hbs.BevykhmG7XUei/J2XQdv0y','Utilisateur','test','test','etst','test','78541','0125489652',NULL,NULL,'en attente','2024-08-29 09:38:46'),(23,'Dupont','Alice','alice@example.com','123456','Utilisateur',NULL,'123 Rue des Fleurs','Paris','France','75001','0102030405','Bénévole','Je souhaite devenir bénévole pour aider la communauté.','en attente','2024-08-29 09:50:04'),(24,'Martin','Bob','bob@example.com','123456','Bénévole',NULL,'456 Avenue des Champs','Lyon','France','69001','0607080910','Salarié','Je travaille comme bénévole depuis 2 ans, je souhaite passer au poste de salarié.','en attente','2024-08-29 09:50:04'),(25,'Durand','Charlie','charlie@example.com','$2y$10$kePlSkH833GfYImPyoI1.e3Li0WuPgBXZH6wlyit/7UA1oJ8Hadmu','Admin',NULL,'789 Boulevard des Arts','Marseille','France','13001','0708091011',NULL,NULL,'acceptée','2024-08-29 09:50:04'),(26,'Didrit Verdier','Benjamin','bendemoi02@gmail.com','$2y$10$PmMMbOLlEMIfEghXZzJteO9s/1Nv0WuyAcArCUxJXNX1VrsxP0BFm','Utilisateur','J\'essaye','11 Avenue Paul Doumer, 92500 Rueil-Malmaison, France','Rueil-Malmaison','France','92500','0660105216','Bénévole',NULL,'en attente','2024-08-29 12:33:51'),(27,'NYZZ','NYZZ','bendemoi09@gmail.com','$2y$10$rNz1/mkGEF8S0inggOQKOeh0XCQbiVmux4f3/K41fnKbWeoeNVdd.','Bénévole','j\'adore aider','48 Rue du Patron','Lisbonne','Portugal','60000','0783051047','Salarié',NULL,'en attente','2024-08-29 12:45:41');
/*!40000 ALTER TABLE `utilisateurs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-10 10:40:54
