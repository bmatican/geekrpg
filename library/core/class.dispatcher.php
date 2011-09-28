<?php

class Geek_Dispatcher {

  private $_application;
  private $_method;
  private $_args;
  private $_handlers;
  private $_controllerInstances;

  public function __construct(&$_application, &$_method, &$_args, &$_handlers, &$_controllerInstances) {
    $this->_application = $_application;
    $this->_method = $_method;
    $this->_args = $_args;
    $this->_handlers = $_handlers;
    $this->_controllerInstances = $_controllerInstances;
  }

  public function dispatch() {
    try {
      $typeController = Geek::getControllerName($this->_application);
      // $str = "\$appController = new $typeController('$this->_application');";
      // eval($str);
      $appController = $this->_controllerInstances[$typeController];
      $appController->registerHandlers($this->_handlers);
      $result = call_user_func_array(array($appController, $this->_method), $this->_args);

      if (FALSE === $result) {
        $errors["_caller"][] = Error::callerFailure($this->_method);
        jsonOutput($errors);
      }
    } catch (Exception $e) {
      Geek::$LOG->log(Logger::ERROR, "failed to call");
      jsonOutput(array("_exception" => $e));
    }
  }
}

?>
