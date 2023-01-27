<?php

//UserAlreadyExistsException for login and email
class UserAlreadyExistsException extends Exception
{
    public function __construct($key, $value)
    {
        parent::__construct("User with this $key: $value already exists");
    }
}