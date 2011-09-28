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
    // Register your hooks and create a database model before actually using
    // it to be sure data has where to go :)
    $this->postModel = new PostModel("Posts");
    $this->provideHook("createtables");
  }
  
  public function addPost($title, $body, $state = PostModel::OPEN) {
    $userid = $_SESSION["userid"] or 200;
    $dateAdded = time();
    $this->postModel->addPost($userid, $title, $body, $dateAdded, $state);
    // $this->render("");
  }

  /**
    * Removes a post from the DB.
    */
  public function removePost($postidOrTitle) {
    //TODO: admin rights??
    $this->postModel->removePost($postidOrTitle);
    // $this->render("");
  }
}

?>

