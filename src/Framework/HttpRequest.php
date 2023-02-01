<?php
class HttpRequest
{
    private $_url;
    private $_method;
    private $_param;
    private $_route;

    public function __construct($url = null, $method = null)
    {
        $this->_url = (is_null($url)) ? $_SERVER['REQUEST_URI'] : $url;
        $this->_method = (is_null($method)) ? $_SERVER['REQUEST_METHOD'] : $method;
        $this->_param = array();
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function getParam()
    {
        return $this->_param;
    }

    public function setRoute($route)
    {
        $this->_route = $route;
    }

    public function bindParam()
    {
        switch ($this->_method) {
            case "GET":
            case "DELETE":
                foreach ($this->_route->getParam() as $param) {
                    if (isset($_GET[$param])) {
                        $this->_param[] = $_GET[$param];
                    }
                }
            case "POST":
            case "PUT":
                foreach ($this->_route->getParam() as $param) {
                    if (isset($_POST[$param])) {
                        $this->_param[] = $_POST[$param];
                    }
                }
        }
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function run($config)
    {
        $this->bindParam();
        $this->_route->run($this, $config);
    }

    public function addParam($value)
    {
        $this->_param[] = $value;
    }
}