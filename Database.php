<?php

class Database {

    public $pdo;
    // uztaisit metodi query
    public function __construct($config) {
        
        $dsn = "mysql:" . http_build_query($config, "", ";");

        // PHP data object
        $this->pdo = new PDO($dsn);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
 
    public function query($sql, $params){
        //Data source name
       
        // sagatavot vaicajumu
        $statement = $this->pdo->prepare($sql);
        // izpildit vaicajumu
        $statement->execute($params);
        return $statement;
    }
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}




