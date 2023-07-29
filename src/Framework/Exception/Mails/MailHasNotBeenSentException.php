<?php
class MailHasNotBeenSentException extends Exception
{
    public function __construct($message = "Mail has not been sent", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}