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

  public $VIEWS_NAME = 'views';
  
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
    $this->VIEW = "Index";
  }

  public function getViewInstance($view, $viewArgs = array()) {
    $path = $this->APPLICATION_NAME. DS . $this->VIEWS_NAME. DS . $this->CONTROLLER_NAME;
    return Geek::getView($view, $path, $viewArgs);
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

  public function getErrorView( $view, array $viewArgs = array() ){
    $view = $this->getViewInstance( $view, $viewArgs );
    if( !$view ){
      $view = Geek::getErrorView( $view, $viewArgs );
    }
    return $view;
  }
  
  /**
   * Enforces the permission name. It will render a special permission page
   * and kill the application 
   */
  public function setPermission($name) {
    if (!Geek::checkPermission($name)) {
      $this->render('Permission');
      exit();
    }
  }

  /**
    * Function will automatically include the respective view into the page
    * template for displaying.
    * @param {String} $view  Relative path to the view
    * @param {Array} $arguments  Key => Value pairs or arguments to be added to the view
    */
  public function render($view = null, $viewArgs = array()) {
  	if(!$view) {
  		$view = $this->VIEW;
  	}

    if( isset($_POST) ){
      $viewArgs['__post'] = $_POST;
    }
    if( isset($_GET) ){
      $viewArgs['__get'] = $_GET;
    }

    if( !($view instanceof GeekView) ){
      $view = $this->getViewInstance($view, $viewArgs);
      $view = $view ? $view : $this->getErrorView( '404', $viewArgs );
    }
    Geek::$Template->render( $view );
  }
  
  /**
   * Try to see if there is a registered method to handle the attempted call.
   * If there is, it will call that method, on the assigned handler.
   * Otherwise, it will still try to execute the _undefinedMethod() function
   * that can be overloaded in subclasses.
   */
  public function __call($method, $args) {
    if (isset($this->_newmethods[$method])) {
      // small hack to make this go smoother :)
      $c = $this->_handlerInstances[$this->_newmethods[$method]];
      $m = $method;
      $a = array($this);
      switch(count($a)) { 
        case 0: $res = $c->{$m}(); break; 
        case 1: $res = $c->{$m}($a[0]); break; 
        case 2: $res = $c->{$m}($a[0], $a[1]); break; 
        case 3: $res = $c->{$m}($a[0], $a[1], $a[2]); break; 
        case 4: $res = $c->{$m}($a[0], $a[1], $a[2], $a[3]); break; 
        case 5: $res = $c->{$m}($a[0], $a[1], $a[2], $a[3], $a[4]); break; 
        default: $res = call_user_func_array(array($c, $m), $a);  break; 
      } 
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
    $this->render( '404', $args );
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
   * Set a specific form value
   * @param {string} $key
   * @param {mixed} $value
   */
  public function setFormValue( $key, $value ){
    $this->_formValues[ $key ] = $value;
  }
  
}

?>
