<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

//require_once 'config.php';

class IndexController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        //require_once 'config.php';
        $indexController = $app['controllers_factory'];
        $indexController->get('/', array($this, 'index'));
        return $indexController;
    }

    public function index(Application $app)
    {
        //root direction
        return $app['twig']->render('index.twig');
    }
}
