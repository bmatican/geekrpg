<?php

class Geek_Dispatcher {

  private $_application;
  private $_method;
  private $_args;
  private $_handlers;
  private $_controllerInstances;
  private $_newmethods;

  public function __construct(&$_application, &$_method, &$_args, &$_handlers, $_newmethods, &$_controllerInstances) {
    $this->_application = $_application;
    $this->_method = $_method;
    $this->_args = $_args;
    $this->_handlers = $_handlers;
    $this->_newmethods = $_newmethods;
    $this->_controllerInstances = $_controllerInstances;
  }

  public function dispatch() {
    try {
      $typeController = Geek::getControllerName($this->_application);

      if (!isset($appController)) {
        Geek::ERROR( '404' );
      } else {
        $appController = $this->_controllerInstances[$typeController];
        $appController->registerHandlers($this->_handlers);
        $appController->registerMethods($this->_newmethods);

        // if POST then set form
        if ("POST" == $_SERVER["REQUEST_METHOD"]) {
          $newPost = Geek::escapeArray( $_POST );
          if( isset( $newPost['__form_name'] ) && isset( $newPost['__argumentsOrder'] ) ){
            $args = explode(',', $newPost['__argumentsOrder']);
            $prefix = $_POST['__form_name'].'/';
            foreach( $args as $k => $v ){
              $this->_args[ $k ] = $newPost[ $prefix.$v ];
            }
          }
        }
        
        Geek::$Template->addHeadContent( '<base href="' . HTTP_ROOT . '" />' );

        $result = call_user_func_array(array($appController, $this->_method), $this->_args);

      }
    } catch (Exception $e) {
      Geek::$LOG->log(Logger::ERROR, "failed to call: $e");
      Geek::ERROR( '500' );
    }
  }
}

?>
