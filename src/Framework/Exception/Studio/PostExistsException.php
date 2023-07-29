<?php
class PostExistsException extends Exception
{
    public function __construct($message = 'Post already exists', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}