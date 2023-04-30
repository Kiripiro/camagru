<?php
class CommentManager extends BaseManager
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

    function getPostComments($picture_id)
    {
        try {

        }
    }
}