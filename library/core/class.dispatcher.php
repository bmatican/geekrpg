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
      
      $appController = $this->_controllerInstances[$typeController];
      if (!isset($appController)) {
        Geek::$Template->render('404');
      } else {
        $appController->registerHandlers($this->_handlers);
        $appController->registerMethods($this->_newmethods);

        // if POST then set form
        if ("POST" == $_SERVER["REQUEST_METHOD"]) {
          $newPost = array();
          foreach ($_POST as $key => $value) {
            // escape all, should have used map, but it sends warnings...
            $newPost[mysql_real_escape_string($key)] = 
              mysql_real_escape_string($value); 
          }
          $appController->setFormValues($newPost);
          
          if( isset( $_POST['__form_name'] ) && isset( $_POST['__argumentsOrder'] ) ){
            $args = explode(',', $_POST['__argumentsOrder']);
            $prefix = $_POST['__form_name'].'/';
            foreach( $args as $k => $v ){
              $this->_args[ $k ] = $newPost[ $prefix.$v ];
            }
          }
        }
        
        Geek::$Template->addHeadContent( '<base href="' . HTTP_ROOT . '" />' );
        /*
        // escape GETs too
        if( "GET" == $_SERVER["REQUEST_METHOD"] ){
          $newGet = array();
          foreach ($_GET as $key => $value) {
            if( $key != 'q' )
              $newGet[mysql_real_escape_string($key)] = mysql_real_escape_string($value); 
          }
          $this->_args = $newGet;
        }
        */
        $result = call_user_func_array(array($appController, $this->_method), $this->_args);

        if (FALSE === $result) {
          Geek::$Template->render('404');
        }
      }
    } catch (Exception $e) {
      Geek::$LOG->log(Logger::ERROR, "failed to call");
    }
  }
}

?>
