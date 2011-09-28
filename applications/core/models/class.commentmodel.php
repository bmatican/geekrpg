<?php

class CommentModel extends Geek_Model {

  const OPEN = 0;
  const CLOSED = 1;

  protected $_commentTable;
  public function __construct($tableName) {
    parent::__construct($tableName);
    $this->_commentTable = $this->_tableName . "_comment";
    $this->createTables();
  }

  protected function createTables() {
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
      . $this->_tableName . " (id) "
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
}

?>
