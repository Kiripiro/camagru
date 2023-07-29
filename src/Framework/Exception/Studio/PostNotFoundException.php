<?php
class PostNotFoundException extends Exception
{
    public function __construct($message = 'Post not found', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}