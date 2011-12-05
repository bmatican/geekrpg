<?php

class CoreHandlers extends Geek_Handlers {
  
  public function __construct() {
    parent::__construct();
    // register all the hooks here
    // $this->registerHandler("PostController", "createtables", "createTables");
    $tagControllers = array("PostController", "ProblemController");
    foreach ($tagControllers as $t) {
      $this->registerMethod($t, "createTag");
      $this->registerMethod($t, "destroyTag");
      $this->registerMethod($t, "viewTag");
      $this->registerMethod($t, "indexTags");
      $this->registerMethod($t, "tags");
    }
    
  }

  public function tags($controller, $name = null) {
    if ($name) {
      $objects = $controller->tagModel->getObjectsFor(array($name));
      $objects = $controller->tagModel->getObjectsWithTags($objects);
      $controller->render('index', array( 'posts' => $objects ) );
    } else {
      $controller->renderError('404');
    }
  }
  
  public function createTag($controller, $name = null, $description = null) {
    $view = Geek::getView( 'blank' );
    if ($name && $description) {
      $result = $controller->tagModel->createTags(array(array(
        "name"        => $name,
        "description" => $description,
      )));
      if( true === $result ){
        $view->add( new Anchor('create another one', array('href' => Geek::path($controller->CONTROLLER_NAME.'/createTag') ) ) );
        $view->add( "<div>The tag <b>$name</b> with the description <b>$description</b> has been created!</div>" );
      } else {
    $view->add( new Anchor('create another one', array('href' => Geek::path($controller->CONTROLLER_NAME.'/createTag') ) ) );
        $view->add( "<div>An error has occured: <span style=\"color:red\">$result</span></div>" );
      }
      $controller->render( $view );
    } else {
      $form = $view->Form( 'createTag', $controller->CONTROLLER_NAME.'/createTag', 'name,description' );
      $form->input( 'name', array( 'placeholder' => 'name' ) );
      $form->input( 'description', array( 'placeholder' => 'description', 'size' => 40 ) );
      $form->submit( 'Create Tag!' );
      $controller->render( $view );
    }
  }
  
  public function destroyTag($controller, $name = null) {
    if ($name) {
      $controller->tagModel->destroyTags(array($name));
      //TODO: a view and a redirect mb??
    } else {
      $controller->renderError( '404' );
    }
  }
  
  public function viewTag($controller, $name = null) {
    if ($name) {
      $tag = $controller->tagModel->getAllWhere(array('name = "' . $name .'"'));
      geek::export($tag);
      //TODO: view goes here...
      $controller->render( 'viewTag', $tag );
    } else {
     $controller->renderError( '404' );
    }
  }
  
  public function indexTags($controller, $limit = null, $offset = null, $orderby = 'name') {
    $tags = $controller->tagModel->getAllWhere(array("id > 0"), $limit, $offset, null, $orderby);
    geek::export($tags);
    
    //TODO: wish I had a view like yours...
    $controller->render('index', $tags);
  }
}

?>
