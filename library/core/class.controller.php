<?php

/**
  * Stub for the controller classes we will create.
  */
class Geek_Controller {
  /**
    * The application this controller belongs to, designated by name
    */
  public $APPLICATION_NAME;

  /**
    * The handlers that will be registered for specific hooks.
    */
  private $_handlers;

  /**
    * All the individual instances of the handler classes to handle the hooks.
    */
  private $_handlerInstances;

  public function __construct() {
    $this->_handlers = array();
    $this->_handlerInstances = array();
  }

  /**
    * Function to register hooks. This will only be called once, in the 
    * dispatch phase and any further calls will have no effect.
    * @TODO: this is going to be a severe hack for now, until I figure out
    * a beter way to do it...
    */
  public function registerHandlers($allHandlers) {
    if (! $allHandlers) {
      // in case of NULL or problems...
      return;
    }

    if (empty($this->_handlers)) {
      $this->_handlers = $allHandlers;
      foreach ($allHandlers as $hook => $handlers) {
        foreach ($handlers as $classHandler => $function) {
          if (!isset($this->_handlerInstances[$classHandler])) {
            $this->_handlerInstances[$classHandler] = new $classHandler();
          }
        }
      }
    } else {
      Geek::$LOG->log(Logger::DEBUG, "You are not supposed to register handlers manually!");
    }
  }

  /**
    * Provides a hook for future development. Internally, it will loop through
    * all the registered handlers for this hook and call their code 
    * sequentially.
    * @TODO: for future, we could implement a priority system for hooks...
    */
  public function provideHook($hook) {
    $handlers = isset( $this->_handlers[$hook] ) ? $this->_handlers[$hook] : null;
    // no handlers
    if (!$handlers) {
      return;
    }

    foreach ($handlers as $handlerClass => $function) {
      call_user_func_array(array($this->_handlerInstances[$handlerClass], $function), array($this));
    }
  }

  /**
    * Function will automatically include the respective view into the page
    * template for displaying.
    * @param {String} $view  Relative path to the view
    * @param {Array} $arguments  Key => Value pairs or arguments to be added to the view
    */
  public function render($view, $arguments = array()) {
    $viewPath = PATH_APPLICATIONS . DS . $this->APPLICATION_NAME . DS . "views" . DS;
    $filePath = PATH_APPLICATIONS . DS . $this->APPLICATION_NAME . DS . "views" . DS . $view;
    
    Geek::$Template
      ->setController( $this )
      ->setViewArgs( $arguments );
      
    if( !file_exists( $viewPath ) ){
      if( file_exists( $viewPath . '404.php' ) ){
        Geek::$Template->render( $viewPath . '404.php' );
      } else {
        Geek::$Template->render( PATH_VIEW . '404.php' );
      }
    }
    Geek::$Template->render( $filePath );
  }
}

?>
