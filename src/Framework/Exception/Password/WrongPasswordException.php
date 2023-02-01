<?php
class WrongPasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct("Wrong password. Please try again.");
    }
}