<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Model\AdminModel;

class AdminController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $adminController = $app['controllers_factory'];
        $adminController->get('/{page}', array($this, 'index'))->value('page', 1)->bind('/admin/');
        $adminController->match('/login', array($this, 'login'))->bind('/admin/login');
        $adminController->match('/logout', array($this, 'logout'))->bind('/admin/logout');
        $adminController->match('/delete/{user_id}', array($this, 'delete'))->bind('/admin/delete');;
        $adminController->match('/view/{user_id}', array($this, 'view'))->bind('/admin/view');
        return $adminController;
    }

    public function index(Application $app, Request $request)
    {

        $pageLimit = 3;
        $page = (int) $request->get('page', 1);
        $adminModel = new AdminModel($app);
        $pagesCount = $adminModel->countUsersPages($pageLimit);

        if (($page < 1) || ($page > $pagesCount)) {
            $page = 1;
        }

        $users = $adminModel->getUsersPage($page, $pageLimit, $pagesCount);
        $paginator = array('page' => $page, 'pagesCount' => $pagesCount);
        
        return $app['twig']->render('admin/admin_index.twig', array('users' => $users, 'paginator' => $paginator ));
    }

    public function login(Application $app, Request $request)
    {

        $data = array();

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add('name', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->add('password', 'password', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $adminModel = new AdminModel($app);
            $admin = $adminModel->loginAdmin($form->getData());

            if (count($admin)) {
                $app['session']->set('admin', $admin);
                $app['session']->getFlashBag()->add('success', array('title' => 'Ok', 'content' => 'Hey Admin, login is successfull.'));
                return $app->redirect($app['url_generator']->generate('/admin'), 301);
            }
            else{
                $app['session']->getFlashBag()->add('error', array('title' => 'FALSE', 'content' => 'Login is not successfull. Please try again.'));
            }
        }

        return $app['twig']->render('admin/admin_login.twig', array('form' => $form->createView()));
    }

    public function logout(Application $app, Request $request)
    {
        if (($admin = $app['session']->get('admin')) !== null) {
            $app['session']->remove('user');
        }
        $app['session']->getFlashBag()->add('success', array('title' => 'Ok', 'content' => 'Hey Admin, You have been logout successfully.'));
        return $app['twig']->render('admin/admin_logout.twig');
    }

    public function delete(Application $app, Request $request)
    {
        $user_id = (int) $request->get('user_id', 0);

        $adminModel = new AdminModel($app);

        $user = $adminModel->deleteUser($user_id);

        $app['session']->getFlashBag()->add('success', array('title' => 'OK', 'content' => 'User has been succesfully deleted.'));
        
        return $app->redirect($app['url_generator']->generate('/admin/'), 301);
    }

    public function view(Application $app, Request $request)
    {
        $user_id = (int) $request->get('user_id', 0);

        $adminModel = new AdminModel($app);

        $user = $adminModel->viewUser($user_id);
        
        return $app['twig']->render('admin/admin_view.twig', array('user' => $user));
    }

}
