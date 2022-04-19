<?php
    session_start();

    defined('ROOT_PATH') || define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));

    require '../../vendor/autoload.php';
    require_once  '../dispatcher.php';
    require_once '../controllers.php';

    require_once '../routing.php';

    $action_url = $_GET['action'];
    dispatch($routing, $action_url);
