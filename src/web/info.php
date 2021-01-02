<?php
class Router {
    public $controller;
    public $action;
    public $id;

    public $queryParams;

    public function __construct()
    {
        $this->queryParams = $_SERVER['QUERY_STRING'];
        $requestUri = $_SERVER['REQUEST_URI'];
        $explode = explode("/", $requestUri);

        $this->controller = $explode[1];
        $this->action = isset($explode[2]) ? $explode[2] : 'list';
        $this->id = isset($explode[3]) ? $explode[3] : null;
    }
}
