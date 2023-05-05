<?php
class CommentsManager extends BaseManager
{
    public function __construct($datasource)
    {
        parent::__construct("comments", "Comments", $datasource);
    }

    public function addComment($comment, $postId, $userId)
    {
        try {
            $commentObj = new Comments();
            $commentObj->setUserId($userId);
            $commentObj->setPictureId($postId);
            $commentObj->setComment($comment);
            $date = new DateTime();
            $date->setTimezone(new DateTimeZone("Europe/Paris"));
            $dateString = $date->format("Y-m-d H:i:s");
            $commentObj->setDate($dateString);
            $this->create($commentObj, ["user_id", "picture_id", "comment", "date"]);
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getPostComments($picture_id)
    {
        try {
            $req = $this->_bdd->prepare("SELECT * FROM comments WHERE picture_id=?");
            $req->execute(array($picture_id));
            $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Comments");
            return $req->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteComments($pictureId)
    {
        try {
            $req = $this->_bdd->prepare("DELETE FROM comments WHERE picture_id=?");
            $req->execute(array($pictureId));
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }
}