<?php

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;


class UserController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $userController = $app['controllers_factory'];
        $userController->match('/profile/{display_name}', array($this, 'index'));
        $userController->match('/profile/{display_name}/chat', array($this, 'chat'));
        $userController->match('/profile/{display_name}/display', array($this, 'display'));
        $userController->get('/edit', array($this, 'edit'));
        $userController->get('/delete', array($this, 'delete'));
        $userController->get('/view', array($this, 'view'));
	    $userController->get('/register', array($this, 'register'));
	    $userController->get('/login', array($this, 'login'));
	    $userController->get('/logout', array($this, 'logout'));
        return $userController;
    }

    public function index(Application $app, $display_name)
    {
        //profile view
        $user = $app['db']->fetchAll("SELECT user_id, username, email, display_name FROM user WHERE display_name = '$display_name' LIMIT 1");

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
        var_dump($message);

        $new_message = $app['db']->executeUpdate("INSERT INTO chat (chat_id, posted_on, user_name, message) 
        VALUES (0, NOW(), '" . $display_name . "', '" . $message . "')");
        
        var_dump($new_message);

        return $app['twig']->render('chat.twig', array('form' => $form->createView(), 
        'display_name' => $display_name));
    }

    public function display(Application $app, Request $request){

        $display_messages = $app['db']->fetchAll('SELECT * FROM chat ORDER BY chat_id DESC LIMIT 10');

        //var_dump($display_messages);

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

    public function register(Application $app)
    {


    // $app->match('/signin', function (Request $request) use ($app) {
    //     // some default data for when the form is displayed the first time
    //     $data = array(
    //         'name' => 'Your name',
    //         'email' => 'Your email',
    //         'display_name' => 'Your nick',
    //         'password' => 'Your password',
    //         'password2' => 'Rewrite your password'
    //     );

    //     $form = $app['form.factory']->createBuilder('form', $data)
    //         ->add('name', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
    //         ->add('email', 'text', array('constraints' => new Assert\Email()))
    //         ->add('display_name', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
    //         ->add('password', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
    //         ->add('password2', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
    //         ->getForm();

    // if('POST' == $request->getMethod()){
    //  $form->bind($request);

    //     //$form->handleRequest($request);

    //     if ($form->isValid()) {
    //         $data = $form->getData();

    //         $username = $data['name'];
    //         $email = $data['email'];
    //         $display_name = $data['display_name'];
    //         $password = $data['password'];
    //         $password2 = $data['password2'];

    //             if(isset($password) && isset($password2) && ($password == $password2)){

    //            $new_user = $app['db']->executeUpdate("INSERT INTO user (user_id, username, email, display_name, password) 
    //            VALUES (0,'" . $username . "', '" . $email . "', '" . $display_name . "', SHA('" . $password . "'))");

    //             var_dump($data);
    //             var_dump($data['name']);
    //     //exit;
    //             // do something with the data

    //             // redirect somewhere
    //             return $app->redirect('user/profile/' . $display_name );
    //             }
    //     }
    // }
    //     // display the form
    //     return $app['twig']->render('signin.twig', array('form' => $form->createView()));
    // });   
	return 'Register Action';
    }

    public function login(Application $app)
    {
    // //login
    // $app->match('/login', function (Request $request) use ($app) {
    //     // some default data for when the form is displayed the first time
    //     $data = array(
    //         'display_name' => 'Your name',
    //         'password' => 'Your password',
    //     );

    //     $form = $app['form.factory']->createBuilder('form', $data)
    //         ->add('name', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
    //         ->add('password', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
    //         ->getForm();

    // if('POST' == $request->getMethod()){
    //     $form->bind($request);

    //     //$form->handleRequest($request);

    //     if ($form->isValid()) {
    //         $data = $form->getData();

    //         $display_name = $data['display_name'];
    //         $password = $data['password'];

    //         $check_user = $app['db']->fetchAll("SELECT * FROM user WHERE display_name ='$display_name' AND password = SHA('$password') LIMIT 1");
    //             var_dump($check_user);

    //             if(empty($check_user)){
    //                 echo 'fail';  

    //                 var_dump($data);
    //                 var_dump($data['display_name']);
    //         //exit;
    //                 // do something with the data

    //                 // redirect somewhere
    //                 return $app->redirect('user/profile/' . $display_name);
    //             }
    //     }
    // }
    //     // display the form
    //     return $app['twig']->render('login.twig', array('form' => $form->createView()));
    // });
	return 'Login Action';
    }

    public function logout(Application $app)
    {
	return 'Logout Action';
    }

}
