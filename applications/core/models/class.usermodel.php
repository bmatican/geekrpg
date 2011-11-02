<?php

class UserModel extends Geek_Model {
  public function __construct($tableName) {
    parent::__construct($tableName);
  }
  
  public function validateUser($username, $password) {
    $query = 'SELECT * FROM Users '
      . ' WHERE username="' . $username . '"'
      . ' AND password="' . md5($password) . '"';
      
    $result = $this->_getResult($this->query($query));
    return $result;
  }
  
  public function existsUser($username) {
    $query = "SELECT * FROM Users WHERE username='$username'";
    return count($this->_getResult($this->query($query))) > 0;
  }
}

?>
