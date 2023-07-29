<?php
class InvalidFileException extends Exception
{
    public function __construct($mime_type)
    {
        parent::__construct("Invalid file: " . $mime_type);
    }
}