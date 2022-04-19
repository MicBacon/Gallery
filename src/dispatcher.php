<?php
    require_once 'utilities.php';

    function dispatch($routing, $action_url) {
        if(!isset($routing[$action_url])) {
            throw new Exception('Controller is not defined');
        }

        $controller_name = $routing[$action_url];

        $model = [];
        if(isset($_SESSION['profile'])) {
            $model['profile'] = $_SESSION['profile'];
        }
        if($controller_name != 'registration' && $controller_name != 'login' && $controller_name != 'start'
            && $controller_name != 'gallery' && !isLogin()) {
            $view_name = 'redirect:login';
        } else {
            $view_name = $controller_name($model);
        }

        build_response($view_name, $model);
    }

    function build_response($view, $model) {
        if(strpos($view, 'redirect:') === 0){
            $action = substr($view, strlen('redirect:'));
            if($action === 'login'){
                unset($_SESSION['user_id']);
            }
            $url = getLinkByAction($action);
            header("Location: " . $url);
            exit;
        }else{
            $model['messages'] = getMessages();
            render($view, $model);
        }
    }

    function render($view_name, $model){
        extract($model);
        include "view/" . $view_name . '.php';
    }

