<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Model\UserModel;

class UserController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $userController = $app['controllers_factory'];
        $userController->match('/profile/{display_name}', array($this, 'index'))->bind('/profile');
        $userController->match('/profile/{display_name}/chat', array($this, 'chat'));
        $userController->match('/profile/{display_name}/display', array($this, 'display'));
        $userController->match('/register', array($this, 'register'))->bind('/register');
        $userController->get('/edit', array($this, 'edit'));
        $userController->get('/delete', array($this, 'delete'));
        $userController->get('/view', array($this, 'view'));
	    $userController->match('/login', array($this, 'login'))->bind('/login');
	    $userController->get('/logout', array($this, 'logout'))->bind('/logout');
        return $userController;
    }

    public function index(Application $app, $display_name)
    {
        $userModel = new UserModel($app);
        
        $user = $userModel->getUser($display_name);

        return $app['twig']->render('profile.twig', array('user' => $user ));
    }

    public function chat(Application $app, Request $request, $display_name)
    {

        $data = array(
            'message' => ''
        );

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add('message', 'text')
            ->getForm();

        $form->bind($request);

        $data = $form->getData();

        $message= $data['message'];

        $new_message = $app['db']->executeUpdate("INSERT INTO chat (chat_id, posted_on, user_name, message) 
        VALUES (0, NOW(), '" . $display_name . "', '" . $message . "')");
        
        return $app['twig']->render('chat.twig', array('form' => $form->createView(), 
        'display_name' => $display_name));
    }

    public function display(Application $app, Request $request)
    {

        $display_messages = $app['db']->fetchAll('SELECT * FROM chat ORDER BY chat_id DESC LIMIT 10');

        return $app['twig']->render('display.twig', array('display_messages' => $display_messages ));

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
        return 'View Action';
    }

    public function register(Application $app, Request $request)
    {
        // some default data for when the form is displayed the first time
        $data = array(
            'name' => 'Your name',
            'email' => 'Your email',
            'display_name' => 'Your nick',
            'password' => 'Your password',
            'password2' => 'Rewrite your password'
        );

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add('name', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
            ->add('email', 'text', array('constraints' => new Assert\Email()))
            ->add('display_name', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
            ->add('password', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
            ->add('password2', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
            ->getForm();

        if('POST' == $request->getMethod()){
         $form->bind($request);

            //$form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $display_name = $form->get('display_name')->getData();
                $password = $form->get('password')->getData();
                $password2 = $form->get('password2')->getData();

                if($password == $password2){
                    $register = new UserModel($app);
                    $new_user = $register->registerUser($data);

                    // redirect to user profile
                    $app['session']->getFlashBag()->add('message', array('title' => 'OK', 'content' => 'You are registered.'));
                    return $app->redirect('profile/' . $display_name );
                }
                else{
                    $app['session']->getFlashBag()->add('error', array('title' => 'FALSE', 'content' => 'Password must be set and must be repeated.'));
                }
            }
            
        }
        // display the form
        return $app['twig']->render('register.twig', array('form' => $form->createView()));
    }

    public function login(Application $app, Request $request)
    {
        $data = array();

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add('display_name', 'text', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->add('password', 'password', array(
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $usersModel = new UserModel($app);
            $user = $usersModel->loginUser($form->getData());
            $display_name = $form->get('display_name')->getData();

            if (count($user)) {
                $app['session']->set('user', $user);
                $app['session']->getFlashBag()->add('success', array('title' => 'Ok', 'content' => 'Login is successfull.'));
                return $app->redirect('profile/' . $display_name);
            }
            else{
                $app['session']->getFlashBag()->add('error', array('title' => 'FALSE', 'content' => 'Login is not successfull. Please try again.'));
            }
        }

        return $app['twig']->render('login.twig', array('form' => $form->createView()));
    }

    public function logout(Application $app, Request $request)
    {
        if (($user = $app['session']->get('user')) !== null) {
            $app['session']->remove('user');
        }
        $app['session']->getFlashBag()->add('success', array('title' => 'Ok', 'content' => 'You have been logout successfully.'));
        return $app['twig']->render('logout.twig');
    }
}