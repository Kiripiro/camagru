<?php
class MissingNumberException extends Exception
{
    public function __construct()
    {
        parent::__construct("Missing number");
    }
}