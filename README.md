# VrijwilligersTool
Roeselare vrijwilligt

# DatabaseSchema:

![alt tag](http://i.imgur.com/nordGSA.jpg)
^yes relations are not very clear(pk <-> fk), a limitation of visual paradigm.

# Querry
```
SELECT Firstname, Lastname, Proficiency, Skill.Name FROM User
join UserSkill on UserSkill.Id = User.SkillId
join SkillProficiency on SkillProficiency.Id = UserSkill.ProficiencyId
join Skill on Skill.Id = SkillProficiency.Type
```

# SQL
```
-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2016 at 11:02 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `homestead` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `homestead`;

--
-- Database: `homestead`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Telephone` varchar(10) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`Id`, `Email`, `Address`, `Telephone`) VALUES
(1, 'jelle.criel@student.howest.be', 'Sint-Corneliusstraat 7\r\n9280 Lebbeke', '0477459599');

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE IF NOT EXISTS `organisation` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `ContactId` int(10) DEFAULT NULL,
  `CreatorId` int(10) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKOrganisati891792` (`ContactId`),
  KEY `FKOrganisati829755` (`CreatorId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `organisation`
--

INSERT INTO `organisation` (`Id`, `Name`, `Description`, `ContactId`, `CreatorId`) VALUES
(1, 'Howest Brugge', 'Beter dan kortrijk', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE IF NOT EXISTS `skill` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Name` (`Name`),
  UNIQUE KEY `Id` (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`Id`, `Name`) VALUES
(1, 'Programming');

-- --------------------------------------------------------

--
-- Table structure for table `skillproficiency`
--

CREATE TABLE IF NOT EXISTS `skillproficiency` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Type` int(10) NOT NULL,
  `Proficiency` tinyint(5) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKSkillProfi479319` (`Type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `skillproficiency`
--

INSERT INTO `skillproficiency` (`Id`, `Type`, `Proficiency`) VALUES
(1, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `testimonial`
--

CREATE TABLE IF NOT EXISTS `testimonial` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `Value` varchar(2000) NOT NULL,
  `SenderId` int(10) DEFAULT NULL,
  `ReceiverId` int(10) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKTestimonia20655` (`SenderId`),
  KEY `FKTestimonia893114` (`ReceiverId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `ContactId` int(10) DEFAULT NULL,
  `SkillId` int(10) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `FKUser301874` (`ContactId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Id`, `FirstName`, `LastName`, `ContactId`, `SkillId`) VALUES
(1, 'Jelle', 'Criel', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userskill`
--

CREATE TABLE IF NOT EXISTS `userskill` (
  `Id` int(10) NOT NULL,
  `ProficiencyId` int(10) NOT NULL,
  PRIMARY KEY (`Id`,`ProficiencyId`),
  KEY `FKUserSkill759301` (`ProficiencyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userskill`
--

INSERT INTO `userskill` (`Id`, `ProficiencyId`) VALUES
(0, 1),
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vacancy`
--

CREATE TABLE IF NOT EXISTS `vacancy` (
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
  KEY `FKVacancy396991` (`OrganisationId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vacancyskill`
--

CREATE TABLE IF NOT EXISTS `vacancyskill` (
  `Id` int(10) NOT NULL,
  `ProficiencyId` int(10) NOT NULL,
  PRIMARY KEY (`Id`,`ProficiencyId`),
  KEY `FKVacancySki10694` (`ProficiencyId`),
  KEY `FKVacancySki10695` (`ProficiencyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `organisation`
--
ALTER TABLE `organisation`
  ADD CONSTRAINT `FKOrganisati829755` FOREIGN KEY (`CreatorId`) REFERENCES `user` (`Id`),
  ADD CONSTRAINT `FKOrganisati891792` FOREIGN KEY (`ContactId`) REFERENCES `contact` (`Id`);

--
-- Constraints for table `skillproficiency`
--
ALTER TABLE `skillproficiency`
  ADD CONSTRAINT `FKSkillProfi479319` FOREIGN KEY (`Type`) REFERENCES `skill` (`Id`);

--
-- Constraints for table `testimonial`
--
ALTER TABLE `testimonial`
  ADD CONSTRAINT `FKTestimonia893114` FOREIGN KEY (`ReceiverId`) REFERENCES `user` (`Id`),
  ADD CONSTRAINT `FKTestimonia20655` FOREIGN KEY (`SenderId`) REFERENCES `user` (`Id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FKUser301874` FOREIGN KEY (`ContactId`) REFERENCES `contact` (`Id`);
  ADD CONSTRAINT `FKUserUserSkill312345` FOREIGN KEY (`SkillId`) REFERENCES `UserSkill` (`Id`);

--
-- Constraints for table `userskill`
--
ALTER TABLE `userskill`
  ADD CONSTRAINT `FKUserSkill759301` FOREIGN KEY (`ProficiencyId`) REFERENCES `skillproficiency` (`Id`);

--
-- Constraints for table `vacancy`
--
ALTER TABLE `vacancy`
  ADD CONSTRAINT `FKVacancy396991` FOREIGN KEY (`OrganisationId`) REFERENCES `organisation` (`Id`);
  ADD CONSTRAINT `FKVacancyVacancySkill312345` FOREIGN KEY (`SkillId`) REFERENCES `VacancySkill` (`Id`);

--
-- Constraints for table `vacancyskill`
-
ALTER TABLE `vacancyskill`
  ADD CONSTRAINT `FKVacancySki10695` FOREIGN KEY (`ProficiencyId`) REFERENCES `skillproficiency` (`Id`);
```
