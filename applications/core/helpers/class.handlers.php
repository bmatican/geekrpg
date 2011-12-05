<?php

class CoreHandlers extends Geek_Handlers {
  
  public function __construct() {
    parent::__construct();
    // register all the hooks here
    // $this->registerHandler("PostController", "createtables", "createTables");
    // $this->registerMethod("PostController", "aaaa");
    
  }
  /*
  public function aaaa(&$controller, $x, $y, $z) {
    geek::export(array($x, $y, $z));
  }
  
  public function createTables(&$controller) {
    geek::export($controller);
  }
  */
}

?>
