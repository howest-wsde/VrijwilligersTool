# VrijwilligersTool
Roeselare vrijwilligt

# DatabaseSchema:

![alt tag](http://i.imgur.com/msxwtbr.jpg)
^yes relations are not very clear(pk <-> fk), a limitation of visual paradigm.

# SQL
```
CREATE TABLE `User` (
  Id        int(10) NOT NULL AUTO_INCREMENT, 
  FirstName varchar(100) NOT NULL, 
  LastName  varchar(100) NOT NULL, 
  ContactId int(10), 
  Skills    int(10), 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Vacancy (
  Id             int(10) NOT NULL AUTO_INCREMENT, 
  Title          varchar(150) NOT NULL, 
  Description    varchar(2000) NOT NULL, 
  StartDate      datetime NULL, 
  EndDate        datetime NULL, 
  CreationTime   datetime NULL, 
  RequiredSkills int(10), 
  OrganisationId int(10), 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Organisation (
  Id          int(10) NOT NULL AUTO_INCREMENT, 
  Name        varchar(100) NOT NULL UNIQUE, 
  Description varchar(1000) NOT NULL, 
  ContactId   int(10), 
  CreatorId   int(10), 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Contact (
  Id        int(10) NOT NULL AUTO_INCREMENT, 
  Email     varchar(255) NOT NULL, 
  Address   varchar(255) NOT NULL, 
  Telephone int(10) NOT NULL, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE SkillType (
  Id          int(10) NOT NULL AUTO_INCREMENT, 
  Type        int(10) NOT NULL, 
  Proficiency tinyint(5) NOT NULL, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE SkillProficiency (
  Id   int(10) NOT NULL AUTO_INCREMENT, 
  Type varchar(50) NOT NULL UNIQUE, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Testimonial (
  Id         int(10) NOT NULL AUTO_INCREMENT, 
  Value      varchar(2000) NOT NULL, 
  SenderId   int(10), 
  ReceiverId int(10), 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Skill (
  Id              int(10) NOT NULL AUTO_INCREMENT, 
  SkillRequiredId int(10) NOT NULL, 
  SkillId         int(10) NOT NULL, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
ALTER TABLE SkillType ADD INDEX FKSkillType227321 (Type), ADD CONSTRAINT FKSkillType227321 FOREIGN KEY (Type) REFERENCES SkillProficiency (Id);
ALTER TABLE Vacancy ADD INDEX FKVacancy396991 (OrganisationId), ADD CONSTRAINT FKVacancy396991 FOREIGN KEY (OrganisationId) REFERENCES Organisation (Id);
ALTER TABLE Organisation ADD INDEX FKOrganisati891792 (ContactId), ADD CONSTRAINT FKOrganisati891792 FOREIGN KEY (ContactId) REFERENCES Contact (Id);
ALTER TABLE `User` ADD INDEX FKUser301874 (ContactId), ADD CONSTRAINT FKUser301874 FOREIGN KEY (ContactId) REFERENCES Contact (Id);
ALTER TABLE Testimonial ADD INDEX FKTestimonia20655 (SenderId), ADD CONSTRAINT FKTestimonia20655 FOREIGN KEY (SenderId) REFERENCES `User` (Id);
ALTER TABLE Testimonial ADD INDEX FKTestimonia893114 (ReceiverId), ADD CONSTRAINT FKTestimonia893114 FOREIGN KEY (ReceiverId) REFERENCES `User` (Id);
ALTER TABLE Organisation ADD INDEX FKOrganisati829755 (CreatorId), ADD CONSTRAINT FKOrganisati829755 FOREIGN KEY (CreatorId) REFERENCES `User` (Id);
ALTER TABLE Skill ADD INDEX FKSkill850843 (SkillId), ADD CONSTRAINT FKSkill850843 FOREIGN KEY (SkillId) REFERENCES SkillType (Id);
ALTER TABLE Skill ADD INDEX FKSkill885086 (SkillRequiredId), ADD CONSTRAINT FKSkill885086 FOREIGN KEY (SkillRequiredId) REFERENCES Vacancy (Id);
ALTER TABLE Skill ADD INDEX FKSkill830893 (SkillRequiredId), ADD CONSTRAINT FKSkill830893 FOREIGN KEY (SkillRequiredId) REFERENCES `User` (Id);
```
