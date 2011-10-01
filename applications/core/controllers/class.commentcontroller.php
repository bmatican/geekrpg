<?php
/**
  * This is generally where the license goes :)
  */

class CommentController extends Geek_Controller {
  public $commentModel;

  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    // Register your hooks and create a database model before actually using
    // it to be sure data has where to go :)
    // $this->commentModel = new CommentModel("Posts");
    // $this->provideHook("createtables");
  }
  
  public function addComment($postid, $body, $parentid = 0, $state = commentModel::OPEN) {
    //$this->commentModel->addComment($postid, $_SESSION["userid"], $body, time(), $parentid, $state);
  }
}

?>

