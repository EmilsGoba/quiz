<?php
class Database {
  private $pdo;

  public function __construct($config) {
    $connection_string = "mysql:" . http_build_query($config, "", ";");
    $this->pdo = new PDO($connection_string);
    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }

  public function execute($query_string, $params) {
    $query = $this->pdo->prepare($query_string);
    $query->execute($params); 
    return $query;
  }
}
?>