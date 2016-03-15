
# VrijwilligersTool
Roeselare vrijwilligt

# stackoverflow
http://stackoverflow.com/questions/35727303/im-getting-the-wrong-data-when-using-doctrine-in-symfony

# DatabaseSchema:

![alt tag](http://i.imgur.com/sPyZak3.png)

# Querry(sql)
```
use homestead;
SELECT Firstname, Lastname, Proficiency, skill.Name FROM volunteer
join volunteer_has_skillproficiency as vhs on vhs.volunteer_Id = volunteer.Id
join skillproficiency on skillproficiency.Id = vhs.skillproficiency_Id
join skill on skill.Id = skillproficiency.Type
```

# Querry(php/doctrine)
```
//all users
$em = $this->getDoctrine()->getManager();
$users = $em->getRepository('AppBundle:Volunteer')->findAll();

echo "Volunteers:";
echo "<br />";
foreach($users as $user)
{
    echo $user;
}
echo "<br />";
```

# SQL
```
CREATE DATABASE  IF NOT EXISTS `homestead` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `homestead`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: homestead
-- ------------------------------------------------------
-- Server version	5.7.10

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
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Telephone` varchar(10) NOT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (1,'jelle.criel@student.howest.be','Sint-Corneliusstraat 7\r\n9280 Lebbeke','0477459599','2016-03-10 14:58:35'),(2,'koen.cornelis@howest.be','ergens in eeklo','1231231231','2016-03-10 14:58:35'),(3,'kurt.callewaert@howest.be','roeselare al tegaere','4564564564','2016-03-10 14:58:35');
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organisation`
--

DROP TABLE IF EXISTS `organisation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organisation` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `Contact` int(10) DEFAULT NULL,
  `Creator` int(10) NOT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKOrganisati891792` (`Contact`),
  KEY `FKOrganisati829755` (`Creator`),
  CONSTRAINT `FKOrganisati829755` FOREIGN KEY (`Creator`) REFERENCES `volunteer` (`Id`),
  CONSTRAINT `FKOrganisati891792` FOREIGN KEY (`Contact`) REFERENCES `contact` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organisation`
--

LOCK TABLES `organisation` WRITE;
/*!40000 ALTER TABLE `organisation` DISABLE KEYS */;
INSERT INTO `organisation` VALUES (1,'Howest Brugge','Beter dan kortrijk',1,1,'2016-03-10 14:59:38'),(2,'Vives','for pussies',2,2,'2016-03-10 14:59:38');
/*!40000 ALTER TABLE `organisation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skill`
--

DROP TABLE IF EXISTS `skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skill` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skill`
--

LOCK TABLES `skill` WRITE;
/*!40000 ALTER TABLE `skill` DISABLE KEYS */;
INSERT INTO `skill` VALUES (3,'Being badass'),(2,'Database design'),(1,'Programming'),(4,'security');
/*!40000 ALTER TABLE `skill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skillproficiency`
--

DROP TABLE IF EXISTS `skillproficiency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skillproficiency` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Type` int(10) NOT NULL,
  `Proficiency` int(5) NOT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKSkillProfi479319` (`Type`),
  CONSTRAINT `FKSkillProfi479319` FOREIGN KEY (`Type`) REFERENCES `skill` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skillproficiency`
--

LOCK TABLES `skillproficiency` WRITE;
/*!40000 ALTER TABLE `skillproficiency` DISABLE KEYS */;
INSERT INTO `skillproficiency` VALUES (1,1,10,'2016-03-10 15:01:08'),(2,2,9,'2016-03-10 15:01:08'),(3,1,8,'2016-03-10 15:01:08'),(4,2,7,'2016-03-10 15:01:08'),(5,3,6,'2016-03-10 15:01:08'),(6,4,5,'2016-03-10 15:01:08'),(7,2,4,'2016-03-10 15:01:08');
/*!40000 ALTER TABLE `skillproficiency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonial`
--

DROP TABLE IF EXISTS `testimonial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonial` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Value` varchar(2000) NOT NULL,
  `Sender` int(10) DEFAULT NULL,
  `Receiver` int(10) DEFAULT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKTestimonia20655` (`Sender`),
  KEY `FKTestimonia893114` (`Receiver`),
  CONSTRAINT `FKTestimonia20655` FOREIGN KEY (`Sender`) REFERENCES `volunteer` (`Id`),
  CONSTRAINT `FKTestimonia893114` FOREIGN KEY (`Receiver`) REFERENCES `volunteer` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonial`
--

LOCK TABLES `testimonial` WRITE;
/*!40000 ALTER TABLE `testimonial` DISABLE KEYS */;
INSERT INTO `testimonial` VALUES (1,'jelle be great student!',2,2,'2016-03-10 15:01:27');
/*!40000 ALTER TABLE `testimonial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacancy`
--

DROP TABLE IF EXISTS `vacancy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacancy` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Title` varchar(150) NOT NULL,
  `Description` varchar(2000) NOT NULL,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  `CreationTime` datetime DEFAULT NULL,
  `Organisation` int(10) NOT NULL,
  `Category` int(10) DEFAULT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKVacancy396991` (`Organisation`),
  KEY `FkVacancyCategory123_idx` (`Category`),
  CONSTRAINT `FKVacancy396991` FOREIGN KEY (`Organisation`) REFERENCES `organisation` (`Id`),
  CONSTRAINT `FkVacancyCategory123` FOREIGN KEY (`Category`) REFERENCES `vacancycategory` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacancy`
--

LOCK TABLES `vacancy` WRITE;
/*!40000 ALTER TABLE `vacancy` DISABLE KEYS */;
INSERT INTO `vacancy` VALUES (1,'stagestudent','elastic search enzo_test',NULL,NULL,NULL,1,NULL,'2016-03-10 14:56:46'),(2,'test','test-edit',NULL,NULL,NULL,1,NULL,'2016-03-10 14:56:57');
/*!40000 ALTER TABLE `vacancy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacancy_has_skillproficiency`
--

DROP TABLE IF EXISTS `vacancy_has_skillproficiency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacancy_has_skillproficiency` (
  `vacancy_Id` int(10) NOT NULL,
  `skillproficiency_Id` int(10) NOT NULL,
  PRIMARY KEY (`vacancy_Id`,`skillproficiency_Id`),
  KEY `fk_vacancy_has_skillproficiency_skillproficiency1_idx` (`skillproficiency_Id`),
  KEY `fk_vacancy_has_skillproficiency_vacancy1_idx` (`vacancy_Id`),
  CONSTRAINT `fk_vacancy_has_skillproficiency_skillproficiency1` FOREIGN KEY (`skillproficiency_Id`) REFERENCES `skillproficiency` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vacancy_has_skillproficiency_vacancy1` FOREIGN KEY (`vacancy_Id`) REFERENCES `vacancy` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacancy_has_skillproficiency`
--

LOCK TABLES `vacancy_has_skillproficiency` WRITE;
/*!40000 ALTER TABLE `vacancy_has_skillproficiency` DISABLE KEYS */;
/*!40000 ALTER TABLE `vacancy_has_skillproficiency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacancycategory`
--

DROP TABLE IF EXISTS `vacancycategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacancycategory` (
  `Id` int(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id_UNIQUE` (`Id`),
  UNIQUE KEY `Name_UNIQUE` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacancycategory`
--

LOCK TABLES `vacancycategory` WRITE;
/*!40000 ALTER TABLE `vacancycategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `vacancycategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer`
--

DROP TABLE IF EXISTS `volunteer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `username` varchar(150) NOT NULL,
  `passphrase` varchar(60) DEFAULT NULL,
  `Contact` int(10) DEFAULT NULL,
  `last_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKUser301874` (`Contact`),
  CONSTRAINT `FKUser301874` FOREIGN KEY (`Contact`) REFERENCES `contact` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer`
--

LOCK TABLES `volunteer` WRITE;
/*!40000 ALTER TABLE `volunteer` DISABLE KEYS */;
INSERT INTO `volunteer` VALUES (1,'Jelle','CrielCriel','',NULL,1,'2016-03-14 13:52:14'),(2,'Koen','Cornelis','',NULL,2,'2016-03-10 15:02:30'),(3,'Kurt','Callewaert','',NULL,3,'2016-03-10 15:02:30'),(4,'tester','testest','tester testest',NULL,NULL,'2016-03-15 11:08:28'),(5,'jelle','criel','jelle','$2y$15$f7HhxWCkzcMTC6rK3TWd..ENTBZ/CVIWq8CB1qvhKiwjfFG1drjTS',1,'2016-03-15 14:11:36'),(6,'tester','testest','tester testest',NULL,NULL,'2016-03-15 12:36:54'),(7,'tester','testest','tester testest',NULL,NULL,'2016-03-15 12:37:30'),(8,'tester','testest','tester testest',NULL,NULL,'2016-03-15 12:37:51'),(9,'tester','testest','tester testest',NULL,NULL,'2016-03-15 12:40:12'),(10,'tester','testest','tester testest',NULL,NULL,'2016-03-15 13:19:43'),(11,'tester','testest','tester testest',NULL,NULL,'2016-03-15 13:54:34');
/*!40000 ALTER TABLE `volunteer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `volunteer_has_skillproficiency`
--

DROP TABLE IF EXISTS `volunteer_has_skillproficiency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer_has_skillproficiency` (
  `volunteer_Id` int(10) NOT NULL,
  `skillproficiency_Id` int(10) NOT NULL,
  PRIMARY KEY (`volunteer_Id`,`skillproficiency_Id`),
  KEY `fk_volunteer_has_skillproficiency_skillproficiency1_idx` (`skillproficiency_Id`),
  KEY `fk_volunteer_has_skillproficiency_volunteer1_idx` (`volunteer_Id`),
  CONSTRAINT `fk_volunteer_has_skillproficiency_skillproficiency1` FOREIGN KEY (`skillproficiency_Id`) REFERENCES `skillproficiency` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_volunteer_has_skillproficiency_volunteer1` FOREIGN KEY (`volunteer_Id`) REFERENCES `volunteer` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `volunteer_has_skillproficiency`
--

LOCK TABLES `volunteer_has_skillproficiency` WRITE;
/*!40000 ALTER TABLE `volunteer_has_skillproficiency` DISABLE KEYS */;
INSERT INTO `volunteer_has_skillproficiency` VALUES (1,1),(1,2),(2,2),(1,5),(3,7);
/*!40000 ALTER TABLE `volunteer_has_skillproficiency` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-15 15:34:17
```
