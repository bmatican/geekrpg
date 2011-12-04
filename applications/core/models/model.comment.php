<?php

class CommentModel extends Geek_Model {

  const COMMENT_OPEN = 0;
  const COMMENT_CLOSED = 1;
  const COMMENT_MAX_STATE = 2;

  /**
   * The comment table attached to the actual post table
   * @var {STRING}
   */
  public $commentTable;
  
  public function __construct($tableName) {
    $this->commentTable = $tableName . '_comment';
    parent::__construct($this->commentTable);
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
    
    return $this->_arrangeComments($this->_getResult($this->query($query)));
  }
  
    /**
   * Arranges the comments in the natural tree structure of the replies.
   * Assumes they are sorted by id!!!
   * @param $comments the comments to be arranged
   */
  private function _arrangeComments($comments) {
    $tree = array(0 => array(
      "children" => array(),
    ));
    $parents = array();
    foreach ($comments as $comm) {
      $parents[$comm["id"]] = $comm["parentid"];
      $lastid = $comm["id"];
      $chain = array($lastid);
      while(true) {
        $lastid = $parents[$lastid];
        $chain[] = $lastid;
        if ($lastid == 0) {
          break;
        }
      }
      $chain = array_reverse($chain);
      $this->_buildTree($tree, $chain, $comm);
    }
      
    return $tree;
  }

  /**
   * Builds the tree in the required structure.
   *
   * @param $tree the tree we are building, passed by reference on the level
   * @param $parentChain the chain of parents of the current node
   * @param $comment the actual comment to attach as values
   * @return a tree structure of array("value" => $comment, "children" => array(trees))
   */
  private function _buildTree(&$tree, $parentChain, &$comment) {
    $id = $parentChain[0];
    if (count($parentChain) == 1) {
      $tree[$id]["value"] = $comment;
      return ; // finish
    } else {
      if (isset($tree[$id]["children"])) {
        if(!in_array($parentChain[1], array_keys($tree[$id]["children"]))) {
          $tree[$id]["children"][$parentChain[1]] = array();
        }
      } else {
        $tree[$id]["children"] = array();
      }
    }
    
    array_shift($parentChain);
    $this->_buildTree($tree[$id]["children"], $parentChain, $comment);
  }
  
}

?>
