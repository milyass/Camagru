<?php
  class Post {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getAllMyImagesPaged($first_result,$id){
      $this->db->query('SELECT * FROM images WHERE userid=:id  ORDER BY created_at DESC LIMIT ' . $first_result . ', 6');
      $this->db->bind(':id', $id);
      $results = $this->db->resultSet();
      return $results;
    }

    public function getNumberByid($id){
      $this->db->query('SELECT * FROM images WHERE userid=:id');
      $this->db->bind(':id', $id);
      $this->db->execute();
      $results = $this->db->rowCount();
      return $results;
    }

    public function getNumber(){
      $this->db->query('SELECT * FROM images');
      $this->db->execute();
      $results = $this->db->rowCount();
      return $results;
    }

    public function getImages($id){

      $this->db->query('SELECT * FROM images WHERE userid=:id ORDER BY created_at DESC');
      $this->db->bind(':id', $id);
      $results = $this->db->resultSet();
      return $results;
    }

    public function savePic($id,$userid){
      $this->db->query('INSERT INTO images (userid,	imageid) VALUES(:userid, :imageid)');
      $this->db->bind(':userid', $userid);
      $this->db->bind(':imageid', $id);
      $this->db->execute();
    }

    public function findPicOwner($id){
      $this->db->query('SELECT * FROM images WHERE id=:id');
      $this->db->bind(':id',$id);
      $result = $this->db->fetcher();
      return $result;
    }
    
    public function deletePicture($id){
      $this->db->query('DELETE FROM images WHERE id=:id');
      $this->db->bind(':id',$id);
      $this->db->execute();
      $count = $this->db->rowCount();
      if($count > 0)
        return true;
      else
        return false;
    }
    public function jsonliked(){
     $this->db->query('SELECT * FROM likes');
     $results = $this->db->resultSet();
      return $results;
    }
    public function likesbyid($imgid){
      $this->db->query('SELECT * FROM likes WHERE imageid=:imageid');
      $this->db->bind(':imageid',$imgid);
      $this->db->execute();
      $count = $this->db->rowCount();
      return $count;
    }

    public function alreadyliked($imgid,$userid){
      $this->db->query('SELECT * FROM likes WHERE imageid=:imageid AND userid=:userid');
      $this->db->bind(':imageid',$imgid);
      $this->db->bind(':userid',$userid);
      $this->db->execute();
      $count = $this->db->rowCount();
      if($count > 0)
        return true;
      else
        return false;
    }

    public function liked($imgid,$userid){
      $this->db->query('INSERT INTO likes (imageid, userid) VALUES (:imageid ,:userid)');
      $this->db->bind(':imageid',$imgid);
      $this->db->bind(':userid',$userid);
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function unliked($imgid,$userid){
      $this->db->query('DELETE FROM likes WHERE imageid=:imageid AND userid=:userid');
      $this->db->bind(':imageid',$imgid);
      $this->db->bind(':userid',$userid);
      $this->db->execute();
      $count = $this->db->rowCount();
      if($count > 0)
        return true;
      else
        return false;
    }

    public function comment($comment,$cid,$uid){
      $this->db->query('INSERT INTO comments (cid, uid, message) VALUES (:cid ,:uid , :message)');
      $this->db->bind(':cid',$cid);
      $this->db->bind(':uid',$uid);
      $this->db->bind(':message',$comment);
      if($this->db->execute())
        return true;
      else 
        return false;
    }
    public function getComments($cid){
      $this->db->query('SELECT cmnts.*, (SELECT usr.name FROM users usr WHERE cmnts.uid = usr.id) as \'names\' FROM comments cmnts WHERE cid=:cid');
      $this->db->bind(':cid',$cid);
      $results = $this->db->resultSet();
      return $results;
    }
    
    public function isNotifiedPost($uid){
      $this->db->query("SELECT notification FROM users WHERE id=:id");
      $this->db->bind(':id',$uid);
      if($row = $this->db->fetcher()){
        if($row['notification'] == 1)
          return true;
        else
          return false;
      }
      else
        return false;
      }

      public function doesitExist($id){
        $this->db->query("SELECT * FROM images WHERE id=:id");
        $this->db->bind(':id',$id);
        $row = $this->db->single();
        if($this->db->rowCount() > 0){
          return true;
        } 
        else {
          return false;
          }
        }

  }