<?php 

use Dotenv\Dotenv; // Importa la clase Dotenv para manejar variables de entorno
use Model\ActiveRecord; // Importa la clase ActiveRecord para interactuar con la base de datos
require __DIR__ . '/../vendor/autoload.php'; // Requiere el archivo de carga automática de Composer

// Definir la constante CARPETA_IMAGENES
define('CARPETA_IMAGENES', __DIR__ . '/../public/img/galeria/');

// Añadir Dotenv
$dotenv = Dotenv::createImmutable(__DIR__); // Crea una nueva instancia de Dotenv
$dotenv->safeLoad(); // Carga las variables de entorno de forma segura

require 'funciones.php'; // Requiere el archivo de funciones
require 'database.php'; // Requiere el archivo de configuración de la base de datos

// Conectarnos a la base de datos
ActiveRecord::setDB($db); // Establece la conexión a la base de datos
