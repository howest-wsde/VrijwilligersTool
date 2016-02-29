# VrijwilligersTool
Roeselare vrijwilligt

# DatabaseSchema:

![alt tag](http://i.imgur.com/nordGSA.jpg)
^yes relations are not very clear(pk <-> fk), a limitation of visual paradigm.

# Querry
```
SELECT Firstname, Lastname, Proficiency, Skill.Name FROM user
join UserSkill on UserSkill.Id = User.SkillId
join SkillProficiency on SkillProficiency.Id = UserSkill.ProficiencyId
join Skill on Skill.Id = SkillProficiency.Type
```

# SQL
```
CREATE TABLE `User` (
  Id                     int(10) NOT NULL AUTO_INCREMENT, 
  FirstName              varchar(100) NOT NULL, 
  LastName               varchar(100) NOT NULL, 
  ContactId              int(10), 
  SkillId                int(10), 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Vacancy (
  Id                        int(10) NOT NULL AUTO_INCREMENT, 
  Title                     varchar(150) NOT NULL, 
  Description               varchar(2000) NOT NULL, 
  StartDate                 datetime NULL, 
  EndDate                   datetime NULL, 
  CreationTime              datetime NULL, 
  OrganisationId            int(10) NOT NULL, 
  SkillId                   int(10), 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Organisation (
  Id          int(10) NOT NULL AUTO_INCREMENT, 
  Name        varchar(100) NOT NULL UNIQUE, 
  Description varchar(1000) NOT NULL, 
  ContactId   int(10), 
  CreatorId   int(10) NOT NULL, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Contact (
  Id        int(10) NOT NULL AUTO_INCREMENT, 
  Email     varchar(255) NOT NULL, 
  Address   varchar(255) NOT NULL, 
  Telephone varchar(10) NOT NULL, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE SkillProficiency (
  Id          int(10) NOT NULL AUTO_INCREMENT, 
  Type        int(10) NOT NULL, 
  Proficiency tinyint(5) NOT NULL, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Skill (
  Id   int(10) NOT NULL AUTO_INCREMENT, 
  Name varchar(50) NOT NULL UNIQUE, 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE Testimonial (
  Id         int(10) NOT NULL AUTO_INCREMENT, 
  Value      varchar(2000) NOT NULL, 
  SenderId   int(10), 
  ReceiverId int(10), 
  PRIMARY KEY (Id), 
  UNIQUE INDEX (Id)) CHARACTER SET UTF8;
CREATE TABLE VacancySkill (
  Id            int(10) NOT NULL, 
  ProficiencyId int(10) NOT NULL, 
  PRIMARY KEY (Id, 
  ProficiencyId)) CHARACTER SET UTF8;
CREATE TABLE UserSkill (
  Id            int(10) NOT NULL, 
  ProficiencyId int(10) NOT NULL, 
  PRIMARY KEY (Id, 
  ProficiencyId)) CHARACTER SET UTF8;
ALTER TABLE SkillProficiency ADD INDEX FKSkillProfi479319 (Type), ADD CONSTRAINT FKSkillProfi479319 FOREIGN KEY (Type) REFERENCES Skill (Id);
ALTER TABLE Vacancy ADD INDEX FKVacancy396991 (OrganisationId), ADD CONSTRAINT FKVacancy396991 FOREIGN KEY (OrganisationId) REFERENCES Organisation (Id);
ALTER TABLE Organisation ADD INDEX FKOrganisati891792 (ContactId), ADD CONSTRAINT FKOrganisati891792 FOREIGN KEY (ContactId) REFERENCES Contact (Id);
ALTER TABLE `User` ADD INDEX FKUser301874 (ContactId), ADD CONSTRAINT FKUser301874 FOREIGN KEY (ContactId) REFERENCES Contact (Id);
ALTER TABLE Testimonial ADD INDEX FKTestimonia20655 (SenderId), ADD CONSTRAINT FKTestimonia20655 FOREIGN KEY (SenderId) REFERENCES `User` (Id);
ALTER TABLE Testimonial ADD INDEX FKTestimonia893114 (ReceiverId), ADD CONSTRAINT FKTestimonia893114 FOREIGN KEY (ReceiverId) REFERENCES `User` (Id);
ALTER TABLE Organisation ADD INDEX FKOrganisati829755 (CreatorId), ADD CONSTRAINT FKOrganisati829755 FOREIGN KEY (CreatorId) REFERENCES `User` (Id);
ALTER TABLE VacancySkill ADD INDEX FKVacancySki10694 (ProficiencyId), ADD CONSTRAINT FKVacancySki10694 FOREIGN KEY (ProficiencyId) REFERENCES SkillProficiency (Id);
ALTER TABLE VacancySkill ADD INDEX FKVacancySki10695 (ProficiencyId), ADD CONSTRAINT FKVacancySki10695 FOREIGN KEY (ProficiencyId) REFERENCES SkillProficiency (Id);
ALTER TABLE UserSkill ADD INDEX FKUserSkill759301 (ProficiencyId), ADD CONSTRAINT FKUserSkill759301 FOREIGN KEY (ProficiencyId) REFERENCES SkillProficiency (Id);
```

# trying to generate entities?
```new Acme\Bundle\BlogBundle\AcmeBlogBundle()```
in 
```\vrijwilligersproject\app\Appkernel.php```
