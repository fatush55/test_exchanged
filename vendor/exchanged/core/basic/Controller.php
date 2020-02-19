<?php

namespace exchanged\basic;


abstract class Controller
{
    public $route;
    public $controller;
    public $model;
    public $view;
    public $layout;
    public $data = [];

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function __construct($route)
    {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
    }

    public function getView()
    {
        $viewObject = new View($this->route, $this->layout, $this->view);
        $viewObject->render($this->data);
    }

}