-- Crear la base de datos
CREATE DATABASE sistema_a_mvc;
USE sistema_a_mvc;

SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;
SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;
SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;
SET NAMES utf8mb4;
SET @OLD_TIME_ZONE=@@TIME_ZONE;
SET TIME_ZONE='+00:00';
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0;

DROP TABLE IF EXISTS `administra`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `administra` (
  `idadministra` bigint NOT NULL AUTO_INCREMENT,
  `idencargado` bigint NOT NULL,
  `idarea` bigint NOT NULL,
  PRIMARY KEY (`idadministra`),
  UNIQUE KEY `idencargado` (`idencargado`,`idarea`),
  KEY `idarea` (`idarea`),
  CONSTRAINT `administra_ibfk_1` FOREIGN KEY (`idencargado`) REFERENCES `encargado_personal` (`idencargado`),
  CONSTRAINT `administra_ibfk_2` FOREIGN KEY (`idarea`) REFERENCES `area` (`idarea`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `administra` WRITE;
ALTER TABLE `administra` DISABLE KEYS;
INSERT INTO `administra` VALUES (1,1,1),(2,2,2),(3,3,3),(4,4,4),(5,5,5),(6,6,6),(7,7,2),(8,8,5),(9,9,5);
ALTER TABLE `administra` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `agendacita`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `agendacita` (
  `idcita` bigint NOT NULL AUTO_INCREMENT,
  `fechahora` datetime NOT NULL,
  `folio` varchar(100) NOT NULL,
  `idarea` bigint NOT NULL,
  `idpostulante` bigint NOT NULL,
  `iddescripcion` bigint NOT NULL,
  `idstatus` bigint NOT NULL,
  PRIMARY KEY (`idcita`),
  UNIQUE KEY `folio` (`folio`),
  UNIQUE KEY `fechahora` (`fechahora`,`idarea`),
  KEY `idarea` (`idarea`),
  KEY `idpostulante` (`idpostulante`),
  KEY `iddescripcion` (`iddescripcion`),
  KEY `idstatus` (`idstatus`),
  CONSTRAINT `agendacita_ibfk_1` FOREIGN KEY (`idarea`) REFERENCES `area` (`idarea`),
  CONSTRAINT `agendacita_ibfk_2` FOREIGN KEY (`idpostulante`) REFERENCES `postulante` (`idpostulante`),
  CONSTRAINT `agendacita_ibfk_3` FOREIGN KEY (`iddescripcion`) REFERENCES `descripcion` (`iddescripcion`),
  CONSTRAINT `agendacita_ibfk_4` FOREIGN KEY (`idstatus`) REFERENCES `status` (`idstatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `agendacita` WRITE;
ALTER TABLE `agendacita` DISABLE KEYS;
ALTER TABLE `agendacita` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `area`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `area` (
  `idarea` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `iddisponibilidad` bigint NOT NULL,
  PRIMARY KEY (`idarea`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `iddisponibilidad` (`iddisponibilidad`),
  CONSTRAINT `area_ibfk_1` FOREIGN KEY (`iddisponibilidad`) REFERENCES `disponibilidad` (`iddisponibilidad`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `area` WRITE;
ALTER TABLE `area` DISABLE KEYS;
INSERT INTO `area` VALUES (1,'Infraestructura',1),(2,'Administración',1),(3,'Innovación',1),(4,'Operaciones y Mantenimiento',1),(5,'Desarrollo y Diseño',1),(6,'Procesos',1);
ALTER TABLE `area` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `descripcion`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `descripcion` (
  `iddescripcion` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`iddescripcion`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `descripcion` WRITE;
ALTER TABLE `descripcion` DISABLE KEYS;
INSERT INTO `descripcion` VALUES (3,'Estancia Empresarial'),(2,'Prácticas Profesionales'),(1,'Servicio Social');
ALTER TABLE `descripcion` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `discapacidad`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `discapacidad` (
  `iddiscapacidad` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`iddiscapacidad`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `discapacidad` WRITE;
ALTER TABLE `discapacidad` DISABLE KEYS;
INSERT INTO `discapacidad` VALUES (4,'Auditiva'),(1,'Física'),(5,'Ninguna'),(6,'Otra discapacidad'),(2,'Psicosocial'),(3,'Visual');
ALTER TABLE `discapacidad` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `disponibilidad`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `disponibilidad` (
  `iddisponibilidad` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`iddisponibilidad`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `disponibilidad` WRITE;
ALTER TABLE `disponibilidad` DISABLE KEYS;
INSERT INTO `disponibilidad` VALUES (1,'Disponible'),(2,'Inhabilitado');
ALTER TABLE `disponibilidad` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `empleados`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `empleados` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) DEFAULT NULL,
  `apellido` varchar(40) DEFAULT NULL,
  `ciudad` varchar(20) DEFAULT NULL,
  `pais` varchar(20) DEFAULT NULL,
  `puesto_trabajo` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `redes_sociales` text,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `empleados` WRITE;
ALTER TABLE `empleados` DISABLE KEYS;
INSERT INTO `empleados` VALUES (1,'Juan','González','Ciudad de México','México','Desarrollador de Software','Desarrollo Web, Programación','LinkedIn: linkedin.com/juangonzalez, Twitter: twitter.com/juangonzalez',NULL),(2,'María','López','Madrid','España','Diseñador UX/UI','Diseño de Interfaces, Experiencia de Usuario','LinkedIn: linkedin.com/marialopez, Behance: behance.net/marialopez',NULL),(3,'Carlos','Martínez','Buenos Aires','Argentina','Gerente de Proyectos','Gestión de Proyectos, Liderazgo de Equipos','LinkedIn: linkedin.com/carlosmartinez, GitHub: github.com/carlosmartinez',NULL),(4,'Laura','Hernández','Lima','Perú','Analista de Datos','Análisis de Datos, Minería de Datos','LinkedIn: linkedin.com/laurahernandez, Kaggle: kaggle.com/laurahernandez',NULL),(11,'Salvador','Acosta','Chimalhucan','México','Programación','HTML,SDsds',' ','abcbb8ba80dd6278c0cec11c73bc9170'),(12,'Pedro','Gutierrez','Chimalhucan','México','Desarrollador Full Stack','HTML,SASS,PHP,JS,LARAVEL',' ','de089bdaa04d625ec0c7f7546201bcf9');
ALTER TABLE `empleados` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `departamento`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `departamento` (
  `iddepartamento` bigint NOT NULL AUTO_INCREMENT,
  `nombre_departamento` varchar(250) NOT NULL,
  `encargado` bigint NOT NULL,
  `descripcion` text,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`iddepartamento`),
  CONSTRAINT `departamento_ibfk_1` FOREIGN KEY (`encargado`) REFERENCES `empleados` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `departamento` WRITE;
ALTER TABLE `departamento` DISABLE KEYS;
INSERT INTO `departamento` (`nombre_departamento`, `encargado`, `descripcion`, `imagen`) VALUES
('Desarrollo Web', 1, 'Departamento encargado del desarrollo de aplicaciones web.', NULL),
('Diseño UX/UI', 2, 'Departamento encargado del diseño de interfaces y experiencia de usuario.', NULL);
ALTER TABLE `departamento` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `encargado_credencial`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `encargado_credencial` (
  `idencargado` bigint NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `idnivel` bigint NOT NULL,
  PRIMARY KEY (`idencargado`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `idnivel` (`idnivel`),
  CONSTRAINT `encargado_credencial_ibfk_1` FOREIGN KEY (`idencargado`) REFERENCES `encargado_personal` (`idencargado`),
  CONSTRAINT `encargado_credencial_ibfk_2` FOREIGN KEY (`idnivel`) REFERENCES `nivel` (`idnivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `encargado_credencial` WRITE;
ALTER TABLE `encargado_credencial` DISABLE KEYS;
INSERT INTO `encargado_credencial` VALUES (1,'drodriguez@teleurban.mx','DRodriguez','1N7R435c7RUr4@DR',2),(2,'kalcala@teleurban.mx','KAlcala','4dm1N157R4c10N@Ka',2),(3,'jcoxtinica@teleurban.mx','JCoxtinixa','1nN0v4c10N@jC',2),(4,'fflores@teleurban.mx','FFlores','0p3r4c10N35-M4n73ni@ff',2),(5,'koaxaca@teleurban.mx','KOxaca','D354rR0110_di53-0@kO',2),(6,'bbarrera@teleurban.mx','BBarrera','PR0c3505%@BB',2),(7,'alexandroruelas2@hotmail.com','ARuelas','4dm1N157R4c10N@AR',1),(8,'gruelas@gruelas.com','GRuelas','D354rR0110_di53-0@Gr',1),(9,'damian.alanis98@gmail.com','DAlanis','D354rR0110_di53-0@D4',1);
ALTER TABLE `encargado_credencial` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `encargado_personal`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `encargado_personal` (
  `idencargado` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `apellidopaterno` varchar(25) NOT NULL,
  PRIMARY KEY (`idencargado`),
  UNIQUE KEY `nombre` (`nombre`,`apellidopaterno`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `encargado_personal` WRITE;
ALTER TABLE `encargado_personal` DISABLE KEYS;
INSERT INTO `encargado_personal` VALUES (7,'Alexandro','Ruelas'),(6,'Brenda','Barrera'),(9,'Damian','Alanis'),(1,'Diego','Rodriguez'),(4,'Fernando','Flores'),(8,'Gabriel','Ruelas'),(3,'Jorge','Coxtinica'),(2,'Karen','Alcala'),(5,'Karla','Oaxaca');
ALTER TABLE `encargado_personal` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `genero`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `genero` (
  `idgenero` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`idgenero`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `genero` WRITE;
ALTER TABLE `genero` DISABLE KEYS;
INSERT INTO `genero` VALUES (2,'Femenino'),(1,'Masculino');
ALTER TABLE `genero` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `nivel`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `nivel` (
  `idnivel` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`idnivel`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `nivel` WRITE;
ALTER TABLE `nivel` DISABLE KEYS;
INSERT INTO `nivel` VALUES (2,'Administrador'),(1,'Jefe');
ALTER TABLE `nivel` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `postulante`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `postulante` (
  `idpostulante` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `apellidopaterno` varchar(25) NOT NULL,
  `apellidomaterno` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `curriculum` varchar(100) NOT NULL,
  `iddiscapacidad` bigint NOT NULL,
  `idgenero` bigint NOT NULL,
  `idsemestre` bigint NOT NULL,
  `iduniversidad` bigint NOT NULL,
  PRIMARY KEY (`idpostulante`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `telefono` (`telefono`),
  UNIQUE KEY `curriculum` (`curriculum`),
  UNIQUE KEY `nombre` (`nombre`,`apellidopaterno`,`apellidomaterno`,`email`,`telefono`,`curriculum`,`iddiscapacidad`,`idgenero`,`idsemestre`,`iduniversidad`),
  KEY `iddiscapacidad` (`iddiscapacidad`),
  KEY `idgenero` (`idgenero`),
  KEY `idsemestre` (`idsemestre`),
  KEY `iduniversidad` (`iduniversidad`),
  CONSTRAINT `postulante_ibfk_1` FOREIGN KEY (`iddiscapacidad`) REFERENCES `discapacidad` (`iddiscapacidad`),
  CONSTRAINT `postulante_ibfk_2` FOREIGN KEY (`idgenero`) REFERENCES `genero` (`idgenero`),
  CONSTRAINT `postulante_ibfk_3` FOREIGN KEY (`idsemestre`) REFERENCES `semestre` (`idsemestre`),
  CONSTRAINT `postulante_ibfk_4` FOREIGN KEY (`iduniversidad`) REFERENCES `universidad` (`iduniversidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `postulante` WRITE;
ALTER TABLE `postulante` DISABLE KEYS;
ALTER TABLE `postulante` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `semestre`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `semestre` (
  `idsemestre` bigint NOT NULL AUTO_INCREMENT,
  `grado` varchar(250) NOT NULL,
  PRIMARY KEY (`idsemestre`),
  UNIQUE KEY `grado` (`grado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `semestre` WRITE;
ALTER TABLE `semestre` DISABLE KEYS;
INSERT INTO `semestre` VALUES (4,'Noveno'),(3,'Octavo'),(2,'Séptimo'),(1,'Sexto');
ALTER TABLE `semestre` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `status`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `status` (
  `idstatus` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`idstatus`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `status` WRITE;
ALTER TABLE `status` DISABLE KEYS;
INSERT INTO `status` VALUES (2,'Aceptado'),(1,'Pendiente');
ALTER TABLE `status` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `universidad`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `universidad` (
  `iduniversidad` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`iduniversidad`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `universidad` WRITE;
ALTER TABLE `universidad` DISABLE KEYS;
INSERT INTO `universidad` VALUES (1,'IPN'),(7,'Otra universidad'),(5,'TESCHI'),(3,'UAEM'),(4,'UAM'),(2,'UNAM'),(6,'UTN');
ALTER TABLE `universidad` ENABLE KEYS;
UNLOCK TABLES;

DROP TABLE IF EXISTS `usuarios`;
SET @saved_cs_client = @@character_set_client;
SET character_set_client = utf8mb4;
CREATE TABLE `usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) DEFAULT NULL,
  `apellido` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `confirmado` tinyint(1) DEFAULT NULL,
  `token` varchar(13) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SET character_set_client = @saved_cs_client;

LOCK TABLES `usuarios` WRITE;
ALTER TABLE `usuarios` DISABLE KEYS;
INSERT INTO `usuarios` VALUES (1,'Salvador','Acosta','salvador.ar614@gmail.com','$2y$10$S9sHFtNr2UBe6g7eS9Q68.EVK2Pd4agmUThsY6X.XZRg4SgtrVIEy',1,'',1);
ALTER TABLE `usuarios` ENABLE KEYS;
UNLOCK TABLES;

SET TIME_ZONE=@OLD_TIME_ZONE;
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT;
SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS;
SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION;
SET SQL_NOTES=@OLD_SQL_NOTES;
