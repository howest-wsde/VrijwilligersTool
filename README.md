<<<<<<< HEAD
Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony
application that you can use as the skeleton for your new applications.

For details on how to download and get started with Symfony, see the
[Installation][1] chapter of the Symfony Documentation.

What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding;

  * Twig as the only configured template engine;

  * Doctrine ORM/DBAL;

  * Swiftmailer;

  * Annotations enabled for everything.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev/test env) - Adds code generation
    capabilities

  * **DebugBundle** (in dev/test env) - Adds Debug and VarDumper component
    integration

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  https://symfony.com/doc/3.0/book/installation.html
[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/3.0/book/doctrine.html
[8]:  https://symfony.com/doc/3.0/book/templating.html
[9]:  https://symfony.com/doc/3.0/book/security.html
[10]: https://symfony.com/doc/3.0/cookbook/email.html
[11]: https://symfony.com/doc/3.0/cookbook/logging/monolog.html
[13]: https://symfony.com/doc/3.0/bundles/SensioGeneratorBundle/index.html
=======
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
>>>>>>> origin/master
