# VrijwilligersTool
Roeselare vrijwilligt

# DatabaseSchema:

![alt tag](http://i.imgur.com/37Op7w1.png)
^yes relations are not very clear(pk <-> fk), a limitation of visual paradigm.

# Querry(sql)
```
use homestead;
SELECT Firstname, Lastname, Proficiency, skill.Name FROM user
join userskill on userskill.Id = user.SkillId
join skillproficiency on skillproficiency.Id = userskill.ProficiencyId
join skill on skill.Id = skillproficiency.Type
where Firstname = "Jelle"
```

# Querry(php/doctrine)
```
$user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneByFirstname("Jelle");

        $userSkill = $user->getSkill();

        $skillProficiency = $userSkill->getProficiency();

        $skill = $skillProficiency->getType();

        $html = "<html><body>" . $user->getFirstname()." ".$user->getLastname().", ".$skillProficiency->getProficiency().", ".$skill->getName()."</body></html>";
        return new Response($html);
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
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (1,'jelle.criel@student.howest.be','Sint-Corneliusstraat 7\r\n9280 Lebbeke','0477459599');
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
  `ContactId` int(10) DEFAULT NULL,
  `CreatorId` int(10) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKOrganisati891792` (`ContactId`),
  KEY `FKOrganisati829755` (`CreatorId`),
  CONSTRAINT `FKOrganisati829755` FOREIGN KEY (`CreatorId`) REFERENCES `user` (`Id`),
  CONSTRAINT `FKOrganisati891792` FOREIGN KEY (`ContactId`) REFERENCES `contact` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organisation`
--

LOCK TABLES `organisation` WRITE;
/*!40000 ALTER TABLE `organisation` DISABLE KEYS */;
INSERT INTO `organisation` VALUES (1,'Howest Brugge','Beter dan kortrijk',1,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skill`
--

LOCK TABLES `skill` WRITE;
/*!40000 ALTER TABLE `skill` DISABLE KEYS */;
INSERT INTO `skill` VALUES (1,'Programming');
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
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKSkillProfi479319` (`Type`),
  CONSTRAINT `FKSkillProfi479319` FOREIGN KEY (`Type`) REFERENCES `skill` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skillproficiency`
--

LOCK TABLES `skillproficiency` WRITE;
/*!40000 ALTER TABLE `skillproficiency` DISABLE KEYS */;
INSERT INTO `skillproficiency` VALUES (1,1,10);
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
  `SenderId` int(10) DEFAULT NULL,
  `ReceiverId` int(10) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKTestimonia20655` (`SenderId`),
  KEY `FKTestimonia893114` (`ReceiverId`),
  CONSTRAINT `FKTestimonia20655` FOREIGN KEY (`SenderId`) REFERENCES `user` (`Id`),
  CONSTRAINT `FKTestimonia893114` FOREIGN KEY (`ReceiverId`) REFERENCES `user` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonial`
--

LOCK TABLES `testimonial` WRITE;
/*!40000 ALTER TABLE `testimonial` DISABLE KEYS */;
/*!40000 ALTER TABLE `testimonial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `ContactId` int(10) DEFAULT NULL,
  `SkillId` int(10) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKUser301874` (`ContactId`),
  KEY `FKUserUserSKill13_idx` (`SkillId`),
  CONSTRAINT `FKUser301874` FOREIGN KEY (`ContactId`) REFERENCES `contact` (`Id`),
  CONSTRAINT `FKUserUserSKill13` FOREIGN KEY (`SkillId`) REFERENCES `userskill` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Jelle','Criel',1,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userskill`
--

DROP TABLE IF EXISTS `userskill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userskill` (
  `Id` int(10) NOT NULL,
  `ProficiencyId` int(10) NOT NULL,
  PRIMARY KEY (`Id`,`ProficiencyId`),
  KEY `FKUserSkill759301` (`ProficiencyId`),
  CONSTRAINT `FKUserSkill759301` FOREIGN KEY (`ProficiencyId`) REFERENCES `skillproficiency` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userskill`
--

LOCK TABLES `userskill` WRITE;
/*!40000 ALTER TABLE `userskill` DISABLE KEYS */;
INSERT INTO `userskill` VALUES (0,1),(1,1);
/*!40000 ALTER TABLE `userskill` ENABLE KEYS */;
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
  `OrganisationId` int(10) NOT NULL,
  `SkillId` int(10) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKVacancy396991` (`OrganisationId`),
  KEY `FKVcancyVacancySkill_idx` (`SkillId`),
  CONSTRAINT `FKVacancy396991` FOREIGN KEY (`OrganisationId`) REFERENCES `organisation` (`Id`),
  CONSTRAINT `FKVcancyVacancySkill` FOREIGN KEY (`SkillId`) REFERENCES `vacancyskill` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacancy`
--

LOCK TABLES `vacancy` WRITE;
/*!40000 ALTER TABLE `vacancy` DISABLE KEYS */;
/*!40000 ALTER TABLE `vacancy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacancyskill`
--

DROP TABLE IF EXISTS `vacancyskill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vacancyskill` (
  `Id` int(10) NOT NULL,
  `ProficiencyId` int(10) NOT NULL,
  PRIMARY KEY (`Id`,`ProficiencyId`),
  KEY `FKVacancySki10694` (`ProficiencyId`),
  KEY `FKVacancySki10695` (`ProficiencyId`),
  CONSTRAINT `FKProficiencyIdSkillProficiency123` FOREIGN KEY (`ProficiencyId`) REFERENCES `skillproficiency` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacancyskill`
--

LOCK TABLES `vacancyskill` WRITE;
/*!40000 ALTER TABLE `vacancyskill` DISABLE KEYS */;
/*!40000 ALTER TABLE `vacancyskill` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-01 12:10:26
```
