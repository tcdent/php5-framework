<?php

abstract class Database_Connector {
    
    // Connection information.
    public $host;
    public $user;
    public $password;
    public $database;
    
    // Connection resource.
    public $connection;
    
    public function __construct($config){
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->database = $config['database'];
        
        $this->connect();
        $this->select_database();
    }
    
    abstract function connect();
    abstract function select_database();
    
    abstract function query();
    abstract function num_rows($result);
    abstract function fetch($result);
    
    abstract function escape($value);
    
    public function fetch_first($query){
        $handle = $this->query($query);
        return ($handle)? $this->fetch($handle) : NULL;
    }
    
    public function fetch_all($query){
        if(!$handle = $this->query($query))
            return array();
        
        $results = array();
        while($result = $this->fetch($handle)){
            $results[] = $result;
        }
        return $results;
    }
}

?>