<?php
class picturesManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("pictures", "Picture", $datasource);
    }
}