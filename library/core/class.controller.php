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
   * The controller name, without the extra added "Controller"
   */
  public $CONTROLLER_NAME;
  
  /**
   * The view this controller should render if none is specified
   * @var unknown_type
   */
  public $VIEW;

  /**
   * 
   */
  private $_formValues;
  
  /**
    * The handlers that will be registered for specific hooks.
    */
  private $_handlers;

  /**
    * The new methods that will be registered to controllers
    */
  private $_newmethods;

  /**
    * All the individual instances of the handler classes to handle the hooks.
    */
  private $_handlerInstances;

  public function __construct() {
    $this->_handlers = array();
    $this->_newmethods = array();
    $this->_handlerInstances = array();
    $classname = get_class($this);
    $this->CONTROLLER_NAME = strtolower(substr($classname, 0, strlen($classname) - strlen("Controller")));
    $this->VIEW = "index.php";
  }

  /**
    * Function to register new methods. This will only be called once, in the 
    * dispatch phase and any further calls will have no effect.
    * @TODO: this is going to be a severe hack for now, until I figure out
    * a beter way to do it...
    */
  public function registerMethods($newMethods) {
    if (! $newMethods) {
      // in case of NULL or problems...
      return;
    }

    if (empty($this->_newmethods)) {
      foreach ($newMethods as $handlerClass => $methods ) {
        foreach ($methods as $method) {
          if (isset($this->_newmethods[$method])) {
            Geek::$LOG->log(Logger::WARN, "Previously had a method defined from " 
                . $this->_newmethods[$method] 
                . " ignoring the one from " . $handlerClass);
          } else {
            $this->_newmethods[$method] = $handlerClass;
            if (!isset($this->_handlerInstances[$handlerClass])) {
              $this->_handlerInstances[$handlerClass] = new $handlerClass();
            }
          }
        }
      }
    } else {
      Geek::$LOG->log(Logger::DEBUG, "You are not supposed to register handlers manually!");
    }
  }

  /**
    * Function to register handlers. This will only be called once, in the 
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
  public function render($view = FALSE, $arguments = array()) {
  	if(FALSE === $view) {
  		$view = $this->VIEW;
  	}
  	
    $viewPath = PATH_APPLICATIONS . $this->APPLICATION_NAME . DS . "views" . DS . $this->CONTROLLER_NAME . DS;
    $filePath = $viewPath . $view;
    
    if( isset($_POST) ){
      $arguments['__post'] = $_POST;
    }
    if( isset($_GET) ){
      $arguments['__get'] = $_GET;
    }
    
    Geek::$Template
      ->setController( $this )
      ->setViewArgs( $arguments );

    Geek::$Template->render( $filePath );
  }
  
  /**
   * Try to see if there is a registered method to handle the attempted call.
   * If there is, it will call that method, on the assigned handler.
   * Otherwise, it will still try to execute the _undefinedMethod() function
   * that can be overloaded in subclasses.
   */
  public function __call($method, $args) {
    if (isset($this->_newmethods[$method])) {
      call_user_func_array(
        array(
          $this->_handlerInstances[$this->_newmethods[$method]], 
          $method), 
        array($this)
        );
    } else {
      $this->_undefinedMethod($method, $args);
    }
  }

  /**
   * Gets called in case there is no method currently in place to handle the
   * request received.
   *
   * By default, will try to render the 404 page of this controller.
   */
  protected function _undefinedMethod($method, $args) {
    $this->render("404.php", array_merge(array($method), $args));
  }
  
  /**
   * Check if the method initially called was done via a POST
   * @return true if yes, false otherwise
   */
  public function isPost() {
  	return isset($this->_formValues);
  }
  
  /**
   * @returns {array}  The form values as an associative array
   */
  public function getFormValues(){
    return $this->_formValues;
  }
  
  /**
   * Sets all form values
   * @param {array} $values
   */
  public function setFormValues( $values ){
    $this->_formValues = array_map( "Geek::escape", $values );
  }
  
  /**
   * Set a specific form value
   * @param {string} $key
   * @param {mixed} $value
   */
  public function setFormValue( $key, $value ){
    $this->_formValues[ $key ] = $value;
  }
  
}

?>
