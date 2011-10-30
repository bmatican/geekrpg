<?php
/**
  * This is generally where the license goes :)
  */

class TagController extends Geek_Controller {
  public $tagModel;

  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    // Register your hooks and create a database model before actually using
    // it to be sure data has where to go :)
    $this->provideHook("createtables");
    // $this->tagModel = new TagModel("Users");
  }
  
  public function test() {
    $this->tagModel = new TagModel("Problem");
    //$tagModel->createTags(array(
    //      array(
    //        "name" => "xxx",
    //        "description" => "xxxx xxxx"
    //      )
    //));
    // $tagModel->deleteTagsFor(3, array("aaa", "java", "crap"));
    // var_export($tagModel->getObjectsFor(array("bash", "xxx"), "password", FALSE, 1, 0));
  }
}

?>

