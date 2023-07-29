<?php
class Route
{
    private $_path;
    private $_controller;
    private $_action;
    private $_method;
    private $_param;
    private $_manager;

    public function __construct($route)
    {
        $this->_path = $route->path;
        $this->_controller = $route->controller;
        $this->_action = $route->action;
        $this->_method = $route->method;
        $this->_param = $route->param;
        $this->_manager = $route->manager;
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getParam()
    {
        return $this->_param;
    }

    public function getManager()
    {
        return $this->_manager;
    }

    public function run($httpRequest, $config)
    {
        $controller = null;
        $controllerName = $this->_controller . "Controller";
        if (class_exists($controllerName)) {
            $controller = new $controllerName($httpRequest, $config);
            if (method_exists($controller, $this->_action)) {
                $controller->{$this->_action}(...$httpRequest->getParam());
            } else {
                ($controller->{$this->_action});
                throw new ActionNotFoundException($this->_action);
            }
        } else {
            throw new ControllerNotFoundException();
        }
    }
}