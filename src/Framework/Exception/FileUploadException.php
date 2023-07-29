<?php
class FileUploadException extends Exception
{
    public function __construct()
    {
        parent::__construct("Couldn't upload file");
    }
}