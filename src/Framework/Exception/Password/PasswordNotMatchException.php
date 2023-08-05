<?php
class PasswordsNotMatchException extends Exception
{
    public function __construct()
    {
        parent::__construct("Passwords do not match");
    }
}