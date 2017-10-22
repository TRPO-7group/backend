-- MySQL dump 10.11
--
-- Host: localhost    Database: repbase
-- ------------------------------------------------------
-- Server version	5.0.67-community-nt

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
-- Table structure for table `disc`
--

DROP TABLE IF EXISTS `disc`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `disc` (
  `disc_id` int(11) NOT NULL,
  `disc_name` varchar(20) default NULL,
  PRIMARY KEY  (`disc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `disc`
--

LOCK TABLES `disc` WRITE;
/*!40000 ALTER TABLE `disc` DISABLE KEYS */;
/*!40000 ALTER TABLE `disc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rep`
--

DROP TABLE IF EXISTS `rep`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `rep` (
  `rep_id` int(11) NOT NULL,
  `is_ind` tinyint(1) default NULL,
  `pater_rep` int(11) default NULL,
  `rep_owner` int(11) default NULL,
  `rep_disc` int(11) default NULL,
  PRIMARY KEY  (`rep_id`),
  KEY `rep_owner` (`rep_owner`),
  KEY `rep_disc` (`rep_disc`),
  KEY `pater_rep` (`pater_rep`),
  CONSTRAINT `rep_ibfk_1` FOREIGN KEY (`rep_owner`) REFERENCES `user` (`user_id`),
  CONSTRAINT `rep_ibfk_2` FOREIGN KEY (`rep_disc`) REFERENCES `disc` (`disc_id`),
  CONSTRAINT `rep_ibfk_3` FOREIGN KEY (`pater_rep`) REFERENCES `rep` (`rep_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `rep`
--

LOCK TABLES `rep` WRITE;
/*!40000 ALTER TABLE `rep` DISABLE KEYS */;
/*!40000 ALTER TABLE `rep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reptegs`
--

DROP TABLE IF EXISTS `reptegs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `reptegs` (
  `id` int(11) NOT NULL,
  `repid` int(11) default NULL,
  `tegid` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `repid` (`repid`),
  KEY `tegid` (`tegid`),
  CONSTRAINT `reptegs_ibfk_1` FOREIGN KEY (`repid`) REFERENCES `rep` (`rep_id`),
  CONSTRAINT `reptegs_ibfk_2` FOREIGN KEY (`tegid`) REFERENCES `teg` (`teg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `reptegs`
--

LOCK TABLES `reptegs` WRITE;
/*!40000 ALTER TABLE `reptegs` DISABLE KEYS */;
/*!40000 ALTER TABLE `reptegs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teg`
--

DROP TABLE IF EXISTS `teg`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `teg` (
  `teg_id` int(11) NOT NULL,
  `teg_name` varchar(20) default NULL,
  PRIMARY KEY  (`teg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `teg`
--

LOCK TABLES `teg` WRITE;
/*!40000 ALTER TABLE `teg` DISABLE KEYS */;
/*!40000 ALTER TABLE `teg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_mail` varchar(20) default NULL,
  `user_type` varchar(20) default NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-21 19:32:03
