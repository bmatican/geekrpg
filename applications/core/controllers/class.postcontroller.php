<?php
/**
  * This is generally where the license goes :)
  */

class PostController extends Geek_Controller {
  public $postModel;
  
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->postModel = new PostModel("Posts");
    $this->provideHook("createtables");
  }
  
  public function index() {
    render();
  }
  
  public function add($title, $body, $state = PostModel::POST_OPEN) {
    // TODO: check rights??
    if ($state < 0 || $state >= POST_MAX_STATE) {
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
  public function remove($postid) {
    //TODO: admin rights??
    $this->postModel->remove($postid);
    $this->render();
  }
}

?>

