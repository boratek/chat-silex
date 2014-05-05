<?php

require_once __DIR__.'../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

//translations
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app['debug'] = true;

//root direction
// $app->get('/', function () use ($app){
// 	return $app['twig']->render('index.twig');
// 	}
// );

//db register and config
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
'dbs.options' => array (
        'mysql_read' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'chat',
            'user'      => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
        )
)));
  
//session
$app->register(new Silex\Provider\SessionServiceProvider());