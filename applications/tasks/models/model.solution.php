<?php

class SolutionModel extends PostModel {

  public function __construct($tablename = null) {
    parent::__construct($tablename);
    $this->_alterTable();
  }
  
  /**
   * Modify the table structure in case it was never run before
   */
  protected function _alterTable() {
  	$query = 'ALTER TABLE ' . $this->tablename
  	 . ' ADD COLUMN problemid INT NOT NULL , '
  	 . ' ADD KEY key_problemid (problemid),'
  	 . ' ADD CONSTRAINT fk_problemid FOREIGN KEY(problemid) '
  	 . ' REFERENCES Problem(id) ON UPDATE CASCADE ON DELETE CASCADE , '
  	 . ' ADD CONSTRAINT uq_solution UNIQUE KEY(problemid, userid) '
  	 ;

  	 //TODO: might cause an error if duplicate...should be ok with it :)
  	 $this->query($query);
  }

  /**
   * Get all solutions for a specific user
   * @param {INT} $userid the userid
   * @return {ARRAY} the solutions
   */
  public function getUserSolutions($userid) {
    return $this->getAllWhere(array('userid = ' . $userid));
  }
  
  /**
   * Get all the solutions for a specific problem.
   * Alternatively, if a userid is passed, it will only get the solutions, if
   * the user has an accepted solution for the problem.
   * 
   * @param {INT} $problemid the problem
   * @param {INT} $userid the restricted user
   * @return {ARRAY} the solutions
   */
  public function getAllSolutions($problemid, $userid = FALSE) {
    $query = ' SELECT * FROM ' . $this->tablename
      . ' WHERE problemid = "' . $problemid . '"';
    
    if (FALSE !== $userid) {
      $query .= ' AND EXISTS ( SELECT * FROM ' . $this->tablename
      . ' WHERE userid = "' . $userid . '"'
      . ' AND state = ' . PostModel::POST_CLOSED
      . ' )';
    }
    return $this->_getResult($this->query($query));
  }
}

?>
