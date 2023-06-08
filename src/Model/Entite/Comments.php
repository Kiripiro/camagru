<?php
class Comments
{
    private $id;
    private $user_id;
    private $picture_id;
    private $comment;
    private $date;
    private $username;

    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }
    public function getPicture_id()
    {
        return $this->picture_id;
    }
    public function getComment()
    {
        return $this->comment;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function getUsername()
    {
        return $this->username;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function setPictureId($picture_id)
    {
        $this->picture_id = $picture_id;
    }
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
}