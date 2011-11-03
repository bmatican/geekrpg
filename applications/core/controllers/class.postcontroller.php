<?php
/**
  * This is generally where the license goes :)
  */

class PostController extends Geek_Controller {
  public $postModel;
  public $postCommentModel;
  public $postTagModel;
    
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->postModel = new PostModel();
    $this->postCommentModel = new CommentModel("Posts");
    $this->postTagModel = new TagModel("Posts");
    $this->provideHook("createtables");
  }
  
  // POSTS 
  
  public function index($id = FALSE, $limit = FALSE, $offset = FALSE) {
  	if (FALSE !== $id) {
  		if(is_numeric($id)) {
        $this->posts = $this->postModel->getAllWhere(array("id = $id"), $limit, $offset);
        if (!empty($this->posts)) {
          // should only be one
          $this->posts[0]["comments"] = $this->postCommentModel->getComments($this->posts[0]["id"]);
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
    if( $title === null ){
      
      $this->render('add.php');
    } else {
      if ($state < 0 || $state >= PostModel::POST_MAX_STATE) {
        $this->render("404.php");
      } else {
        $userid = $_SESSION["userid"];
        $dateAdded = time();
        $values = array(
            "userid" => $userid,
            "body" => $body,
            "title" => $title,
            "dateAdded" => $dateAdded,
            "state" => $state,
          );
          
        $this->postModel->insert($values);
      }
      header('Location:'.Geek::path('post/index'));
    }
  }

  /**
    * Removes a post from the DB.
    */
  public function remove($postid = null) {
    //TODO: admin rights??
    $this->postModel->removeById($postid);
    $this->render();
  }
  
  // COMMENTS
  public function comment($postid, $body, $parentid = 0, $state = postCommentModel::COMMENT_OPEN) {
    //TODO: check rights
    $userid = $_SESSION["userid"];
    $userid = 1;
    $values = array(
      "userid" => $userid,
      "postid" => $postid,
      "body" => $body,
      "dateAdded" => time(),
      "parentid" => $parentid,
      "state" => $state,
    );
    $this->postCommentModel->insert($values);
    $this->render();
  }
  
  // TAGS
  
  public function tag($tags = FALSE, $method = "and", $limit = FALSE, $offset = FALSE) {
    if (FALSE === $tags) {
      $this->tags = $this->postTagModel->getAllWhere(array());
      $this->render("index.php");
    } else {
      if (!in_array($method, array("and", "or"))) {
        $this->render("404.php");
      } else {
        $tags = explode(",", $tags);
        $this->problems = $this->postTagModel->getObjectsFor(
          $tags,
          'id',
          $method == "and" ? TRUE : FALSE,
          $limit,
          $offset
        );
        $this->render("index.php");
      }
    }
  }
  
  public function createTag($name = FALSE, $description = FALSE) {
    if (FALSE !== $name && FALSE !== $description) {
      $this->problemTagModel->createTags(array(array(
        "name" => $name,
        "description" => $description,
      )));
    }
    $this->render();
  }
  
  // OTHERS
}

?>

