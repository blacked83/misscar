<?php
//Definición de zona horaria
date_default_timezone_set('UTC');

//Constantes de Directorios
define("BASE_URL", __DIR__ . "/");
define("ASSETS_URL", __DIR__ . "/assets/");
define("FONTS_URL", __DIR__ . "/assets/css/fonts/");
define("CLASS_URL", __DIR__ . "/class/");
define("CONTROLLERS_URL", __DIR__ . "/controllers/");
define("FUNCTIONS_URL", __DIR__ . "/functions/");
define("LOGS_URL", __DIR__ . "/logs/");
define("MODELS_URL", __DIR__ . "/models/");
define("SERVICES_URL", __DIR__ . "/services/");

// Constantes de conexión a BD
define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "root");
define("MYSQL_PASS", '12345678');
define("MYSQL_BD", "misscar");
define("MYSQL_CERT", "");
?>