<?php

class Geek_Dispatcher {

  private $_application;
  private $_method;
  private $_args;
  private $_handlers;
  private $_newmethods;
  private $_controllerInstances;

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
      $appController->registerHandlers($this->_handlers);
      $appController->registerMethods($this->_newmethods);
      
      // if POST then set form
      if ("POST" == $_SERVER["REQUEST_METHOD"]) {
        $newPost = array();
        foreach ($_POST as $key => $value) {
          // escape all
          $newPost[mysql_real_escape_string($key)] = 
            mysql_real_escape_string($value); 
        }
        $appController->setFormValues($newPost);
      }
      
      // escape GETs too
      $this->_args = array_map(mysql_real_escape_string, $this->_args);
      
      $result = call_user_func_array(array($appController, $this->_method), $this->_args);

      if (FALSE === $result) {
        $errors["_caller"][] = Error::callerFailure($this->_method);
        Geek::jsonOutput($errors);
      }
    } catch (Exception $e) {
      Geek::$LOG->log(Logger::ERROR, "failed to call");
      Geek::jsonOutput(array("_exception" => $e));
    }
  }
}

?>
