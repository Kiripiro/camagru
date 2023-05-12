<?php
class LikesManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("likes", "Likes", $datasource);
    }

    public function likeExists($pictureID, $userId)
    {
        $req = $this->_bdd->prepare("SELECT * FROM likes WHERE picture_id=? AND user_id=?");
        $req->execute(array($pictureID, $userId));
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Likes");
        return $req->fetch();
    }

    public function addLike($pictureID, $userId)
    {
        try {
            if ($this->likeExists($pictureID, $userId)) {
                if ($this->removeLike($pictureID, $userId)) {
                    return "Like removed";
                }
                throw new Exception("Couldn't remove like");
            }
            $like = new Likes();
            $like->setPictureID($pictureID);
            $like->setUserID($userId);
            $this->create($like, ["picture_id", "user_id"]);
            return "Like added";
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function removeLike($pictureID, $userId)
    {
        try {
            if (!$this->likeExists($pictureID, $userId)) {
                throw new Exception("Like doesn't exists");
            }
            $req = $this->_bdd->prepare("DELETE FROM likes WHERE picture_id=? AND user_id=?");
            $req->execute(array($pictureID, $userId));
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteLikes($pictureId)
    {
        try {
            $req = $this->_bdd->prepare("DELETE FROM likes WHERE picture_id=?");
            $req->execute(array($pictureId));
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getPostLikes($picture_id)
    {
        try {
            $req = $this->_bdd->prepare("SELECT * FROM likes WHERE picture_id=?");
            $req->execute(array($picture_id));
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Likes");
            return $req->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getById($id)
    {
        try {
            $req = $this->_bdd->prepare("SELECT * FROM likes WHERE id=?");
            $req->execute(array($id));
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Likes");
            return $req->fetch();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteByUser($id)
    {
        try {
            $req = $this->_bdd->prepare("DELETE FROM likes WHERE user_id=?");
            $req->execute(array($id));
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}