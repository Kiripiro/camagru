<?php
class EmptyFieldsException extends Exception
{
    private $_fields;

    public function __construct($fields)
    {
        $this->_fields = $fields;
        parent::__construct("Empty field(s): $this->_fields", 0030);
    }

    public function getMoreDetail()
    {
        return "The following field(s) are empty: " . $this->_fields;
    }
}