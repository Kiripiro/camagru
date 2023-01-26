<?php
class NoRouteFoundException extends Exception
{
    private $_httpRequest;

    public function __construct($httpRequest, $message = "No route has been found")
    {
        $this->_httpRequest = $httpRequest;
        parent::__construct($message, "0002");
    }

    public function getMoreDetail()
    {
        return "Route '" . $this->_httpRequest->getUrl() . "' has not been found with method '" . $this->_httpRequest->getMethod() . "'";
    }
}