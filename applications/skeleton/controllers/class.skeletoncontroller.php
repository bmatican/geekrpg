<?php
/**
  * This is generally where the license goes :)
  */

class SkeletonController extends Geek_Controller {
  /**
    * Default constructor.
    */
  public function __construct($application) {
    parent::__construct($application);
  }

  public function test() {
    $this->render("sample.php");
  }
}

?>

