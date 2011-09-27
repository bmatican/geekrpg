<?php

class TagsHandlers extends Geek_Handlers {
  
  public function __construct() {
    parent::__construct();
    // register all the hooks here
    $this->registerHandler("TagsController", "createtables", "createTables");
  }

  // write functions here
  public function createTables($context) {
    $TagsModel = new TagsModel("Users");
    $TagsModel->createTables();
  }
}

?>
