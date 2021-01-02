<?php
    function dispatch($routing, $action_url)
    {
        if(!isset($routing[$action_url])) {
            throw new Exception('Controller is not defined');
        }

        $controller_name = $routing[$action_url];

        $model = [];
        $view_name = $controller_name($model);

        build_response($view_name, $model);
    }

    function build_response($view, $model){
        if(strpos($view, 'redirect:') === 0){
            $url = substr($view, strlen('redirect:'));
            header("Location: " . $url);
            exit;
        }else{
            render($view, $model);
        }
    }

    function render($view_name, $model){
        extract($model);
        include "view/" . $view_name . '.php';
    }