<?php

// FRONT CONTROLLER
//новая ветку в гите
// 1. Общие настройки
ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();
// 2. Подключение файлов системы
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');




$router = new Router();
$router->run();

