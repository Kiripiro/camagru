<?php
class PasswordNotMatchException extends Exception
{
    public function __construct()
    {
        parent::__construct("Password not match");
    }
}