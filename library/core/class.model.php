<?php
/*
 * Stub for now for the models to use for our data.
 */
class Geek_Model {
  protected $_database;

  protected $_tableName;

  public function __construct($tableName) {
    $this->_tableName = $tableName;
    $this->_database = Geek_Database::getInstance();
  }
}

?>
