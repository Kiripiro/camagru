<?php
class InvalidTokenException extends Exception
{
    public function __construct()
    {
        parent::__construct("Invalid token.");
    }
}