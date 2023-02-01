<?php
class UserNotFoundException extends Exception
{
    public function __construct($firstname, $lastname)
    {
        parent::__construct("User: $firstname $lastname not found");
    }
}