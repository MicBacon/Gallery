<?php
    include 'info.php';

    session_start();

    $router = new Router();

    var_dump($router);


    die('koniec');

    require '../../vendor/autoload.php';
    require_once  '../dispatcher.php';
    require_once '../controllers.php';

    require_once '../routing.php';

    $action_url = $_GET['action'];
    dispatch($routing, $action_url, $router);
