<?php

class Database_MySQLConnector extends Database_Connector {
    
    public function connect(){
        $this->connection = mysql_connect(
            $this->host, $this->user, $this->password);
    }
    
    public function select_database(){
        return mysql_select_db($this->database, $this->connection);
    }
    
    public function query(){
        $arguments = func_get_args();
        $query = array_shift($arguments);
        
        if(count($arguments))
            $query = sprintf($query, $arguments);
        
        return mysql_query($query, $this->connection);
    }
    
    public function num_rows($result){
        return mysql_num_rows($result);
    }
    
    public function insert_id(){
        return mysql_insert_id($this->connection);
    }
    
    public function fetch($result){
        return mysql_fetch_assoc($result);
    }
    
    public function escape($value){
       return mysql_real_escape_string($value, $this->connection);
    }
}

?>