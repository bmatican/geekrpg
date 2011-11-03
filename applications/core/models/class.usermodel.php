<?php

class UserModel extends Geek_Model {
  public function __construct($tableName = null) {
    parent::__construct($tableName);
  }
  
  /**
   * @override
   */
  protected function _createTables() {
   $createUsers = 'CREATE TABLE IF NOT EXISTS ' 
      . $this->tablename
      .  ' ( '
      . ' id INT NOT NULL AUTO_INCREMENT, '
      . ' username INT NOT NULL, '
      . ' password INT NOT NULL DEFAULT 0, '
      . ' email INT NOT NULL, '
      . ' roleid INT NOT NULL '
      . ' PRIMARY KEY(id), '
      . ' UNIQUE KEY(username), '
      . ' CONSTRAINT fk_roleid FOREIGN KEY(roleid) REFERENCES Roles(id) '
      . ' ON UPDATE CASCADE ON DELETE SET DEFAULT '
      . ' )';
   
    $this->query($createUsers);
  }
  
  public function validateUser($username, $password) {
    $query = 'SELECT u.* FROM Users u '
      . ' WHERE u.username="' . $username . '"'
      . ' AND u.password="' . md5($password) . '"';
      
    $result = $this->_getResult($this->query($query));
    return $result;
  }
  
  public function existsUser($username) {
    $query = 'SELECT u.* FROM Users u WHERE u.username="' . $username . '"';
    return count($this->_getResult($this->query($query))) > 0;
  }
  
  public function searchUser($usernames) {
    $query = 'SELECT u.* FROM Users u '
      . ' WHERE u.username in ( '
      . $this->_createSetOfStrings($usernames)
      . ' )';
      
    return $this->_getResult($this->query($query));
  }
  
  public function getUserInformation($username) {
    $query = 'SELECT u.* FROM Users u '
      . ' WHERE u.username = "' . $username . '"';
      
    return $this->_getResult($this->query($query));
  }
}

?>
