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
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}