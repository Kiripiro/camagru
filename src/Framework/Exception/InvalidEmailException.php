<?php
class InvalidEmailException extends Exception
{
    public function __construct()
    {
        parent::__construct("Invalid email");
    }
}