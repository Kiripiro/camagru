<?php
class UserNotVerifiedException extends Exception
{
    public function __construct($firstname, $lastname)
    {
        parent::__construct("User: $firstname $lastname is not verified, make sure to verify your account using the link sent to your email address.");
    }
}