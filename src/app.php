<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

//rejestracja kontrolerów
$app->mount('/index/', new Controller\IndexController());
$app->mount('/admin/', new Controller\AdminController());
$app->mount('/user/', new Controller\UserController());

$app['debug'] = true;
   
require_once 'config.php';

$app->run();
