<?php

class CoreHandlers extends Geek_Handlers {
  
  public function __construct() {
    parent::__construct();
    // register all the hooks here
    // $this->registerHandler("PostController", "createtables", "createTables");
    $tagControllers = array("PostController", "ProblemController");
    foreach ($tagControllers as $t) {
      $this->registerMethod($t, "createTag");
      $this->registerMethod($t, "viewTag");
      $this->registerMethod($t, "indexTag");
    }
    
  }
  
  public function createTag($controller, $name = null, $description = null) {
    if ($name && $description) {
      $controller->tagModel->createTags(array(array(
        "name"        => $name,
        "description" => $description,
      )));
      $controller->render( 'createTag' );
    } else {
      $controller->renderError('404', array($controller, $name, $description));
    }
  }
  
  public function destroyTag($controller, $name = null) {
    if ($name) {
      
    } else {
    
    }
  }
  
  public function viewTag($controller, $name = null) {
    if ($name) {
      $tag = $controller->getAllWhere(array('name = '));
    } else {
     $controller->indexTags(); 
    }
  }
  
  public function indexTags($controller, $limit = 50, $offset = 0) {
    $tags = $controller->tagModel->getAllWhere(array("id > 0"), $limit, $offset);
    $controller->render('index', $tags);
  }
}

?>
