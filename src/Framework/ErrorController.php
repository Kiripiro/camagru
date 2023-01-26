<?php
class ErrorController extends BaseController
{
    public function Show($exception)
    {
        // var_dump($exception);
        $this->addParam("exception", $exception);
        $this->view("error");
    }
}