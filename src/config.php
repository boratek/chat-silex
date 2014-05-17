<?php

//require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

//registration of providers

//twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

//do generowanie urli i funkcji path
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

//trzeba było zakomentować, bo nie ma zadeklarowanych firewalli, więc zwraca błędy
//$app->register(new Silex\Provider\SecurityServiceProvider());

//$app->boot();

//translations
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app['debug'] = true;

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