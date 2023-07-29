<?php
class MissingUppercaseCharacterException extends Exception
{
    public function __construct()
    {
        parent::__construct("Missing uppercase character");
    }
}