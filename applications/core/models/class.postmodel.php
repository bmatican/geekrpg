<?php

class PostModel extends Geek_Model {

  const POST_OPEN = 0;
  const POST_CLOSED = 1;

  public function __construct($tableName) {
    parent::__construct($tableName);
  }

  /**
   * @override
   */
  protected function _createTables() {
    //TODO: hardcoded title length
    $createPost = "CREATE TABLE IF NOT EXISTS " 
      . $this->tablename
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
  
  /**
   * Add a specific post in the table
   * @param {INT} $userid
   * @param {STRING} $title
   * @param {STRING} $body
   * @param {INT} $dateAdded
   * @param {INT} $state
   */
  public function add($userid, $title, $body, $dateAdded, $state = self::POST_OPEN) {
    $query = "INSERT INTO "
      . $this->tablename . " (userid, title, body, dateAdded, state) "
      . " VALUES " . " ( " 
      . "\"" . mysql_real_escape_string($userid) . "\", " 
      . "\"" . mysql_real_escape_string($title) . "\", "
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
  public function remove($id) {
    $query = "DELETE post.* FROM "
      . $this->tablename . " post ";

    if (is_numeric($id)) {
      $query .= "WHERE post.id = \"$id\"";
    } else { 
      $query .= "WHERE post.title = \"" 
        . mysql_real_escape_string($id) . "\"";
    }

    mysql_query($query) or die(mysql_error());
  }
}

?>
