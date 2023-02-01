<?php
class UserAlreadyVerifiedException extends Exception
{
    public function __construct($firstname, $lastname)
    {
        parent::__construct("User: $firstname $lastname already verified");
    }
}