<?php
class StudioManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("pictures", "Pictures", $datasource);
    }

    public function postExists($pictureID)
    {
        $req = $this->_bdd->prepare("SELECT * FROM pictures WHERE path=?");
        $req->execute(array($pictureID));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Pictures");
        return $req->fetch();
    }

    public function addPost($pictureID, $userId, $description)
    {
        try {
            if ($this->postExists($pictureID)) {
                throw new Exception("Post already exists");
            }
            $post = new Pictures();
            $post->setUserId($userId);
            $post->setPath($pictureID);
            $post->setDescription($description);
            $this->create($post, ["userId", "path", "description"]);
            return $post;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getUsersPost($userId, $pictureID)
    {
        $req = $this->_bdd->prepare("SELECT * FROM pictures WHERE userId=? AND path=?");
        $req->execute(array($userId, $pictureID));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Pictures");
        return $req->fetch();
    }

    public function getAllUsersPosts($userId)
    {
        $req = $this->_bdd->prepare("SELECT * FROM pictures WHERE userId=?");
        $req->execute(array($userId));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Pictures");
        return $req->fetchAll();
    }

    public function deletePost($pictureID)
    {
        try {
            if (!$this->postExists($pictureID)) {
                throw new Exception("Post doesn't exists");
            }
            $req = $this->_bdd->prepare("DELETE FROM pictures WHERE path=?");
            $req->execute(array($pictureID));
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}