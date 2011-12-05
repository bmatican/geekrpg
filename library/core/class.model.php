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

  public function __construct($tablename = null) {
    $this->_database = Geek_Database::getInstance();
    if (null == $tablename) {
      // plural ftw...
      $this->tablename = substr(get_class($this), 0, - strlen("Model")) . "s";
    } else {
      $this->tablename = mysql_real_escape_string($tablename);
    }
    $this->_createTables();
  }

   /**
   * Updates a set of KEY => VALUE pairs into a database table. Defaults to 
   * the underlying table. Can be passed a where clause array. If not, then 
   * it will look for an "id" => $id in the passed KV array. If none is found
   * it will fail!
   * 
   * @param {ARRAY} $values the KVs to update to
   * @param {STRING} $tablename the alternate table to update into
   * @param {ARRAY} $where a KV array of where clauses
   * @return true on success, false on missing info, mysql_error on failure
   */
  public function update($values, $tablename = null, array $where = array(), $limit = null){
    if (null === $tablename) {
      $tablename = $this->tablename;
    }
    
    $query = 'UPDATE ' . $tablename. ' ' 
      . ' SET ';
    
    foreach ($values as $k => $v) {
      if ($k == 'id') {
        $where['id'] = $v;
        continue;
      }
      
      $query .= ' ' . $k . ' = "' . $v . '", ';
    }
    
    if (', ' == substr($query, -2)) {
      $query = substr($query, 0 , strlen($query) - 2);
    }
    
    if ( count($where) > 0 ) {
      $done = FALSE;
      foreach ($where as $k => $v) {
        if (!$done) {
          $query .= ' WHERE ' . $k . ' = "' . $v . '" ';
          $done = TRUE;
        } else {
          $query .= ' AND ' . $k . ' = "' . $v . '" ';
        }
      }
    }
    
    if (null !== $limit) {
     $query .= ' LIMIT ' . $limit;
    }
    return $this->query( $query );
  }

  public function getInsertId(){
    return mysql_insert_id();
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
      
    return $this->query( $query );
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
   * Get the row of the underlying table with the specified id.
   * Alternatively, can also be used on a different table than the given one.
   */
  public function getById($id, $tablename = FALSE) {
    if(FALSE === $tablename) {
      $tablename = $this->tablename;
    }
    
    $query = 'SELECT * FROM ' . $tablename
      . ' WHERE id = ' . $id;
      
    $result = $this->_getResult($this->query($query));
    
    if (is_string($result) || is_null($result)) {
      return $result;
    } else {
      // got a result array back; should only have 1 elem
      return $result[0];
    }
  }
  
  /**
   * Remove the row of the underlying table with the specified id.
   * Alternatively, can also be used on a different table than the given one.
   * @param {INT} $id the id to be used
   * @param {STRING} $tablename the table to target
   * @return 
   */
  public function removeById($id, $tablename = FALSE) {
    if(FALSE === $tablename) {
      $tablename = $this->tablename;
    }
    
    $query = 'DELETE FROM ' . $tablename
      . ' WHERE id = ' . $id;
      
    return $this->query($query);
  }
  
  /**
   * Gets all the elements from the underlying table following the where clause
   * Alternatively, can be made to look in a different table.
   * 
   * @param {ARRAY} $where an array of where clauses
   * @param {STRING} $tablename the alternate table from which to select
   */
  public function getAllWhere($where, $limit = null, $offset = null, $tablename = null, $orderby = null) {
    if (null === $tablename) {
      $tablename = $this->tablename;
    }
    
    $query = 'SELECT * from ' . $tablename . ' ';
    
    $done = FALSE;
    foreach ($where as $clause) {
      if (!$done) {
        $done = TRUE;
        $query .= ' WHERE ' . $clause;
      } else {
        $query .= ' AND ' . $clause;        
      }
    }
    
    if ($orderby) {
      $query .= ' ORDER BY ' . $orderby . ' asc';
    }
    
    if (null !== $limit) {
      $query .= ' LIMIT ' . $limit;
      if (null !== $offset) {
        $query .= ' OFFSET ' . $offset;
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
        $set .= ' ' . $string . ', ';
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
