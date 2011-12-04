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
      . ' username VARCHAR(64) NOT NULL, '
      . ' password VARCHAR(128) NOT NULL, '
      . ' email VARCHAR(64) NOT NULL UNIQUE, '
      . ' roleid INT NOT NULL DEFAULT ' . ROLE_DEFAULT . ', '
      . ' PRIMARY KEY(id), '
      . ' UNIQUE KEY(username), '
      . ' CONSTRAINT fk_roleid FOREIGN KEY(roleid) REFERENCES Roles(id) '
      . ' ON UPDATE CASCADE ON DELETE SET DEFAULT '
      . ' )';
   
    $this->query($createUsers);
  }
  
  public function validateUser($username, $password) {
    $query = 'SELECT u.* FROM Users u'
      . ' WHERE u.username="' . $username . '"'
      . ' AND u.password="' . md5($password) . '"';
      
    $result = $this->_getResult($this->query($query));
    return $result;
  }
  
  public function existsUser($username) {
    $query = 'SELECT u.id FROM Users u WHERE u.username="' . $username . '"';
    return count($this->_getResult($this->query($query))) > 0;
  }
  
  public function searchUsers($usernames) {
    $query = 'SELECT DISTINCT(u.id), u.username, u.email FROM Users u '
      . ' WHERE u.username LIKE "%' . $usernames[0] . '%"';
      
    unset($usernames[0]);
    foreach ( $usernames as $u ) {
      $query .= ' OR u.username LIKE "%' . $u . '%"';
    }
    
    return $this->_getResult($this->query($query));
  }
  
  public function getUserInformation($username) {
    $query = 'SELECT u.id, u.username, u.email FROM Users u '
      . ' WHERE u.username = "' . $username . '"';
      
    return $this->_getResult($this->query($query));
  }
}

?>
