<?php
class userManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("users", "User", $datasource);
    }

    public function getByEmail($email)
    {
        $req = $this->_bdd->prepare("SELECT * FROM users WHERE email=?");
        $req->execute(array($email));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");
        return $req->fetch();
    }
}