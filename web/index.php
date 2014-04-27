<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application();

//registration of providers

//twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

//translations
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app['debug'] = true;

//root direction
$app->get('/', function () use ($app){
	return $app['twig']->render('index.twig');
	}
);

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


//db

//ADMIN

//list of users
$app->get('admin/users', function () use ($app) {
    
    $user = $app['db']->fetchAll('SELECT * FROM user');
    var_dump($user);
    return $app['twig']->render('users.twig', array('user' => $user ));


});


//user specified by id
$app->get('/admin/users/{id}', function (Silex\Application $app, $id) {

    $sql = "SELECT user_id, username, email FROM user WHERE user_id = $id";

    $user = $app['db']->fetchAll("SELECT user_id, username, email FROM user WHERE user_id = $id");

    return $app['twig']->render('show.twig', array('user' => $user));
});

//delete user specified by id -- działa, ale zwraca błąd!!
$app->get('/admin/users/delete/{id}', function (Silex\Application $app, $id) {

    $sql = "SELECT user_id, username, email FROM user WHERE user_id = $id";

    $user = $app['db']->fetchAll("DELETE FROM user WHERE user_id = $id LIMIT 1");

    return $app['twig']->render('delete.twig', array('user' => $user));
});

//USER
//profile
$app->get('user/profile/{display_name}', function (Silex\Application $app, $display_name){
    
    $user = $app['db']->fetchAll("SELECT user_id, username, email, display_name FROM user WHERE display_name = '$display_name' LIMIT 1");

    return $app['twig']->render('profile.twig', array('user' => $user ));
});


//formularz logowania
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

//$conn = DriverManager::getConnection(array(/*..*/));
//$queryBuilder = $conn->createQueryBuilder();

$app->match('/signin', function (Request $request) use ($app) {
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

        $username = $data['name'];
        $email = $data['email'];
        $display_name = $data['display_name'];
        $password = $data['password'];
        $password2 = $data['password2'];

            if(isset($password) && isset($password2) && ($password == $password2)){

           $new_user = $app['db']->executeUpdate("INSERT INTO user (user_id, username, email, display_name, password) 
           VALUES (0,'" . $username . "', '" . $email . "', '" . $display_name . "', SHA('" . $password . "'))");

            var_dump($data);
            var_dump($data['name']);
    //exit;
            // do something with the data

            // redirect somewhere
            return $app->redirect('user/profile/' . $display_name );
            }
    }
}
    // display the form
    return $app['twig']->render('signin.twig', array('form' => $form->createView()));
});

//login
$app->match('/login', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'display_name' => 'Your name',
        'password' => 'Your password',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('name', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
        ->add('password', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
        ->getForm();

if('POST' == $request->getMethod()){
    $form->bind($request);

    //$form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        $display_name = $data['display_name'];
        $password = $data['password'];

        $check_user = $app['db']->fetchAll("SELECT * FROM user WHERE display_name ='$display_name' AND password = SHA('$password') LIMIT 1");
            var_dump($check_user);

            if(empty($check_user)){
                echo 'fail';  

                var_dump($data);
                var_dump($data['display_name']);
        //exit;
                // do something with the data

                // redirect somewhere
                return $app->redirect('user/profile/' . $display_name);
            }
    }
}
    // display the form
    return $app['twig']->render('login.twig', array('form' => $form->createView()));
});




//chat
//USER 

$app->match('/user/profile/{display_name}/chat', function (Request $request, $display_name) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'message' => ''
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('message', 'text')
        ->getForm();

if('post' == $request->getMethod()){
    $form->bind($request);

    $data = $form->getData();

    $message= $data['message'];
    var_dump($message);
    //$display_name = 'bartek';

    $new_message = $app['db']->executeUpdate("INSERT INTO chat (chat_id, posted_on, user_name, message) 
    VALUES (0, NOW(), '" . $display_name . "', '" . $message . "')");
    
    var_dump($message);


       //$display_messages = $app['db']->fetchAll('SELECT * FROM chat');

    

    //var_dump($display_messages);
}
//$display_messages = $app['db']->fetchAll('SELECT * FROM chat ORDER BY chat_id DESC LIMIT 10');
//require_once 'db.php';

return $app['twig']->render('chat.twig', array('form' => $form->createView()));
//include 'db.php';
// $display_messages = $app['db']->fetchAll('SELECT * FROM chat ORDER BY chat_id DESC');

//     return $app['twig']->render('chat.twig', array(
//     'form' => $form->createView(), 
//     //'new_message' => $new_message,
//     'display_messages' => $display_messages)
//     );

  /*  $display_messages = $app['db']->fetchAll('SELECT * FROM chat');

    var_dump($display_messages);

    return $app['twig']->render('display.twig', array('display_messages' => $display_messages ));*/



});




//$app->match('/user/profile/{display_name}/chat/', function () use ($app) {
    // some default data for when the form is displayed the first time

//    $display_messages = $app['db']->fetchAll('SELECT * FROM chat');

 //   var_dump($display_messages);

 //   return $app['twig']->render('display.twig', array('display_messages' => $display_messages ));
//});


//session user
//use Symfony\Component\HttpFoundation\Response;

$app->get('/logins', function () use ($app) {
    $username = $app['request']->server->get('PHP_AUTH_USER', false);
    $password = $app['request']->server->get('PHP_AUTH_PW');

    if ('igor' === $username && 'password' === $password) {
        $app['session']->set('user', array('username' => $username));
        return $app->redirect('/account');
    }

    $response = new Response();
    $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', 'site_login'));
    $response->setStatusCode(401, 'Please sign in.');
    return $response;
});

$app->get('/account', function () use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect('/login');
    }

    return "Welcome {$user['username']}!";
});

$app->run();
