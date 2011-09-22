<?php

class SkeletonHandlers extends Geek_Handlers {
  
  public function __construct() {
    parent::__construct();
    // register all the hooks here
    $this->registerHandler("SkeletonController", "somehook", "test");
  }

  // write functions here
  public function test($context) {
    // does nothing here
    echo get_class() . "111";
  }
}

?>
