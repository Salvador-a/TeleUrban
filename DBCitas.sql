
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `administra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administra` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_encargado` bigint NOT NULL,
  `id_area` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_encargado` (`id_encargado`,`id_area`),
  KEY `id_area` (`id_area`),
  CONSTRAINT `administra_ibfk_1` FOREIGN KEY (`id_encargado`) REFERENCES `encargado_personal` (`id`),
  CONSTRAINT `administra_ibfk_2` FOREIGN KEY (`id_area`) REFERENCES `area` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `administra` WRITE;
/*!40000 ALTER TABLE `administra` DISABLE KEYS */;
INSERT INTO `administra` VALUES (1,1,1),(2,2,2),(3,3,3),(4,4,4),(5,5,5),(6,6,6),(7,7,2),(8,8,5),(9,9,5);
/*!40000 ALTER TABLE `administra` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `area` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  `id_disponibilidad` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `id_disponibilidad` (`id_disponibilidad`),
  CONSTRAINT `area_ibfk_1` FOREIGN KEY (`id_disponibilidad`) REFERENCES `disponibilidad` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
INSERT INTO `area` VALUES (1,'Infraestructura',1),(2,'Administración',1),(3,'Innovación',1),(4,'Operaciones y Mantenimiento',1),(5,'Desarrollo y Diseño',1),(6,'Procesos',1);
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre_departamento` varchar(250) NOT NULL,
  `id_encargado` bigint NOT NULL,
  `descripcion` text,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departamento_ibfk_1` (`id_encargado`),
  CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`id_encargado`) REFERENCES `empleados` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'Desarrollo y Diseño',2,'Nuestro equipo especializado se encarga de dar vida a tus ideas en el mundo digital. Utilizando frameworks como Laravel y dominando tecnologías como PHP, HTML y CSS, creamos aplicaciones web de alto rendimiento y funcionalidad. Diseñamos interfaces visuales sorprendentes y envolventes que cautivan a los usuarios. Además, hacemos uso de herramientas como Figma para esculpir la arquitectura de sitios web con destreza y precisión.','3292d40f36842a22158673eec0209403'),(2,'Operaciones y Mantenimiento',3,'Nuestro equipo se dedica a gestionar la cadena de suministro, coordinar la producción y realizar tareas de mantenimiento preventivo y correctivo en sistemas tecnológicos. Administramos el inventario, mejoramos continuamente nuestras operaciones y garantizamos la estabilidad y seguridad de software y hardware. Enfocados en la eficiencia y calidad, buscamos maximizar la productividad, cumplir plazos establecidos y satisfacer las necesidades de los clientes. Nuestro objetivo es lograr una ejecución eficiente para alcanzar el éxito empresarial y la satisfacción de nuestros clientes, respaldando sus operaciones con soluciones confiables y eficientes.','92c1d8669b3b99770cf461b8737634c7'),(3,'Administración',4,'Este departamento se encarga de la creación, mantenimiento y mejora de software para la empresa. Los empleados de este departamento trabajan en proyectos de desarrollo web, aplicaciones móviles y sistemas internos. Su objetivo es proporcionar soluciones tecnológicas eficientes y de alta calidad para apoyar las operaciones de la empresa.','4194a8228585eaf151d698a97330e568'),(4,'Infraestructura',12,'Nuestro equipo se encarga de diseñar, construir y mantener las infraestructuras tecnológicas y físicas necesarias para que nuestros servicios funcionen de manera óptima. Trabajamos para asegurar la disponibilidad, confiabilidad y seguridad de nuestros sistemas, redes y equipos, así como para optimizar los recursos y la eficiencia energética. Con nuestro enfoque en la infraestructura de calidad, nos comprometemos a brindar a nuestros clientes un entorno estable y confiable que respalde su experiencia y garantice el éxito de sus proyectos.','b2c83b0c1b1f01205b44ef3885e40ba6'),(5,'Innovación',11,'Nuestro equipo se dedica a identificar oportunidades de mejora, fomentar la generación de ideas y promover la implementación de proyectos disruptivos. Utilizamos metodologías y herramientas especializadas para estimular la colaboración y la experimentación, buscando generar valor y diferenciación en un entorno empresarial dinámico. Con nuestro enfoque centrado en la innovación, buscamos impulsar el crecimiento y la transformación de nuestro negocio, siempre buscando estar a la vanguardia en nuestra industria.\r\n\r\n','8e0cbef04f1873039c8aabb0d156c4b7'),(6,' Procesos',1,'Nuestro equipo de Procesos se especializa en la optimización y perfeccionamiento de los flujos de trabajo empresariales. Nos apasiona simplificar procesos, identificar mejoras y convertir desafíos en oportunidades. Trabajamos en estrecha colaboración con todos los departamentos para impulsar la eficiencia operativa y el éxito sostenible de la organización.\r\n\r\n','13f0051277046bde1c219f75b59ae1df');
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `descripcion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `descripcion` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `descripcion` WRITE;
/*!40000 ALTER TABLE `descripcion` DISABLE KEYS */;
INSERT INTO `descripcion` VALUES (3,'Estancia Empresarial'),(2,'Prácticas Profesionales'),(1,'Servicio Social');
/*!40000 ALTER TABLE `descripcion` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `discapacidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discapacidad` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `discapacidad` WRITE;
/*!40000 ALTER TABLE `discapacidad` DISABLE KEYS */;
INSERT INTO `discapacidad` VALUES (4,'Auditiva'),(1,'Física'),(5,'Ninguna'),(6,'Otra discapacidad'),(2,'Psicosocial'),(3,'Visual');
/*!40000 ALTER TABLE `discapacidad` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `disponibilidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disponibilidad` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `disponibilidad` WRITE;
/*!40000 ALTER TABLE `disponibilidad` DISABLE KEYS */;
INSERT INTO `disponibilidad` VALUES (1,'Disponible'),(2,'Inhabilitado');
/*!40000 ALTER TABLE `disponibilidad` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (1,'Sonia','Quinteri','Ciudad de México','México','Gerente de Calidad','Gestión de Calidad (ISO 9001),Auditorías de Calidad,Mejora Continua (Lean, Six Sigma),Análisis y Control de Calidad','null','6c924f64827c6acca0313e9a10e97f3f'),(2,'Karla','López','Neza','Mexico','Ingeniera Frontend (Frontend Engineer)','HTML,CSS,JavaScript,React, Vue.js,Desarrollo Responsivo,Accesibilidad Web (WCAG)','null','aa13d3658ddba2a99b72e35c80e69cfa'),(3,'Gerente de Mantenimiento','Martínez','Mexico','México','Gerente de Proyectos','Mantenimiento Preventivo y Correctivo,Gestión de Sistemas Tecnológicos (hardware y software),Análisis de Fallas y Solución de Problemas,Programación y Planificación de Mantenimiento','null','603e5c647aad33fa55d059b018a77975'),(4,'Laura','Hernández','Mexico','México','Analista de Datos','Gestión de Proyectos,Metodologías Ágiles (Scrum, Kanban),Gestión de Recursos y Presupuestos,Coordinación de Equipos','null','9900427a177c053e3f343accef022a6a'),(11,'Casandra','Martinez','Mexico','México','Consultora de Innovación Tecnológica','Análisis de Mercado y Tendencias Tecnológicas,Estrategias de Innovación,Transformación Digital','null','c17dfd4e28e622c72b9b9ff36e3f0361'),(12,'Pedro','Gutierrez','Chimalhucan','México','Ingeniero de Redes','Configuración y administración de redes LAN/WAN,Seguridad de redes y firewalls,Protocolos de red (TCP/IP, DNS, DHCP),Optimización del rendimiento de la red','null','99f7d0d567d2f4319eeed561de45e001');
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `encargado_credencial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encargado_credencial` (
  `id` bigint NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `id_nivel` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `id_nivel` (`id_nivel`),
  CONSTRAINT `encargado_credencial_ibfk_1` FOREIGN KEY (`id`) REFERENCES `encargado_personal` (`id`),
  CONSTRAINT `encargado_credencial_ibfk_2` FOREIGN KEY (`id_nivel`) REFERENCES `nivel` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `encargado_credencial` WRITE;
/*!40000 ALTER TABLE `encargado_credencial` DISABLE KEYS */;
INSERT INTO `encargado_credencial` VALUES (1,'drodriguez@teleurban.mx','DRodriguez','1N7R435c7RUr4@DR',2),(2,'kalcala@teleurban.mx','KAlcala','4dm1N157R4c10N@Ka',2),(3,'jcoxtinica@teleurban.mx','JCoxtinixa','1nN0v4c10N@jC',2),(4,'fflores@teleurban.mx','FFlores','0p3r4c10N35-M4n73ni@ff',2),(5,'koaxaca@teleurban.mx','KOxaca','D354rR0110_di53-0@kO',2),(6,'bbarrera@teleurban.mx','BBarrera','PR0c3505%@BB',2),(7,'alexandroruelas2@hotmail.com','ARuelas','4dm1N157R4c10N@AR',1),(8,'gruelas@gruelas.com','GRuelas','D354rR0110_di53-0@Gr',1),(9,'damian.alanis98@gmail.com','DAlanis','D354rR0110_di53-0@D4',1);
/*!40000 ALTER TABLE `encargado_credencial` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `encargado_personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encargado_personal` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(25) NOT NULL,
  `apellidopaterno` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`,`apellidopaterno`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `encargado_personal` WRITE;
/*!40000 ALTER TABLE `encargado_personal` DISABLE KEYS */;
INSERT INTO `encargado_personal` VALUES (7,'Alexandro','Ruelas'),(6,'Brenda','Barrera'),(9,'Damian','Alanis'),(1,'Diego','Rodriguez'),(4,'Fernando','Flores'),(8,'Gabriel','Ruelas'),(3,'Jorge','Coxtinica'),(2,'Karen','Alcala'),(5,'Karla','Oaxaca');
/*!40000 ALTER TABLE `encargado_personal` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `entrevista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entrevista` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `a_paterno` varchar(255) NOT NULL,
  `a_materno` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `discapacidad_id` bigint DEFAULT NULL,
  `genero_id` bigint DEFAULT NULL,
  `semestre_id` bigint DEFAULT NULL,
  `universidad_id` bigint DEFAULT NULL,
  `curriculum` longblob,
  `fecha_hora` datetime NOT NULL,
  `area_id` bigint DEFAULT NULL,
  `modalidad_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discapacidad_id` (`discapacidad_id`),
  KEY `genero_id` (`genero_id`),
  KEY `semestre_id` (`semestre_id`),
  KEY `universidad_id` (`universidad_id`),
  KEY `area_id` (`area_id`),
  KEY `modalidad_id` (`modalidad_id`),
  CONSTRAINT `entrevista_ibfk_1` FOREIGN KEY (`discapacidad_id`) REFERENCES `discapacidad` (`id`),
  CONSTRAINT `entrevista_ibfk_2` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id`),
  CONSTRAINT `entrevista_ibfk_3` FOREIGN KEY (`semestre_id`) REFERENCES `semestre` (`id`),
  CONSTRAINT `entrevista_ibfk_4` FOREIGN KEY (`universidad_id`) REFERENCES `universidad` (`id`),
  CONSTRAINT `entrevista_ibfk_5` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`),
  CONSTRAINT `entrevista_ibfk_6` FOREIGN KEY (`modalidad_id`) REFERENCES `descripcion` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `entrevista` WRITE;
/*!40000 ALTER TABLE `entrevista` DISABLE KEYS */;
/*!40000 ALTER TABLE `entrevista` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE `genero` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (2,'Femenino'),(1,'Masculino');
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `nivel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nivel` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `nivel` WRITE;
/*!40000 ALTER TABLE `nivel` DISABLE KEYS */;
INSERT INTO `nivel` VALUES (2,'Administrador'),(1,'Jefe');
/*!40000 ALTER TABLE `nivel` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `semestre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semestre` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `grado` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grado` (`grado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `semestre` WRITE;
/*!40000 ALTER TABLE `semestre` DISABLE KEYS */;
INSERT INTO `semestre` VALUES (4,'Noveno'),(3,'Octavo'),(2,'Séptimo'),(1,'Sexto');
/*!40000 ALTER TABLE `semestre` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (2,'Aceptado'),(1,'Pendiente');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `universidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `universidad` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `universidad` WRITE;
/*!40000 ALTER TABLE `universidad` DISABLE KEYS */;
INSERT INTO `universidad` VALUES (1,'IPN'),(7,'Otra universidad'),(5,'TESCHI'),(3,'UAEM'),(4,'UAM'),(2,'UNAM'),(6,'UTN');
/*!40000 ALTER TABLE `universidad` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Salvador','Acosta','salvador.ar614@gmail.com','$2y$10$S9sHFtNr2UBe6g7eS9Q68.EVK2Pd4agmUThsY6X.XZRg4SgtrVIEy',1,'',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

