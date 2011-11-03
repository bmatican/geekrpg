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
      . ' PRIMARY KEY(id), '
      . ' UNIQUE KEY(name) '
      . ' )';
   
    $this->query($createUsers);
  }
  
}
  
?>
