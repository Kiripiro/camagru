<?php
class ControllerNotFoundException extends Exception
{
    public function __construct($message = "Controller has not been found")
    {
        parent::__construct($message, "0001");
    }
}