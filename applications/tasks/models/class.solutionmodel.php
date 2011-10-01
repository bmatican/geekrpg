<?php

class SolutionModel extends PostModel {

  public function __construct() {
    parent::__construct("Solution");
    $this->_alterTable();
  }
  
  protected function _alterTable() {
  	$query = "ALTER TABLE " . $this->tablename
  	 . " ADD COLUMN problemid INT NOT NULL , "
  	 . " ADD CONSTRAINT fk_problemid FOREIGN KEY(problemid) "
  	 . " REFERENCES Problem(id) ON UPDATE CASCADE ON DELETE CASCADE , "
  	 . " ADD CONSTRAINT uq_solution UNIQUE KEY(problemid, userid) "
  	 ;

  	 //TODO: might cause an error if duplicate...should be ok with it :)
  	 mysql_query($query);
  }
}

?>