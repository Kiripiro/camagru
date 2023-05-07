<?php
class Router
{
    private $_listRoute;

    public function __construct()
    {
        $stringRoute = file_get_contents('Config/route.json');
        $this->_listRoute = json_decode($stringRoute);
    }

    public function findRoute($httpRequest, $basepath)
    {
        $url = str_replace($basepath, "", $httpRequest->getUrl());
        $method = $httpRequest->getMethod();

        $urlHasParam = str_contains($url, "?");
        if ($urlHasParam && $method == "GET") {
            $key = substr($url, strpos($url, "?") + 1, strpos($url, "=") - strpos($url, "?") - 1);
            $value = substr($url, strpos($url, "=") + 1);
            $url = substr($url, 0, strpos($url, "?"));
            $param = array($key => $value);
            $httpRequest->addParam($param);
        }

        $routeFound = array_filter($this->_listRoute, function ($route) use ($url, $method) {
            return preg_match("#^" . $route->path . "$#", $url) && $route->method == $method;
        });
        $numberRoute = count($routeFound);
        if ($numberRoute > 1) {
            throw new MultipleRouteFoundException();
        } else if ($numberRoute == 0) {
            throw new NoRouteFoundException($httpRequest);
        } else {
            return new Route(array_shift($routeFound));
        }
    }
}