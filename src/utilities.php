<?php
    require_once 'business.php';

    function isLogin() {
        return isset($_SESSION['profile']);
    }

    function unlog_user() {
        session_destroy();
    }

    function getLinkByAction($action) {
        global $routing;

        foreach($routing as $route => $routeAction) {
            if($routeAction === $action) {
                return $route;
            }
        }

        throw new InvalidArgumentException('Action is not available');
    }

    /**
     * @param $name string
     * @return mixed|null
     */
    function getPostParam($name, $defaultValue = null) {
        return (isset($_POST[$name])) ? $_POST[$name] : $defaultValue;
    }

    /**
     * @param $type string
     * @param $text string
     */
    function addErrorMessage($type, $text) {
        if(!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        }

        array_push($_SESSION['messages'], array('type' => $type, 'text' => $text));
    }

    function getMessages() {
        $messages = (isset($_SESSION['messages'])) ? $_SESSION['messages'] : array();
        unset($_SESSION['messages']);
        return $messages;
    }
