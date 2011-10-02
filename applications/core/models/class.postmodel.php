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
    $createPost = 'CREATE TABLE IF NOT EXISTS ' 
      . $this->tablename
      .  ' ( '
      . ' id INT NOT NULL AUTO_INCREMENT, '
      . ' userid INT NOT NULL, '
      . ' title VARCHAR(40) NOT NULL, '
      . ' body mediumtext NOT NULL, '
      . ' dateAdded INT NOT NULL, '
      . ' state INT NOT NULL DEFAULT 0,'
      . ' PRIMARY KEY(id), '
      . ' KEY(userid), '
      . ' KEY(dateAdded), '
      . ' CONSTRAINT fk_user FOREIGN KEY(userid) REFERENCES ' 
      . ' Users' . '(id) '
      . ' ON UPDATE RESTRICT ON DELETE RESTRICT ' 
      . ' )';
      
    $this->query($createPost);
  }

  /**
    * Removes a post.
    * @param $postidOrTitle either the postid or the title of the desired post
    */
  public function remove($id) {
    $query = 'DELETE post.* FROM '
      . $this->tablename . ' post '
      . ' WHERE post.id = "' . $id . '"';
    
    return $this->query($query);
  }
}

?>
