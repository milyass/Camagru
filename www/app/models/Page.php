<?
class Page{
    private $db;
    public function __construct(){
        $this->db = new Database;
    }
   
    public function getAllImagesPaged($first_result){
        $this->db->query('SELECT im.*, (SELECT COUNT(*) FROM likes lik WHERE lik.imageid = im.id) as \'count\' , (SELECT usr.name FROM users usr WHERE im.userid = usr.id) as \'names\' FROM images im 
        ORDER BY created_at 
        DESC LIMIT '. $first_result . ', 6');
        $results = $this->db->resultSet();
        return $results;
    }
    
    public function getNumber(){
        $this->db->query('SELECT * FROM images');
        $this->db->execute();
        if($results = $this->db->rowCount())
        return $results;
        return false;
    }

}
