<?php

class PostModel extends Geek_Model {

  const OPEN = 0;
  const CLOSED = 1;

  public function __construct($tableName) {
    parent::__construct($tableName);
    $this->createTables();
  }

  protected function createTables() {
    //TODO: hardcoded title length
    $createPost = "CREATE TABLE IF NOT EXISTS " 
      . $this->_tableName
      .  " ( "
      . " id INT NOT NULL AUTO_INCREMENT, "
      . " userid INT NOT NULL, "
      . " title VARCHAR(40) NOT NULL UNIQUE, "
      . " body mediumtext NOT NULL, "
      . " dateAdded INT NOT NULL, "
      . " state INT NOT NULL DEFAULT 0,"
      . " PRIMARY KEY(id), "
      . " KEY(userid), "
      . " KEY(dateAdded), "
      . " CONSTRAINT fk_user FOREIGN KEY(userid) REFERENCES " 
      . " Users" . "(id) "
      . " ON UPDATE RESTRICT ON DELETE RESTRICT " 
      . " )";
    mysql_query($createPost) or die(mysql_error());
  }

  public function addPost($userid, $title, $body, $dateAdded, $state = self::OPEN) {
    $query = "INSERT INTO "
      . $this->_tableName . " (userid, title, body, dateAdded, state) "
      . " VALUES " . " ( " 
      . "\"" . mysql_real_escape_string($userid) . "\", " 
      . "\"" .mysql_real_escape_string($title) . "\", "
      . "\"" . mysql_real_escape_string($body) . "\", "
      . "\"" . mysql_real_escape_string($dateAdded) . "\", "
      . "\"" . mysql_real_escape_string($state) . "\""
      . " ) ";

    mysql_query($query) or die(mysql_error());
  }

  /**
    * Removes a post.
    * @param $postidOrTitle either the postid or the title of the desired post
    */
  public function removePost($postidOrTitle) {
    $query = "DELETE post.* FROM "
      . $this->_tableName . " post ";

    if (is_numeric($postidOrTitle)) {
      $query .= "WHERE post.id = \"$postidOrTitle\"";
    } else { 
      $query .= "WHERE post.title = \"" 
        . mysql_real_escape_string($postidOrTitle) . "\"";
    }

    mysql_query($query) or die(mysql_error());
  }
}

?>
