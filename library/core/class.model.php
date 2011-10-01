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
  
  public function insert( $values ){
    
    foreach( $values as $k => $v ){
      $arr[] = "'$v'";
    }
    
    $q = "INSERT INTO ".$this->_tableName." (".implode(", ", array_keys($values)).") VALUES (".implode(', ', $values).")";
    return mysql_query( $q ) ? true : mysql_error();
    
  }
  
  /**
    * Creates a set of strings to be used with IN
    *
    * @example ( "c++", "java", "bash" ) from the respective array
    *
    * @param $strings the array of strings we want to use
    */
  protected function _createSetOfStrings($strings) {
    $set = "";
    foreach ($strings as $string) {
      $string = mysql_real_escape_string($string);
      $set .= " \"$string\", ";
    }

    if ("" !== $set) {
      $set = substr($set, 0, -2);
    }

    $set = " ( " . $set . " ) ";

    return $set;
  }
}

?>
