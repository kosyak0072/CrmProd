<?php

/* Paths Defines */
define('APP_FOLDER', realpath(dirname(__FILE__)));
define('LIBS_DIR', APP_FOLDER . DIRECTORY_SEPARATOR . 'libs');
define('MODELS_DIR', APP_FOLDER . DIRECTORY_SEPARATOR . 'models');
define('TEMPLATES_DIR', APP_FOLDER . DIRECTORY_SEPARATOR . 'view');
define('CONTROLLERS_DIR', APP_FOLDER . DIRECTORY_SEPARATOR . 'Controller');
define('CSS_DIR',  '/resources/css/');
define('JS_DIR', '/resources/js/');
define('IMG_DIR', '/resources/img/');

define('DOMAIN', 'http://crm.com');

/* Load Models */
require MODELS_DIR . DIRECTORY_SEPARATOR . 'Customer.php';
require MODELS_DIR . DIRECTORY_SEPARATOR . 'Renderer.php';
require MODELS_DIR . DIRECTORY_SEPARATOR . 'Dispatcher.php';


require CONTROLLERS_DIR . DIRECTORY_SEPARATOR . 'baseController.php';
require CONTROLLERS_DIR . DIRECTORY_SEPARATOR . 'View.php';

define('DB_HOST', 'vipdip01.mysql.ukraine.com.ua');
define('DB_NAME', 'vipdip01_sverka');
define('DB_USER', 'vipdip01_sverka');
define('DB_PASS', 'knuphqsq');
