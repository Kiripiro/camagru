<?php
class userManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("users", "User", $datasource);
    }

    public function getByMail($mail)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE email=?");
        $req->execute(array($mail));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
        return $req->fetch();
    }
}