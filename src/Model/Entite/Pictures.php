<?
class Pictures
{
    private $id;
    private $userId;
    private $path;
    private $description;
    private $likes;
    private $comments;
    private $date;

    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getLikes()
    {
        return $this->likes;
    }
    public function getComments()
    {
        return $this->comments;
    }
    public function getDate()
    {
        return $this->date;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    public function setPath($path)
    {
        $this->path = $path;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }
    public function setComments($comments)
    {
        $this->comments = $comments;
    }
    public function setDate($date)
    {
        $this->date = $date;
    }

}