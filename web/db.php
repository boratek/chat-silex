<?php
 //require_once 'index.php';

$display_messages = $app['db']->fetchAll('SELECT * FROM chat ORDER BY chat_id DESC LIMIT 10');

 var_dump($display_messages);

    return $app['twig']->render('display.twig', array(
     
     //'new_message' => $new_message,
     'display_messages' => $display_messages)
    );

 