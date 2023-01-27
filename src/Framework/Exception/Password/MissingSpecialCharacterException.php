<?php
class MissingSpecialCharacterException extends Exception
{
    public function __construct()
    {
        parent::__construct("Missing special character");
    }
}