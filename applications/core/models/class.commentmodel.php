<?php

class CommentModel extends Geek_Model {

  const OPEN = 0;
  const CLOSED = 1;

  /**
   * The comment table attached to the actual post table
   * @var {STRING}
   */
  protected $_commentTable;
  
  public function __construct($tableName) {
    $this->_commentTable = $tableName . "_comment";
    parent::__construct($tableName);
  }

  /**
   * @override
   */
  protected function _createTables() {
   $createComment = "CREATE TABLE IF NOT EXISTS " 
      . $this->_commentTable
      .  " ( "
      . " id INT NOT NULL AUTO_INCREMENT, "
      . " postid INT NOT NULL, "
      . " parentid INT NOT NULL DEFAULT 0, "
      . " userid INT NOT NULL, "
//      . " title VARCHAR(40) NOT NULL UNIQUE, "
      . " body mediumtext NOT NULL, "
      . " dateAdded INT NOT NULL, "
      . " state INT NOT NULL DEFAULT 0,"
      . " PRIMARY KEY(id), "
      . " KEY(postid), "
      . " KEY(parentid), "
      . " KEY(userid), "
      . " CONSTRAINT fk_post FOREIGN KEY(postid) REFERENCES "
      . $this->tablename . " (id) "
      . " ON UPDATE RESTRICT ON DELETE RESTRICT, "
      . " CONSTRAINT fk_parent FOREIGN KEY(parentid) REFERENCES "
      . $this->_commentTable . " (id)  "
      . " ON UPDATE RESTRICT ON DELETE RESTRICT, "
      . " CONSTRAINT fk_user FOREIGN KEY(userid) REFERENCES " 
      . " Users" . "(id) "
      . " ON UPDATE RESTRICT ON DELETE RESTRICT " 
      . " )";
   mysql_query($createComment) or die(mysql_error());
  }

  /**
   * Add a comment, linked to a specific post, with response either to either a specific comment
   * or directly in response to the post itself
   * 
   * @param {INT} $postid
   * @param {INT} $userid
   * @param {STRING} $body
   * @param {INT} $dateAdded
   * @param {INT} $parentid the comment to which you are replying or 0 for linking to the post itself
   * @param {INT} $state
   */
  public function addComment($postid, $userid, $body, $dateAdded, $parentid = 0, $state = CommentModel::OPEN) {
    $query = "INSERT INTO "
      . $this->_commentTable
      . " (postid, parentid, userid, body, dateAdded, state) "
      . " VALUES " . " ( "
      . "\"" . mysql_real_escape_string($postid) . "\","
      . "\"" . mysql_real_escape_string($parentid) . "\","
      . "\"" . mysql_real_escape_string($userid) . "\","
      . "\"" . mysql_real_escape_string($body) . "\","
      . "\"" . mysql_real_escape_string($dateAdded) . "\","
      . "\"" . mysql_real_escape_string($state) . "\""
      . " ) ";

    mysql_query($query) or die(mysql_error());
  }
  
  public function getComments($postid, $limit = FALSE, $offset = FALSE) {
    $query = "SELECT com.* FROM "
      . $this->_commentTable . " com "
      . " WHERE com.postid = " . mysql_real_escape_string($postid);
      
    if (FALSE !== $limit) {
    	$query .= " LIMIT " . mysql_real_escape_string($limit);
    	if (FALSE !== $offset) {
    		$query .= " OFFSET " . mysql_real_escape_string($offset);
    	}
    }
      
    $comments = array();
    $result = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $comments[] = $row;
    }
    mysql_free_result($result);
    return $comments;
  }
  
}

?>
