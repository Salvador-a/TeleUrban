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

CREATE TABLE `departamentos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre_departamento` varchar(250) NOT NULL,
  `id_encargado` bigint NOT NULL,
  `descripcion` text,
  `imagen` varchar(255) DEFAULT NULL,
  `publicado` tinyint(1) DEFAULT '0',
  `disponible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `departamento_ibfk_1` (`id_encargado`),
  CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`id_encargado`) REFERENCES `empleados` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `departamentos` WRITE;
ALTER TABLE `departamentos` DISABLE KEYS;
INSERT INTO `departamentos` VALUES (1,'Desarrollo y Diseño',2,'Nuestro equipo especializado se encarga de dar vida a tus ideas en el mundo digital. Utilizando frameworks como Laravel y dominando tecnologías como PHP, HTML y CSS, creamos aplicaciones web de alto rendimiento y funcionalidad. Diseñamos interfaces visuales sorprendentes y envolventes que cautivan a los usuarios. Además, hacemos uso de herramientas como Figma para esculpir la arquitectura de sitios web con destreza y precisión.','3292d40f36842a22158673eec0209403',1,1),(2,'Operaciones y Mantenimiento',3,'Nuestro equipo se dedica a gestionar la cadena de suministro, coordinar la producción y realizar tareas de mantenimiento preventivo y correctivo en sistemas tecnológicos. Administramos el inventario, mejoramos continuamente nuestras operaciones y garantizamos la estabilidad y seguridad de software y hardware. Enfocados en la eficiencia y calidad, buscamos maximizar la productividad, cumplir plazos establecidos y satisfacer las necesidades de los clientes. Nuestro objetivo es lograr una ejecución eficiente para alcanzar el éxito empresarial y la satisfacción de nuestros clientes, respaldando sus operaciones con soluciones confiables y eficientes.','92c1d8669b3b99770cf461b8737634c7',1,1),(3,'Administración',4,'Este departamento se encarga de la creación, mantenimiento y mejora de software para la empresa. Los empleados de este departamento trabajan en proyectos de desarrollo web, aplicaciones móviles y sistemas internos. Su objetivo es proporcionar soluciones tecnológicas eficientes y de alta calidad para apoyar las operaciones de la empresa.','4194a8228585eaf151d698a97330e568',1,1),(4,'Infraestructura',12,'Nuestro equipo se encarga de diseñar, construir y mantener las infraestructuras tecnológicas y físicas necesarias para que nuestros servicios funcionen de manera óptima. Trabajamos para asegurar la disponibilidad, confiabilidad y seguridad de nuestros sistemas, redes y equipos, así como para optimizar los recursos y la eficiencia energética. Con nuestro enfoque en la infraestructura de calidad, nos comprometemos a brindar a nuestros clientes un entorno estable y confiable que respalde su experiencia y garantice el éxito de sus proyectos.','b2c83b0c1b1f01205b44ef3885e40ba6',1,1),(5,'Innovación',11,'Nuestro equipo se dedica a identificar oportunidades de mejora, fomentar la generación de ideas y promover la implementación de proyectos disruptivos. Utilizamos metodologías y herramientas especializadas para estimular la colaboración y la experimentación, buscando generar valor y diferenciación en un entorno empresarial dinámico. Con nuestro enfoque centrado en la innovación, buscamos impulsar el crecimiento y la transformación de nuestro negocio, siempre buscando estar a la vanguardia en nuestra industria.','8e0cbef04f1873039c8aabb0d156c4b7',1,1),(6,' Procesos ',1,'Nuestro equipo de Procesos se especializa en la optimización y perfeccionamiento de los flujos de trabajo empresariales. Nos apasiona simplificar procesos, identificar mejoras y convertir desafíos en oportunidades. Trabajamos en estrecha colaboración con todos los departamentos para impulsar la eficiencia operativa y el éxito sostenible de la organización.','13f0051277046bde1c219f75b59ae1df',1,1),(13,' CEO',14,'sdasf sfds asaf as f','e26698b0e5069cec233042913bd33a78',0,0);
ALTER TABLE `departamentos` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `descripcion` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `descripcion` WRITE;
ALTER TABLE `descripcion` DISABLE KEYS;
INSERT INTO `descripcion` VALUES (3,'Estancia Empresarial'),(2,'Prácticas Profesionales'),(1,'Servicio Social');
ALTER TABLE `descripcion` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `discapacidad` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `discapacidad` WRITE;
ALTER TABLE `discapacidad` DISABLE KEYS;
INSERT INTO `discapacidad` VALUES (4,'Auditiva'),(1,'Física'),(5,'Ninguna'),(6,'Otra discapacidad'),(2,'Psicosocial'),(3,'Visual');
ALTER TABLE `discapacidad` ENABLE KEYS;
UNLOCK TABLES;

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
  `departamento_id` bigint DEFAULT NULL,
  `puesto_trabajo_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departamento_id` (`departamento_id`),
  KEY `puesto_trabajo_id` (`puesto_trabajo_id`),
  CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`),
  CONSTRAINT `empleados_ibfk_2` FOREIGN KEY (`puesto_trabajo_id`) REFERENCES `puestos_trabajo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `empleados` WRITE;
ALTER TABLE `empleados` DISABLE KEYS;
INSERT INTO `empleados` VALUES (1,'Sonia','Quinteri','Ciudad de México','México','Gerente de Calidad','Gestión de Calidad (ISO 9001),Auditorías de Calidad,Mejora Continua (Lean, Six Sigma),Análisis y Control de Calidad','null','6c924f64827c6acca0313e9a10e97f3f',6,2),(2,'Karla','López','Neza','Mexico','Ingeniera Frontend (Frontend Engineer)','HTML,CSS,JavaScript,React, Vue.js,Desarrollo Responsivo,Accesibilidad Web (WCAG)','null','aa13d3658ddba2a99b72e35c80e69cfa',1,2),(3,'Sebastián','Martínez','Mexico','México','Gerente de Proyectos','Mantenimiento Preventivo y Correctivo,Gestión de Sistemas Tecnológicos (hardware y software),Análisis de Fallas y Solución de Problemas,Programación y Planificación de Mantenimiento','null','603e5c647aad33fa55d059b018a77975',2,2),(4,'Laura','Hernández','Mexico','México','Analista de Datos','Gestión de Proyectos,Metodologías Ágiles (Scrum, Kanban),Gestión de Recursos y Presupuestos,Coordinación de Equipos','null','9900427a177c053e3f343accef022a6a',3,2),(11,'Casandra','Martinez','Mexico','México','Consultora de Innovación Tecnológica','Análisis de Mercado y Tendencias Tecnológicas,Estrategias de Innovación,Transformación Digital','null','c17dfd4e28e622c72b9b9ff36e3f0361',5,2),(12,'Pedro ','Gutierrez','Chimalhucan','México','Ingeniero de Redes','Configuración y administración de redes LAN/WAN,Seguridad de redes y firewalls,Protocolos de red (TCP/IP, DNS, DHCP),Optimización del rendimiento de la red','null','99f7d0d567d2f4319eeed561de45e001',4,2),(14,' Pamela','Castro','Chimalhucan','México',NULL,'HTML','','f9ea60de89b2afa1a3db335b6d66cd09',13,1);
ALTER TABLE `empleados` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `entrevistas` (
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
  `modalidad_id` bigint DEFAULT NULL,
  `token` varchar(82) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  `departamento_id` bigint DEFAULT NULL,
  `habilidades` text,
  `tags` varchar(255) DEFAULT NULL,
  `estatus_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discapacidad_id` (`discapacidad_id`),
  KEY `genero_id` (`genero_id`),
  KEY `semestre_id` (`semestre_id`),
  KEY `universidad_id` (`universidad_id`),
  KEY `modalidad_id` (`modalidad_id`),
  KEY `fk_departamento_id` (`departamento_id`),
  KEY `fk_estatus` (`estatus_id`),
  CONSTRAINT `entrevistas_ibfk_1` FOREIGN KEY (`discapacidad_id`) REFERENCES `discapacidad` (`id`),
  CONSTRAINT `entrevistas_ibfk_2` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id`),
  CONSTRAINT `entrevistas_ibfk_3` FOREIGN KEY (`semestre_id`) REFERENCES `semestre` (`id`),
  CONSTRAINT `entrevistas_ibfk_4` FOREIGN KEY (`universidad_id`) REFERENCES `universidad` (`id`),
  CONSTRAINT `entrevistas_ibfk_6` FOREIGN KEY (`modalidad_id`) REFERENCES `descripcion` (`id`),
  CONSTRAINT `entrevistas_ibfk_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`),
  CONSTRAINT `fk_departamento_id` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`),
  CONSTRAINT `fk_estatus` FOREIGN KEY (`estatus_id`) REFERENCES `status` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `entrevistas` WRITE;
ALTER TABLE `entrevistas` DISABLE KEYS;
INSERT INTO `entrevistas` VALUES (27,' Salvador','Acosta','Rodriguez','salvador.sar.14@gmail.com','5610136814',3,1,4,5,_binary '403689384b0bad5cd1ee7be6678c5c37.pdf','2024-07-26 12:00:00',3,'CITA--20240721212313-d680c37705f2','2024-07-22 21:23:13',4,'Soy un egresado ','HTML,CSS',1);
ALTER TABLE `entrevistas` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `genero` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `genero` WRITE;
ALTER TABLE `genero` DISABLE KEYS;
INSERT INTO `genero` VALUES (2,'Femenino'),(1,'Masculino');
ALTER TABLE `genero` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `puestos_trabajo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre_puesto` varchar(255) NOT NULL,
  `rol` enum('admin','jefe','trabajador') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `puestos_trabajo` WRITE;
ALTER TABLE `puestos_trabajo` DISABLE KEYS;
INSERT INTO `puestos_trabajo` VALUES (1,'CEO','admin'),(2,'Gerente de área','jefe'),(3,'Empleado','trabajador');
ALTER TABLE `puestos_trabajo` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `semestre` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `grado` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grado` (`grado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `semestre` WRITE;
ALTER TABLE `semestre` DISABLE KEYS;
INSERT INTO `semestre` VALUES (4,'Noveno'),(3,'Octavo'),(2,'Séptimo'),(1,'Sexto');
ALTER TABLE `semestre` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `status` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `status` WRITE;
ALTER TABLE `status` DISABLE KEYS;
INSERT INTO `status` VALUES (2,'Aceptado'),(1,'Pendiente'),(3,'Rechazado');
ALTER TABLE `status` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `universidad` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `universidad` WRITE;
ALTER TABLE `universidad` DISABLE KEYS;
INSERT INTO `universidad` VALUES (1,'IPN'),(7,'Otra universidad'),(5,'TESCHI'),(3,'UAEM'),(4,'UAM'),(2,'UNAM'),(6,'UTN');
ALTER TABLE `universidad` ENABLE KEYS;
UNLOCK TABLES;

CREATE TABLE `usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) DEFAULT NULL,
  `a_paterno` varchar(40) DEFAULT NULL,
  `a_materno` varchar(40) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `genero_id` bigint DEFAULT NULL,
  `departamento_id` bigint DEFAULT NULL,
  `puesto_trabajo_id` bigint DEFAULT NULL,
  `confirmado` tinyint(1) DEFAULT NULL,
  `token` varchar(26) DEFAULT NULL,
  `rol` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `genero_id` (`genero_id`),
  KEY `departamento_id` (`departamento_id`),
  KEY `puesto_trabajo_id` (`puesto_trabajo_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id`),
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`),
  CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`puesto_trabajo_id`) REFERENCES `puestos_trabajo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `usuarios` WRITE;
ALTER TABLE `usuarios` DISABLE KEYS;
INSERT INTO `usuarios` VALUES (5,' Salvador','Acosta','Rodriguez','salvador.ar614@gmail.com','$2y$10$S/GxNVhZx1IDui0/BxdEiusaOqcI/CYcYw4hKhDzFTc8soUsKhGpG','5610136814',1,6,1,1,'',1),(6,' Pedro','Quintero','Lopez','salvador.sar.14@gmail.com','$2y$10$mrRCdQJbUuPlcnw7fBGlP./bCdXrhagJOWxoO6CCCD9uBDVcMPkzW','5610136814',1,6,2,1,'',2),(7,' Salvador','Acosta','Rodriguez','sacosta.sar@gmail.com','$2y$10$TNqdtsPDqknNqzc9xJuqCua9r/L8Ju9OX3VmhwNHLNKs2yG/VfqcO','5610136814',2,6,3,1,'',0),(8,' Elizabel','Loprz','Quintero','elizabellopez05@gmail.com','$2y$10$ppONVlx0rtEUB21ieUgosubVKuhrtzsoRIrEUXE6wBjxHO3KgzRE2','5610136814',2,6,2,1,'',2),(9,' Pamela','Quintero','Lopez','mastercatrin12@gmail.com','$2y$10$2ONNaZK4M9Ibw8N7bYtPUeI85XkNpz1KDVbEx3lDh.Lp7ws2X.GvO','5610136814',2,13,1,1,'',1);
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
