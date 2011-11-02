<?php
/**
  * This is generally where the license goes :)
  */

class PostController extends Geek_Controller {
  public $postModel;
  public $commentModel;
    
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->postModel = new PostModel("Posts");
    $this->commentModel = new CommentModel("Posts");
    $this->provideHook("createtables");
  }
  
  // POSTS 
  
  public function index($id = FALSE, $limit = FALSE, $offset = FALSE) {
  	if (FALSE !== $id) {
  		if(is_numeric($id)) {
        $this->posts = $this->postModel->getAllWhere(array("id = $id"), $limit, $offset);
        if (!empty($this->posts)) {
          // should only be one
          $this->posts[0]["comments"] = $this->commentModel->getComments($this->posts[0]["id"]);
        }
  	  } else {
        $this->render("404.php");
      }
    } else {
      $this->posts = $this->postModel->getAllWhere(array("id > 0"));
    }
    $this->render();
  }
  
  public function add($title = null, $body = null, $state = PostModel::POST_OPEN) {
    // TODO: check rights??
    if ($state < 0 || $state >= PostModel::POST_MAX_STATE) {
      $this->render("404.php");
    } else {
      //TODO: fix $_SESSION
      // $userid = $_SESSION["userid"];
      $userid = 1;
      $dateAdded = time();
      $values = array(
          "userid" => $userid,
          "body" => $body,
          "title" => $title,
          "dateAdded" => $dateAdded,
          "state" => $state,
        );
        
      $this->postModel->insert($values);
      $this->render();
    }
  }

  /**
    * Removes a post from the DB.
    */
  public function remove($postid = null) {
    //TODO: admin rights??
    var_dump( $postid );
    $this->postModel->removeById($postid);
    $this->render();
  }
  
  // COMMENTS
  public function comment($postid, $body, $parentid = 0, $state = commentModel::COMMENT_OPEN) {
    //TODO: check rights
    //TODO: fix $_SESSION
    // $userid = $_SESSION["userid"];
    $userid = 1;
    $values = array(
      "userid" => $userid,
      "postid" => $postid,
      "body" => $body,
      "dateAdded" => time(),
      "parentid" => $parentid,
      "state" => $state,
    );
    $this->commentModel->insert($values);
    $this->render();
  }
}

?>

