<?php

namespace Controller;

//include 'config.php';

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $adminController = $app['controllers_factory'];
        $adminController->get('/', array($this, 'index'));
        $adminController->get('/add', array($this, 'add'));
        $adminController->get('/edit', array($this, 'edit'));
        $adminController->get('/delete', array($this, 'delete'));
        $adminController->get('/view', array($this, 'view'));
        return $adminController;
    }

    public function index(Application $app)
    {

        // //db

        // //ADMIN

        // //list of users
        // $app->get('admin/users', function () use ($app) {
            
        //     $user = $app['db']->fetchAll('SELECT * FROM user');
        //     var_dump($user);
        //     return $app['twig']->render('users.twig', array('user' => $user ));


        // });

	       return 'Index Action';
    }

    public function login(Application $app){

        // $app->get('/adminlogin', function(Request $request) use ($app) {
        //     return $app['twig']->render('admin_login.twig', array(
        //         'error'         => $app['security.last_error']($request),
        //         'last_username' => $app['session']->get('_security.last_username'),
        //     ));
        // });

        // $app->get('/admin_login_check', function() use ($app) {
        //     return $app['twig']->render('admin_login_check.twig');
        // });
    }

    public function edit(Application $app)
    {
        return 'Edit Action';
    }

    public function delete(Application $app)
    {
        return 'Delete Action';
    }

    public function view(Application $app)
    {

        // //user specified by id
        // $app->get('/admin/users/{id}', function (Silex\Application $app, $id) {

        //     $sql = "SELECT user_id, username, email FROM user WHERE user_id = $id";

        //     $user = $app['db']->fetchAll("SELECT user_id, username, email FROM user WHERE user_id = $id");

        //     return $app['twig']->render('show.twig', array('user' => $user));
        // });

        // //delete user specified by id -- działa, ale zwraca błąd!!
        // $app->get('/admin/users/delete/{id}', function (Silex\Application $app, $id) {

        //     $sql = "SELECT user_id, username, email FROM user WHERE user_id = $id";

        //     $user = $app['db']->fetchAll("DELETE FROM user WHERE user_id = $id LIMIT 1");

        //     return $app['twig']->render('delete.twig', array('user' => $user));
        // });
        return 'View Action';
    }

}
