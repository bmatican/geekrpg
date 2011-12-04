<?php

class RoleModel extends Geek_Model {
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
      . ' name VARCHAR(255) NOT NULL, '
      . ' p_admin INT NOT NULL DEFAULT 0, '
      . ' p_loggedin INT NOT NULL DEFAULT 1, '
      . ' PRIMARY KEY(id), '
      . ' UNIQUE KEY(name) '
      . ' )';
   
    $this->query($createUsers);
  }
  
  public function getRole($roleid) {
    $query = ' SELECT r.* from Roles r'
      . ' WHERE r.id = "' . $roleid . '"';
  
    return $this->_getResult($this->query($query));
  }
  
}
  
?>
