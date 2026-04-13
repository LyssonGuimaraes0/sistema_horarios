-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bd_banco_de_horas
-- ------------------------------------------------------
-- Server version	8.4.8

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
-- Table structure for table `cargo`
--

DROP TABLE IF EXISTS `cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cargo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cargo` varchar(16) DEFAULT NULL,
  `cargo_entrada` time DEFAULT NULL,
  `cargo_saida_almoco` time DEFAULT NULL,
  `cargo_volta_almoco` time DEFAULT NULL,
  `cargo_saida` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargo`
--

LOCK TABLES `cargo` WRITE;
/*!40000 ALTER TABLE `cargo` DISABLE KEYS */;
INSERT INTO `cargo` VALUES (1,'Servidor Publico','08:30:00','12:00:00','13:30:00','18:00:00'),(2,'PPE','08:00:00','12:00:00','13:00:00','17:00:00'),(3,'Estagiario-Manha','08:30:00',NULL,NULL,'12:00:00'),(4,'Estagiario-Tarde','13:00:00',NULL,NULL,'17:30:00');
/*!40000 ALTER TABLE `cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento_justificativa`
--

DROP TABLE IF EXISTS `documento_justificativa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento_justificativa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `caminho_justificativa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descricao_motivo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `data_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `documento_justificativa_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento_justificativa`
--

LOCK TABLES `documento_justificativa` WRITE;
/*!40000 ALTER TABLE `documento_justificativa` DISABLE KEYS */;
INSERT INTO `documento_justificativa` VALUES (2,1,'../docs/id_1_alysson_andrade_guimaraes/justificativas/id_1_Data_2026-01-02.pdf','consulta_medica','2026-01-02','2026-01-05','2026-01-07 19:13:12'),(3,1,'../docs/id_1_alysson_andrade_guimaraes/justificativas/id_1_Data_2026-01-08.pdf','consulta_medica','2026-01-08','2026-01-12','2026-01-10 00:53:13'),(4,1,'../docs/id_1_alysson_andrade_guimaraes/justificativas/id_1_Data_2026-03-10.pdf','consulta_medica','2026-03-10','2026-03-14','2026-03-31 14:15:52');
/*!40000 ALTER TABLE `documento_justificativa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feriados`
--

DROP TABLE IF EXISTS `feriados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feriados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `feriado` varchar(200) DEFAULT NULL,
  `dia_mes` char(5) DEFAULT NULL,
  `ano` char(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feriados`
--

LOCK TABLES `feriados` WRITE;
/*!40000 ALTER TABLE `feriados` DISABLE KEYS */;
INSERT INTO `feriados` VALUES (1,'Confraternização Universal','01/01','2026'),(2,'Carnaval','16/02','2026'),(3,'Carnaval','17/02','2026'),(4,'Quarta-feira de Cinzas','18/02','2026'),(5,'Sexta-feira Santa','03/04','2026'),(6,'Tiradentes','21/04','2026'),(7,'Dia do Trabalhador','01/05','2026'),(8,'Corpus Christi','04/06','2026'),(9,'Independência do Brasil','07/09','2026'),(10,'Nossa Senhora Aparecida','12/10','2026'),(11,'Dia do Servidor Público','28/10','2026'),(12,'Finados','02/11','2026'),(13,'Proclamação da República','15/11','2026'),(14,'Dia Nacional de Zumbi e da Consciência Negra','20/11','2026'),(15,'Véspera de Natal','24/12','2026'),(16,'Natal','25/12','2026'),(17,'Véspera de Ano-Novo','31/12','2026'),(41,'Teste','08/04','2026'),(49,'Ponto Facultativo - Teste','07/04','2026');
/*!40000 ALTER TABLE `feriados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folha_ponto_mensal`
--

DROP TABLE IF EXISTS `folha_ponto_mensal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `folha_ponto_mensal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `mes` tinyint NOT NULL,
  `ano` smallint NOT NULL,
  `caminho_folha_de_ponto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `observacao_fechamento` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_folha_mes` (`usuario_id`,`mes`,`ano`),
  CONSTRAINT `folha_ponto_mensal_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folha_ponto_mensal`
--

LOCK TABLES `folha_ponto_mensal` WRITE;
/*!40000 ALTER TABLE `folha_ponto_mensal` DISABLE KEYS */;
INSERT INTO `folha_ponto_mensal` VALUES (5,1,1,2026,'./docs\\id_1_alysson_andrade_guimaraes\\ponto_mensal\\folha_ponto_id_1_alysson_andrade_guimaraes_01_2026.pdf','2026-03-30 18:23:53',NULL),(6,1,3,2026,'./docs\\id_1_alysson_andrade_guimaraes\\ponto_mensal\\folha_ponto_id_1_alysson_andrade_guimaraes_03_2026.pdf','2026-03-30 19:52:01',NULL),(7,1,2,2026,'./docs\\id_1_alysson_andrade_guimaraes\\ponto_mensal\\folha_ponto_id_1_alysson_andrade_guimaraes_02_2026.pdf','2026-03-31 14:31:01',NULL);
/*!40000 ALTER TABLE `folha_ponto_mensal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ponto_diario`
--

DROP TABLE IF EXISTS `ponto_diario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ponto_diario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `data_completo` date DEFAULT NULL,
  `entrada` time DEFAULT NULL,
  `saida_almoco` time DEFAULT NULL,
  `volta_almoco` time DEFAULT NULL,
  `saida` time DEFAULT NULL,
  `status_dia` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Em Andamento',
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_ponto_dia` (`usuario_id`,`data_completo`),
  CONSTRAINT `ponto_diario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ponto_diario`
--

LOCK TABLES `ponto_diario` WRITE;
/*!40000 ALTER TABLE `ponto_diario` DISABLE KEYS */;
INSERT INTO `ponto_diario` VALUES (1,1,'2026-01-01','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-01-07 18:49:13'),(4,1,'2026-01-05','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-01-07 19:13:12'),(8,1,'2026-01-07','08:00:00','13:00:00','14:50:00','17:00:00','Completo','2026-01-08 01:59:44'),(13,1,'2026-01-29','08:10:00','13:11:00','14:50:00','18:00:00','Completo','2026-01-09 23:38:06'),(15,1,'2026-01-08','08:00:00','12:00:00','13:00:00','17:00:00','Atestado','2026-01-10 00:53:13'),(16,1,'2026-01-09','08:00:00','12:00:00','13:00:00','17:00:00','Atestado','2026-01-10 00:53:13'),(17,1,'2026-01-12','08:00:00','12:00:00','13:00:00','17:00:00','Atestado','2026-01-10 00:53:13'),(18,1,'2026-03-20','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-20 14:15:13'),(26,1,'2026-01-13','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-20 17:09:47'),(29,1,'2026-03-02','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-23 18:43:42'),(30,1,'2026-03-03','09:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-24 19:26:05'),(31,1,'2026-03-04','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-24 19:26:54'),(33,3,'2026-01-02','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 13:23:06'),(34,3,'2026-01-05','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 13:26:14'),(35,1,'2026-01-06','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:20:13'),(37,1,'2026-01-14','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:27:17'),(38,1,'2026-01-19','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:28:35'),(39,1,'2026-01-15','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:35:37'),(40,1,'2026-01-16','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:36:25'),(41,1,'2026-01-20','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:43:18'),(42,1,'2026-01-22','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:43:39'),(43,1,'2026-01-21','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:45:24'),(44,1,'2026-01-26','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 14:46:53'),(45,1,'2026-01-23','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 15:04:59'),(46,1,'2026-02-09','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 15:31:29'),(47,1,'2026-02-02','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 15:31:37'),(48,1,'2026-01-27','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 15:32:01'),(49,1,'2026-01-28','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 15:33:09'),(50,1,'2026-01-30','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 16:04:16'),(51,1,'2026-01-02','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-27 16:04:20'),(52,3,'2026-03-09','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 16:56:05'),(53,3,'2026-03-02','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 16:56:15'),(54,3,'2026-03-13','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 16:56:21'),(55,3,'2026-03-03','12:00:00',NULL,NULL,'16:00:00','Completo','2026-03-27 16:56:30'),(56,3,'2026-01-07','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:10'),(57,3,'2026-01-06','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:12'),(58,3,'2026-01-08','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:14'),(59,3,'2026-01-09','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:17'),(60,3,'2026-01-12','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:19'),(61,3,'2026-01-13','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:22'),(62,3,'2026-01-30','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:27'),(63,3,'2026-01-14','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:30'),(64,3,'2026-01-15','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:34'),(65,3,'2026-01-16','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:28:39'),(66,3,'2026-01-19','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:29:29'),(67,3,'2026-01-20','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:29:34'),(68,3,'2026-01-21','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:29:42'),(69,3,'2026-01-22','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:29:48'),(70,3,'2026-01-28','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:29:52'),(71,3,'2026-01-29','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:29:56'),(72,3,'2026-01-23','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:30:01'),(73,3,'2026-01-26','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:30:06'),(74,3,'2026-01-27','13:00:00',NULL,NULL,'17:30:00','Completo','2026-03-27 17:30:09'),(76,1,'2026-03-05','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-30 20:04:26'),(77,1,'2026-03-23','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-30 20:04:40'),(78,1,'2026-03-30','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-30 20:07:01'),(79,1,'2026-03-06','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-31 14:14:43'),(80,1,'2026-03-09','08:00:00','12:00:00','13:00:00','17:00:00','Completo','2026-03-31 14:14:52'),(81,1,'2026-03-10','08:00:00','12:00:00','13:00:00','08:00:00','Atestado','2026-03-31 14:15:52'),(82,1,'2026-03-11','08:00:00','12:00:00','13:00:00','08:00:00','Atestado','2026-03-31 14:15:52'),(83,1,'2026-03-12','08:00:00','12:00:00','13:00:00','08:00:00','Atestado','2026-03-31 14:15:52'),(84,1,'2026-03-13','08:00:00','12:00:00','13:00:00','08:00:00','Atestado','2026-03-31 14:15:52');
/*!40000 ALTER TABLE `ponto_diario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ponto_facultativo`
--

DROP TABLE IF EXISTS `ponto_facultativo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ponto_facultativo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_feriado` int NOT NULL,
  `id_cargo` int NOT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `horario_compensacao_entrada_manha` time DEFAULT NULL,
  `horario_compensacao_saida_manha` time DEFAULT NULL,
  `horario_compensacao_entrada_tarde` time DEFAULT NULL,
  `horario_compensacao_saida_tarde` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_feriado` (`id_feriado`),
  KEY `id_cargo` (`id_cargo`),
  CONSTRAINT `ponto_facultativo_ibfk_1` FOREIGN KEY (`id_feriado`) REFERENCES `feriados` (`id`),
  CONSTRAINT `ponto_facultativo_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ponto_facultativo`
--

LOCK TABLES `ponto_facultativo` WRITE;
/*!40000 ALTER TABLE `ponto_facultativo` DISABLE KEYS */;
INSERT INTO `ponto_facultativo` VALUES (55,49,2,'2026-04-13','2026-04-17','08:00:00','12:00:00','13:00:00','18:00:00'),(56,49,1,'2026-04-13','2026-04-17','08:00:00','12:00:00','13:00:00','18:00:00'),(57,49,3,'2026-04-13','2026-04-17','08:00:00','14:00:00','12:00:00','18:00:00'),(58,49,4,'2026-04-13','2026-04-17','08:00:00','13:00:00','12:00:00','18:00:00');
/*!40000 ALTER TABLE `ponto_facultativo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cpf` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `setor` char(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `permissoes` enum('administrador','usuario') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cargo` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  KEY `fk_usuario_cargo` (`cargo`),
  CONSTRAINT `fk_usuario_cargo` FOREIGN KEY (`cargo`) REFERENCES `cargo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Alysson Andrade Guimaraes','087.615.015.65','COINF','administrador','alyssonandrade','alysson@gmail.com','123',2),(2,'Israel Sales','692.803.760-12','COINF','administrador','israelsales','israel@gmail.com','123',1),(3,'Bruno Lins','498.812.430-41','COINF','usuario','brunolins','brunnolis@gmail.com','123',4);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-13  0:56:59
