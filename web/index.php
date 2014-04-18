<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application();

//registration of providers
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app['debug'] = true;

$app->get('/', function () use ($app){
	return $app['twig']->render('index.twig');
	}
);

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
'dbs.options' => array (
        'mysql_read' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'chat',
            'user'      => '11_krawczyk',
            'password'  => 'bartek',
            'charset'   => 'utf8',
        )
)));
//var_dump(db.options['path']);
$data = array(
    0 => array(
	'id_user' => '1',
        'name' => 'John',
        'email' => 'john@example.com',
    ),
    1 => array(
	'id_user' => '2',
        'name' => 'Mark',
        'email' => 'mark@example.com',
    ),
    2 => array(
	'id_user' => '3',
	'name' => 'Al',
	'email' => 'al@example.com',
    ),
    3 => array(
	'id_user' => '4',
	'name' => 'Bart',
	'email' => 'bart@example.com'
    ),
);

$app->get('/admin/users', function () use ($app, $data) {
    return $app['twig']->render(
        'users.twig', array('data' => $data)
    );
});

$app->get('/admin/users/{id}', function (Silex\Application $app, $id) use ($data) {
    $item = isset($data[$id])?$data[$id]:array();
    return $app['twig']->render(
        'show.twig', array('item' => $item)
    );
});

//db
$app->get('/users', function () use ($app) {
//    $sql = "SELECT * FROM user WHERE id = ?";
//    $user = $app['db']->fetchAssoc($sql, array((int) $id));

  //  return  "<h1>{$user['username']}</h1>".
    //        "<p>{$user['email']}</p>";
	$app['db']->fetchAll('SELECT * FROM user');	
});


//formularz logowania
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->match('/login', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('name', 'text', array('constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' =>5)))))
        ->add('email', 'text', array('constraints' => new Assert\Email()))
        ->add('gender', 'choice', array(
            'choices' => array(1 => 'male', 2 => 'female'),
            'expanded' => true,
	    'constraints' => new Assert\Choice(array(1, 2))
        ))
        ->getForm();

if('POST' == $request->getMethod()){
	$form->bind($request);

//    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data

        // redirect somewhere
        return $app->redirect('...');
    }
}
    // display the form
    return $app['twig']->render('login.twig', array('form' => $form->createView()));
});

$app->run();
