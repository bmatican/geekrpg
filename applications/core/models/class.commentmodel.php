<?php

class CommentModel extends Geek_Model {

  const OPEN = 0;
  const CLOSED = 1;

  /**
   * The comment table attached to the actual post table
   * @var {STRING}
   */
  public $commentTable;
  
  public function __construct($tableName) {
    $this->commentTable = $tableName . '_comment';
    parent::__construct($tableName);
  }

  /**
   * @override
   */
  protected function _createTables() {
   $createComment = 'CREATE TABLE IF NOT EXISTS ' 
      . $this->commentTable
      .  ' ( '
      . ' id INT NOT NULL AUTO_INCREMENT, '
      . ' postid INT NOT NULL, '
      . ' parentid INT NOT NULL DEFAULT 0, '
      . ' userid INT NOT NULL, '
//      . ' title VARCHAR(40) NOT NULL UNIQUE, '
      . ' body mediumtext NOT NULL, '
      . ' dateAdded INT NOT NULL, '
      . ' state INT NOT NULL DEFAULT 0,'
      . ' PRIMARY KEY(id), '
      . ' KEY(postid), '
      . ' KEY(parentid), '
      . ' KEY(userid), '
      . ' CONSTRAINT fk_post FOREIGN KEY(postid) REFERENCES '
      . $this->tablename . ' (id) '
      . ' ON UPDATE RESTRICT ON DELETE RESTRICT, '
      . ' CONSTRAINT fk_parent FOREIGN KEY(parentid) REFERENCES '
      . $this->commentTable . ' (id)  '
      . ' ON UPDATE RESTRICT ON DELETE RESTRICT, '
      . ' CONSTRAINT fk_user FOREIGN KEY(userid) REFERENCES ' 
      . ' Users' . '(id) '
      . ' ON UPDATE RESTRICT ON DELETE RESTRICT ' 
      . ' )';
   
    $this->query($createComment);
  }
  
  public function getComments($postid, $limit = FALSE, $offset = FALSE) {
    $query = 'SELECT com.* FROM '
      . $this->commentTable . ' com '
      . ' WHERE com.postid = "' . $postid . '"';
      
    if (FALSE !== $limit) {
    	$query .= ' LIMIT ' . $limit;
    	if (FALSE !== $offset) {
    		$query .= ' OFFSET ' . $offset;
    	}
    }

  }
  
}

?>
