<?php

//pdo databse class 
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $stmt;
    private $error;
    public function __construct(){
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname.';port=3306';
        try{
          $this->dbh = new PDO($dsn, $this->user, $this->pass);
          $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
          $this->error = $e->getMessage();
          echo $this->error;
        }
      }
    //prepare statment with query 
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }
    public function bind($param, $value){
        $this->stmt->bindParam($param,$value);
    }
    //execute prepared stmt
    public function execute(){
        return $this->stmt->execute();
    }
    // Get results 
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    //GET SINGLE RECORD AS OBJECT
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    //GET ROW COUNT
    public function rowCount(){
        return $this->stmt->rowCount();
    }
    // fetch as associative array
    public function fetcher(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
}