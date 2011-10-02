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
    $this->tablename = mysql_real_escape_string($tableName);
    $this->_database = Geek_Database::getInstance();
    $this->_createTables();
  }
  
  /**
   * Insert a set of KEY => VALUE pairs into a database table. Defaults to 
   * the underlying table.
   * 
   * @param {ARRAY} $values the KVs to insert
   * @param {STRING} $tablename the alternate table to insert into
   * @return true on success, mysql_error on failure
   */
  public function insert($values, $tablename = FALSE ){
    if(FALSE === $tablename) {
      $tablename = $this->tablename;
    }
    
    $query = 'INSERT INTO ' 
      . $tablename. ' ' 
      . $this->_createSetOfStrings(array_keys($values), FALSE)  
      . ' VALUES '
      . $this->_createSetOfStrings(array_values($values))
      ;

    return mysql_query( $query ) ? true : mysql_error();
  }
  
  /**
   * Runs a query directly against the database returning the raw result
   * @param {STRING} $mysqlQuery the desired query
   * @return the result object or the mysql error
   */
  public function query($mysqlQuery) {
    // no more escaping since we are doing it before any call, in dispatcher
    $result = mysql_query($mysqlQuery);
    if(FALSE === $result) {
      Geek::$LOG->log(Logger::ERROR, "Query error on : " . $mysqlQuery);
      return mysql_error();
    } else {
      return $result;
    }
  }
  
  /**
   * Gets all the elements from the underlying table following the where clause
   * Alternatively, can be made to look in a different table.
   * 
   * @param {ARRAY} $where an array of where clauses
   * @param {STRING} $tablename the alternate table from which to select
   */
  public function getAllWhere($where, $tablename = FALSE) {
    if(FALSE === $tablename) {
      $tablename = $this->tablename;
    }
    
    $query = 'SELECT * from ' . $tablename;
    
    $done = FALSE;
    foreach ($where as $clause) {
      if (!$done) {
        $done = TRUE;
        $query .= ' WHERE ' . $clause;
      } else {
        $query .= ' AND ' . $clause;        
      }
    }

    return $this->_getResult($this->query($query));
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
    * @example ( 'c++', 'java', 'bash' ) from the respective array
    *
    * @param $strings the array of strings we want to use
    */
  protected function _createSetOfStrings($strings, $quoted = TRUE) {
    $set = '';
    foreach ($strings as $string) {
      if (TRUE === $quoted) {
        $set .= ' "' . $string . '", ';
      } else {
        $set .= ' $string, ';
      }
    }

    if ('' !== $set) {
      $set = substr($set, 0, -2);
    }

    $set = ' ( ' . $set . ' ) ';

    return $set;
  }
  
  /**
   * Transform a mysql array into a normal php associative array
   * @param {STRING} $result the result to transform
   * @return {ARRAY} the KV array or the mysql error, in case of bad parameter
   */
  protected function _getResult($result) {
    if (is_string($result)) {
      return $result;
    }
    
  	$objects = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $objects[] = $row;
    }
    mysql_free_result($result);
    return $objects;
  }
}

?>
