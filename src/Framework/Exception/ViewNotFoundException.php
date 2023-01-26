<?php
class ViewNotFoundException extends Exception
{
    public function __construct($message = "View has not been found")
    {
        parent::__construct($message, "0001");
    }
}