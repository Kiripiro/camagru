<?
class InvalidPasswordException extends Exception
{
    public function __construct()
    {
        parent::__construct("Invalid password.");
    }
}