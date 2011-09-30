<?php

class CoreHandlers extends Geek_Handlers {
  
  public function __construct() {
    parent::__construct();
    // register all the hooks here
    $this->registerHandler("PostController", "createtables", "createTables");
    // $this->registerMethod("CommentController", "aaaa");
  }

  // write functions here
  public function createTables($context) {
    $model = new PostModel("Post");
  }
}

?>
