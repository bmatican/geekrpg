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
  
  public function index( $limit = 20, $offset = 0 ) {
    $this->posts = $this->postModel->getAllWhere( array("id > 0"), $limit, $offset );
    $this->render();
  }
  
  public function view( $id = null ){
    if( null != $id && is_numeric($id) ){
      $this->post = $this->postModel->getAllWhere( array("id = $id") );
      $this->post = $this->post[0];
      $this->post["comments"] = $this->postCommentModel->getComments($this->post["id"]);
      $this->post["comments"] = $this->post["comments"][0]["children"];
      $this->render( 'view.php' );
    } else {
      $this->render('notFound.php');
    }
  }
  
  public function add($title = null, $body = null, $state = PostModel::POST_OPEN) {
    // TODO: check rights??
    if( $title === null ){
      $this->render('add.php');
    } else {
      if ($state < 0 || $state >= PostModel::POST_MAX_STATE) {
        $this->render("404.php");
      } else {
        
        if( strlen( $title ) < 4 || strlen( $title ) > 42 ){
          $this->_errors['title'] = 'Title must be between 4 and 42 characters long!';
        }
        if( strlen( $body ) < 10 ){
          $this->_errors['body'] = 'You can\'t find at least 10 characters for this textfield?';
        }
        
        if( !empty( $this->_errors ) ){
          $this->render( 'add.php', array( '__errors' => $this->_errors ) );
        } else {
          $userid = $_SESSION["userid"];
          $dateAdded = time();
          $values = array(
            "userid"    => $userid,
            "body"      => $body,
            "title"     => $title,
            "dateAdded" => $dateAdded,
            "state"     => $state,
          );
            
          $this->postModel->insert($values);
          header('Location:'.Geek::path('post/index'));
        }
        
      }
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
  public function comment($postid, $body, $parentid = 0, $state = CommentModel::COMMENT_OPEN) {
    //TODO: check rights
    $userid = $_SESSION['user']['id'];
    $values = array(
      "userid"    => $userid,
      "postid"    => $postid,
      "body"      => $body,
      "dateAdded" => time(),
      "parentid"  => $parentid,
      "state"     => $state,
    );
    $this->postCommentModel->insert($values);
    $this->render( 'post/view/'.$postid );
  }
  
  // TAGS
  
  public function tags($limit = 50, $offset = 0) {
    $this->tags = $this->postTagModel->getAllWhere( array('id>0'), $limit, $offset );
    $this->render('tags.php');
  }
  
  public function tag($tags = null, $method = "and"){
    if (!in_array($method, array("and", "or"))) {
      $this->render("404.php");
    } else {
      $tags = explode(",", $tags);
      $this->posts = $this->postTagModel->getObjectsFor(
        $tags,
        'id',
        $method == "and" ? TRUE : FALSE
      );
      $this->render("index.php");
    }
  }
  
  public function createTag($name = null, $description = null) {
    if (null !== $name && null !== $description) {
      $this->postTagModel->createTags(array(array(
        "name"        => $name,
        "description" => $description,
      )));
    }
    $this->render( 'createTag.php' );
  }
  
  // OTHERS
}

?>

