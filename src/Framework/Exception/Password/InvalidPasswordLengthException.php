<?php
class InvalidPasswordLengthException extends Exception
{
    public function __construct()
    {
        parent::__construct("Password must be at least 8 characters long");
    }
}