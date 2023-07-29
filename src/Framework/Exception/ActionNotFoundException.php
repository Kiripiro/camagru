<?php
class ActionNotFoundException extends Exception
{
    private $_actionName;

    public function __construct($actionName, $message = "Property missing")
    {
        $this->_actionName = $actionName;
        parent::__construct($message, '0040');
    }

    public function getMoreDetail()
    {
        return "Action " . $this->_actionName . " does not exist.";
    }
}