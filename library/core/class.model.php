<?php
/*
 * Stub for now for the models to use for our data.
 */
class Geek_Model {

	/**
	 * Database object
	 * @var {DatabaseCon}
	 */
  protected $_database;

  public $tablename;

  public function __construct($tableName) {
    $this->tablename = $tableName;
    $this->_database = Geek_Database::getInstance();
    $this->_createTables();
  }
  
  public function insert( $values ){
    
    foreach( $values as $k => $v ){
      $arr[] = "'$v'";
    }
    
    $q = "INSERT INTO ".$this->_tableName." (".implode(", ", array_keys($values)).") VALUES (".implode(', ', $values).")";
    return mysql_query( $q ) ? true : mysql_error();
    
  }
  
  /**
   * Gets all the elements from the underlying table following the where clause
   * @param {ARRAY} $where an array of where clauses
   */
  public function getAllWhere($where) {
    $query = "SELECT * from " . $this->tablename;
    
    $done = FALSE;
    foreach ($where as $clause) {
      if (!$done) {
        $done = TRUE;
        $query .= " WHERE " . $clause;
      } else {
        $query .= " AND " . $clause;        
      }
    }
    
    return $this->_getQuery($query);
  }
  
  /**
   * Create the table(s) required by this model if not previously created
   * 
   * IS ALWAYS CALLED IN PARENT CONSTRUCTOR
   */
  protected function _createTables() {
    // to be used lower down the inheritance chain to create the table(s)
  }

  /**
   * Alters the table structure according to requirements
   * 
   * IS NEVER EXPLICITLY CALLED IN THE BASE MODEL CLASS
   */
  protected function _alterTables() {
  	// to be used lower down the inheritance chain to create the table(s)
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
  
  protected  function _getQuery($query) {
  	$objects = array();
    $result = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $objects[] = $row;
    }
    mysql_free_result($result);
    return $objects;
  }
}

?>
