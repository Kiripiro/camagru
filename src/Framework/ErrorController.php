<?php
class ErrorController extends BaseController
{
    public function Show($exception)
    {
        $this->addParam("exception", $exception);
        $this->view("error");
    }
}