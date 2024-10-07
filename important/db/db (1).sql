CREATE DATABASE  IF NOT EXISTS `climate_survey` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `climate_survey`;
-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: 360_survey_schema
-- ------------------------------------------------------
-- Server version	8.0.35-0ubuntu0.22.04.1

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
-- Table structure for table `Branch`
--

DROP TABLE IF EXISTS `Branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Branch` (
  `BranchID` int NOT NULL AUTO_INCREMENT,
  `BranchName` varchar(90) NOT NULL,
  PRIMARY KEY (`BranchID`,`BranchName`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Branch`
--

LOCK TABLES `Branch` WRITE;
/*!40000 ALTER TABLE `Branch` DISABLE KEYS */;
INSERT INTO `Branch` VALUES (100,'Colombo'),(101,'Kandy'),(102,'Kurunagala');
/*!40000 ALTER TABLE `Branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Department`
--

DROP TABLE IF EXISTS `Department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Department` (
  `DepartmentID` int NOT NULL AUTO_INCREMENT,
  `DepartmentName` varchar(120) NOT NULL,
  PRIMARY KEY (`DepartmentID`,`DepartmentName`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Department`
--

LOCK TABLES `Department` WRITE;
/*!40000 ALTER TABLE `Department` DISABLE KEYS */;
INSERT INTO `Department` VALUES (100,'3W Centre'),(101,'3W Centre (Finance)'),(102,'3W Centre (IT)'),(103,'3W Centre (Procurement)'),(104,'3W Centre (HR)'),(105,'3WC'),(106,'3WGE');
/*!40000 ALTER TABLE `Department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DirectReports`
--

DROP TABLE IF EXISTS `DirectReports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `DirectReports` (
  `EID` varchar(45) NOT NULL,
  `DirectReportEID` varchar(45) NOT NULL,
  PRIMARY KEY (`EID`,`DirectReportEID`),
  KEY `fk_DirectReports_2_idx` (`DirectReportEID`),
  CONSTRAINT `fk_DirectReports_1` FOREIGN KEY (`EID`) REFERENCES `Employee` (`EID`),
  CONSTRAINT `fk_DirectReports_2` FOREIGN KEY (`DirectReportEID`) REFERENCES `Employee` (`EID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DirectReports`
--

LOCK TABLES `DirectReports` WRITE;
/*!40000 ALTER TABLE `DirectReports` DISABLE KEYS */;
INSERT INTO `DirectReports` VALUES ('69','1'),('94','35'),('69','58'),('1','62'),('58','64'),('1','65'),('1','68'),('3W 001','69'),('94','69'),('FTC 02','69'),('69','70'),('1','74'),('1','76'),('3W 001','94'),('FTC 01','95'),('94','96'),('3W 001','FTC 01'),('94','FTC 01'),('FTC 02','FTC 01'),('3W 001','FTC 02'),('FTC 01','MT 01'),('FTC 01','MT 02'),('FTC 01','MT 03'),('94','MT 04'),('69','MT 05'),('94','MT 06'),('94','MT 07'),('3W 001','OS 01'),('94','OS 01'),('3W 001','OS 02'),('94','OS 02'),('OS 02','OS 03'),('OS 01','OS 04');
/*!40000 ALTER TABLE `DirectReports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Employee`
--

DROP TABLE IF EXISTS `Employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Employee` (
  `EID` varchar(45) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `BranchID` int NOT NULL,
  `DepartmentID` int NOT NULL,
  `JobTitleID` int NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `Email` varchar(255) NOT NULL,
  PRIMARY KEY (`EID`),
  KEY `fk_BranchID_idx` (`BranchID`),
  KEY `fk_JobTitle_idx` (`JobTitleID`),
  KEY `fk_DepartmentID_idx` (`DepartmentID`),
  CONSTRAINT `fk_BranchID` FOREIGN KEY (`BranchID`) REFERENCES `Branch` (`BranchID`),
  CONSTRAINT `fk_DepartmentID` FOREIGN KEY (`DepartmentID`) REFERENCES `Department` (`DepartmentID`),
  CONSTRAINT `fk_JobTitle` FOREIGN KEY (`JobTitleID`) REFERENCES `JobTitle` (`JobTitleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Employee`
--

LOCK TABLES `Employee` WRITE;
/*!40000 ALTER TABLE `Employee` DISABLE KEYS */;
INSERT INTO `Employee` VALUES ('1','Iresha Dasanayaka',100,106,213,'077 208 0122','iresha@3wge.com'),('35','Ranil Bandara',100,103,207,'076 654 3505','ranil@3rdwave.lk'),('3W 001','Peter Eusabius Stefano Moraes',100,100,200,'077 730 8128','stefan@3rdwaveconsulting.com'),('58','Mihiri Dasanayake',101,106,219,'077 755 1405','mihiri@3wge.com'),('62','Dino Fernandez',100,106,214,'077 167 7788','dino@3wge.com'),('64','Gayathri Samarakoon',101,106,214,'076 426 0327','darshani@3wge.com'),('65','Ruwini ​Jayasundera',100,106,215,'076 415 2244','ruwini@3wge.com'),('68','Samantha De Alwis',100,106,215,'077 411 1397','samantha@3wge.com'),('69','Bernice Roche',100,106,212,'077 750 8577','bernice@3wge.com'),('70','Ronalie Jayaweera',100,106,216,'076 534 7779','ronalie@3wge.com'),('74','Shiromy Patrick',100,106,217,'077 767 8706','shirmoy@3wge.com'),('75','Azam Mohamed',102,106,220,'077 527 5577','mohamed@3wge.com'),('76','Dilhani Perera',100,106,215,'077 118 0321','Dilhani@3wge.com'),('94','Arshini Skandarajah',100,100,202,'076 646 6690','arshini@3rdwaveconsulting.com'),('95','Hiran Wilathgamuwage',100,105,210,'077 760 1706','hiran@3rdwaveconsulting.com'),('96','Thivya Ravindran',100,104,208,'076 964 3723','thivya@3rdwave.lk'),('FTC 01','Anna Malsirini Kumari Bandara',100,105,211,'077 781 3227','malsirini@3rdwaveconsulting.com'),('FTC 02','Roshan Gurusinghe',100,100,201,'077 722 4545','roshan@3rdwave.lk'),('MT 01','Darshani Krishnan',100,105,209,'075 651 6277','darshani@3rdwaveconsulting.com'),('MT 02','Sanuja Kobbekaduwe',100,105,209,'076 847 5123','sanuja@3rdwaveconsulting.com'),('MT 03','Pahani Gamage',100,105,209,'076 689 5460','pahani@3rdwaveconsulting.com'),('MT 04','Imasha Nelundeniya',100,104,209,'070 263 6391','imasha@3rdwave.lk'),('MT 05','Hasun Dharshana',100,106,218,'077 5335807','hasun@3wge.com'),('MT 06','Gayathri Harshika',100,104,209,'076 886 8328','gayathri@3rdwave.lk'),('MT 07','Rakhitha Mutucumarana',100,104,209,'077 485 3284','rakhitha@3rdwave.lk'),('OS 01','Sudesh Siriwardena',100,101,203,'076 919 5363','sudesh@3rdwave.lk'),('OS 02','Irshad Iqbal',100,102,204,'071 688 3262','irshad@3rdwave.lk'),('OS 03','Naveen Ariyawansha',100,102,205,'077 211 9812','it@3rdwave.lk'),('OS 04','Rameesha Sithmini Ihalage',100,101,206,'077 569 0040','rwave261@gmail.com');
/*!40000 ALTER TABLE `Employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ExternalSurveyor`
--

DROP TABLE IF EXISTS `ExternalSurveyor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ExternalSurveyor` (
  `ExternalSurveyorId` int NOT NULL AUTO_INCREMENT,
  `EID` varchar(255) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `CompanyName` varchar(120) DEFAULT NULL,
  `Department` varchar(120) DEFAULT NULL,
  `JobTitleName` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ExternalSurveyorId`,`EID`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ExternalSurveyor`
--

LOCK TABLES `ExternalSurveyor` WRITE;
/*!40000 ALTER TABLE `ExternalSurveyor` DISABLE KEYS */;
INSERT INTO `ExternalSurveyor` VALUES (1,'pasan.chathinthaka.perera@gmail.com','Pasan Perera','Vortex Lanka','IT','Software Engineer'),(2,'test@test.com','test tested','test Company','test Dept','test Designation'),(100,'Naveen@gamil.com','Iresha Dasanayaka','Vortex Lanka','IT','Head of branch cum  Snr Visa Officer - CMB'),(101,'pasan@gmail.com','Iresha Dasanayaka','Vortex Lanka','IT','Head of branch cum  Snr Visa Officer - CMB'),(102,'abcd@gmail.com','Iresha Dasanayaka','Vortex Lanka','IT','Management Trainee');
/*!40000 ALTER TABLE `ExternalSurveyor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `JobTitle`
--

DROP TABLE IF EXISTS `JobTitle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `JobTitle` (
  `JobTitleID` int NOT NULL AUTO_INCREMENT,
  `JobTitleName` varchar(255) NOT NULL,
  PRIMARY KEY (`JobTitleID`,`JobTitleName`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `JobTitle`
--

LOCK TABLES `JobTitle` WRITE;
/*!40000 ALTER TABLE `JobTitle` DISABLE KEYS */;
INSERT INTO `JobTitle` VALUES (200,'Managing Director'),(201,'CCO'),(202,'COO'),(203,'Finance Executive'),(204,'CTO'),(205,'Network/System Adminstrator Trainee'),(206,'Assitant Accountant'),(207,'Administrative Assistant'),(208,'HR & Compliance Officer'),(209,'Management Trainee'),(210,'Associate Consultant'),(211,'Manager Operations (3WC/3WA)'),(212,'Manager Operations'),(213,'Head of branch cum  Snr Visa Officer - CMB'),(214,'Student Counsellor'),(215,'Snr Student Counsellor'),(216,'Associate Marketing'),(217,'Visa officer cum student Counsellor'),(218,'Management0'),(219,'Head of branch cum Snr Student Counsellor - Kandy'),(220,'Senior Marketing Executive');
/*!40000 ALTER TABLE `JobTitle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Login`
--

DROP TABLE IF EXISTS `Login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Login` (
  `UserName` varchar(90) NOT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`UserName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Login`
--

LOCK TABLES `Login` WRITE;
/*!40000 ALTER TABLE `Login` DISABLE KEYS */;
/*!40000 ALTER TABLE `Login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Peers`
--

DROP TABLE IF EXISTS `Peers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Peers` (
  `EID` varchar(45) NOT NULL,
  `PeerID` varchar(45) NOT NULL,
  PRIMARY KEY (`EID`,`PeerID`),
  KEY `fk_Peers_1PID_idx` (`PeerID`),
  CONSTRAINT `fk_Peers_1EID` FOREIGN KEY (`EID`) REFERENCES `Employee` (`EID`),
  CONSTRAINT `fk_Peers_1PID` FOREIGN KEY (`PeerID`) REFERENCES `Employee` (`EID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Peers`
--

LOCK TABLES `Peers` WRITE;
/*!40000 ALTER TABLE `Peers` DISABLE KEYS */;
INSERT INTO `Peers` VALUES ('58','1'),('69','35'),('96','35'),('FTC 01','35'),('OS 01','35'),('OS 02','35'),('1','58'),('35','69'),('96','69'),('FTC 01','69'),('OS 01','69'),('OS 02','69'),('FTC 02','94'),('35','96'),('69','96'),('FTC 01','96'),('OS 01','96'),('OS 02','96'),('35','FTC 01'),('69','FTC 01'),('96','FTC 01'),('OS 01','FTC 01'),('OS 02','FTC 01'),('94','FTC 02'),('35','OS 01'),('69','OS 01'),('96','OS 01'),('FTC 01','OS 01'),('OS 02','OS 01'),('35','OS 02'),('69','OS 02'),('96','OS 02'),('FTC 01','OS 02'),('OS 01','OS 02');
/*!40000 ALTER TABLE `Peers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `QuestionCategoryTable`
--

DROP TABLE IF EXISTS `QuestionCategoryTable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `QuestionCategoryTable` (
  `QuestionListID` int NOT NULL,
  `CategoryID` smallint NOT NULL,
  `CategoryName` varchar(90) NOT NULL,
  PRIMARY KEY (`QuestionListID`,`CategoryID`),
  CONSTRAINT `fk_QuestionListID` FOREIGN KEY (`QuestionListID`) REFERENCES `QuestionListTable` (`QuestionListID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `QuestionCategoryTable`
--

LOCK TABLES `QuestionCategoryTable` WRITE;
/*!40000 ALTER TABLE `QuestionCategoryTable` DISABLE KEYS */;
INSERT INTO `QuestionCategoryTable` VALUES (100,4,'Decision Making'),(100,5,'Organizational Vision / Goals'),(100,7,'Team esteem');
/*!40000 ALTER TABLE `QuestionCategoryTable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `QuestionListTable`
--

DROP TABLE IF EXISTS `QuestionListTable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `QuestionListTable` (
  `QuestionListID` int NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `FilePath` varchar(260) NOT NULL,
  PRIMARY KEY (`QuestionListID`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `QuestionListTable`
--

LOCK TABLES `QuestionListTable` WRITE;
/*!40000 ALTER TABLE `QuestionListTable` DISABLE KEYS */;
INSERT INTO `QuestionListTable` VALUES (100,'2023-12-01','/tmp/phpZmXXZt');
/*!40000 ALTER TABLE `QuestionListTable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `QuestionsTable`
--

DROP TABLE IF EXISTS `QuestionsTable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `QuestionsTable` (
  `QuestionListID` int NOT NULL,
  `CategoryID` smallint NOT NULL,
  `QuestionNumber` smallint NOT NULL,
  `Question` varchar(255) NOT NULL,
  PRIMARY KEY (`QuestionListID`,`CategoryID`,`QuestionNumber`),
  CONSTRAINT `fk_QuestionCategory` FOREIGN KEY (`QuestionListID`, `CategoryID`) REFERENCES `QuestionCategoryTable` (`QuestionListID`, `CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `QuestionsTable`
--

LOCK TABLES `QuestionsTable` WRITE;
/*!40000 ALTER TABLE `QuestionsTable` DISABLE KEYS */;
INSERT INTO `QuestionsTable` VALUES (100,4,1,'Demonstrates transperancy in the decision making process'),(100,4,2,'Involves team members in the decision making process'),(100,4,3,'Represents the team members and their challenges during the management decision making process'),(100,5,1,'Gives priority to Company objectives and targets over departmental and personal targets.'),(100,5,2,'Understands the link and importance of the individual, department KPIs towards the overall organizational performance.'),(100,5,3,'Independently Commits to learn and upgrade knowledge / skills. Grows with the organization'),(100,7,1,'Always Share knowledge and is concerned about developing fellow members.'),(100,7,2,'Appreciates and utilizes the knowledge even he/she is a  junior team member.'),(100,7,3,'Confident about the abilities and aware of his/her own limitations.');
/*!40000 ALTER TABLE `QuestionsTable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Supervisor`
--

DROP TABLE IF EXISTS `Supervisor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Supervisor` (
  `EID` varchar(45) NOT NULL,
  `SEID` varchar(45) NOT NULL COMMENT 'Provides mapping between the employees and supervisor(s). Note one employee can have multiple supervisors',
  PRIMARY KEY (`EID`,`SEID`),
  KEY `fk_Supervisor_2_idx` (`SEID`),
  CONSTRAINT `fk_Supervisor_1` FOREIGN KEY (`EID`) REFERENCES `Employee` (`EID`),
  CONSTRAINT `fk_Supervisor_2` FOREIGN KEY (`SEID`) REFERENCES `Employee` (`EID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Supervisor`
--

LOCK TABLES `Supervisor` WRITE;
/*!40000 ALTER TABLE `Supervisor` DISABLE KEYS */;
INSERT INTO `Supervisor` VALUES ('62','1'),('65','1'),('68','1'),('74','1'),('76','1'),('69','3W 001'),('94','3W 001'),('FTC 01','3W 001'),('FTC 02','3W 001'),('OS 01','3W 001'),('OS 02','3W 001'),('64','58'),('75','58'),('1','69'),('58','69'),('70','69'),('MT 05','69'),('35','94'),('69','94'),('96','94'),('FTC 01','94'),('MT 04','94'),('MT 06','94'),('MT 07','94'),('OS 01','94'),('OS 02','94'),('95','FTC 01'),('MT 01','FTC 01'),('MT 02','FTC 01'),('MT 03','FTC 01'),('69','FTC 02'),('FTC 01','FTC 02'),('OS 04','OS 01'),('OS 03','OS 02');
/*!40000 ALTER TABLE `Supervisor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Survey`
--

DROP TABLE IF EXISTS `Survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Survey` (
  `SurveyID` int NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `QuestionaireID` int NOT NULL,
  `State` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`SurveyID`),
  KEY `fk_Survey_1_idx` (`QuestionaireID`),
  CONSTRAINT `fk_Survey_1` FOREIGN KEY (`QuestionaireID`) REFERENCES `QuestionListTable` (`QuestionListID`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Survey`
--

LOCK TABLES `Survey` WRITE;
/*!40000 ALTER TABLE `Survey` DISABLE KEYS */;
INSERT INTO `Survey` VALUES (100,'2023-12-01',100,1);
/*!40000 ALTER TABLE `Survey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SurveyAssignment`
--

DROP TABLE IF EXISTS `SurveyAssignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SurveyAssignment` (
  `SurveyID` int NOT NULL,
  `AssignorEID` varchar(45) DEFAULT NULL,
  `EID` varchar(45) NOT NULL,
  `SurveyorEID` varchar(255) NOT NULL,
  `SurveyorType` tinyint(1) NOT NULL,
  `AssignedDate` date NOT NULL,
  `ResponseDate` date DEFAULT NULL,
  PRIMARY KEY (`SurveyID`,`EID`,`SurveyorEID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SurveyAssignment`
--

LOCK TABLES `SurveyAssignment` WRITE;
/*!40000 ALTER TABLE `SurveyAssignment` DISABLE KEYS */;
INSERT INTO `SurveyAssignment` VALUES (100,'test','1','1',0,'2023-12-04',NULL),(100,'test','35','35',0,'2023-12-04',NULL),(100,'test','35','69',4,'2023-12-04',NULL),(100,'test','35','94',1,'2023-12-04',NULL),(100,'test','35','abcd@gmail.com',5,'2023-12-04',NULL),(100,'test','35','MT 03',5,'2023-12-04',NULL),(100,'test','35','OS 01',4,'2023-12-04',NULL),(100,'test','35','OS 02',4,'2023-12-04',NULL),(100,'test','35','pasan.chathinthaka.perera@gmail.com',5,'2023-12-04','2023-12-04'),(100,'test','35','test@test.com',5,'2023-12-04',NULL),(100,'test','69','69',0,'2023-12-04','2023-12-04'),(100,'test','69','FTC 01',4,'2023-12-04','2023-12-04'),(100,'test','69','pasan.chathinthaka.perera@gmail.com',5,'2023-12-04','2023-12-04'),(100,'test','95','95',0,'2023-12-01',NULL),(100,'test','95','FTC 01',1,'2023-12-01',NULL),(100,'test','95','MT 07',5,'2023-12-01',NULL),(100,'test','95','Vortex Lanka',5,'2023-12-01',NULL);
/*!40000 ALTER TABLE `SurveyAssignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SurveyFeedback`
--

DROP TABLE IF EXISTS `SurveyFeedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SurveyFeedback` (
  `SurveyID` int NOT NULL,
  `EID` varchar(45) NOT NULL,
  `SurveyorEID` varchar(255) NOT NULL,
  `SurveyorType` tinyint(1) NOT NULL,
  `QuestionCategoryID` smallint NOT NULL,
  `QuestionID` smallint NOT NULL,
  `Rating` smallint NOT NULL,
  PRIMARY KEY (`SurveyID`,`EID`,`SurveyorEID`,`QuestionCategoryID`,`QuestionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SurveyFeedback`
--

LOCK TABLES `SurveyFeedback` WRITE;
/*!40000 ALTER TABLE `SurveyFeedback` DISABLE KEYS */;
INSERT INTO `SurveyFeedback` VALUES (100,'35','pasan.chathinthaka.perera@gmail.com',5,4,1,1),(100,'35','pasan.chathinthaka.perera@gmail.com',5,4,2,2),(100,'35','pasan.chathinthaka.perera@gmail.com',5,4,3,3),(100,'35','pasan.chathinthaka.perera@gmail.com',5,5,1,4),(100,'35','pasan.chathinthaka.perera@gmail.com',5,5,2,4),(100,'35','pasan.chathinthaka.perera@gmail.com',5,5,3,4),(100,'35','pasan.chathinthaka.perera@gmail.com',5,7,1,4),(100,'35','pasan.chathinthaka.perera@gmail.com',5,7,2,4),(100,'35','pasan.chathinthaka.perera@gmail.com',5,7,3,4),(100,'69','69',0,4,1,1),(100,'69','69',0,4,2,2),(100,'69','69',0,4,3,3),(100,'69','69',0,5,1,4),(100,'69','69',0,5,2,3),(100,'69','69',0,5,3,3),(100,'69','69',0,7,1,4),(100,'69','69',0,7,2,4),(100,'69','69',0,7,3,3),(100,'69','FTC 01',4,4,1,1),(100,'69','FTC 01',4,4,2,2),(100,'69','FTC 01',4,4,3,3),(100,'69','FTC 01',4,5,1,4),(100,'69','FTC 01',4,5,2,5),(100,'69','FTC 01',4,5,3,4),(100,'69','FTC 01',4,7,1,3),(100,'69','FTC 01',4,7,2,2),(100,'69','FTC 01',4,7,3,1),(100,'69','pasan.chathinthaka.perera@gmail.com',5,4,1,1),(100,'69','pasan.chathinthaka.perera@gmail.com',5,4,2,2),(100,'69','pasan.chathinthaka.perera@gmail.com',5,4,3,3),(100,'69','pasan.chathinthaka.perera@gmail.com',5,5,1,4),(100,'69','pasan.chathinthaka.perera@gmail.com',5,5,2,3),(100,'69','pasan.chathinthaka.perera@gmail.com',5,5,3,2),(100,'69','pasan.chathinthaka.perera@gmail.com',5,7,1,1),(100,'69','pasan.chathinthaka.perera@gmail.com',5,7,2,2),(100,'69','pasan.chathinthaka.perera@gmail.com',5,7,3,3);
/*!40000 ALTER TABLE `SurveyFeedback` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-04 16:19:20
