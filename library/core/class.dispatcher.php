<?php

class Geek_Dispatcher {
  public function __construct() {

  }

  public function dispatch($application, $method, $args) {
    $pathToTapplication = WEB_ROOT . DS . "applications" . DS . $application;

    if (is_dir($pathToTapplication)) {
      requireFolder($pathToTapplication . DS . "controllers");
      requireFolder($pathToTapplication . DS . "models");
      requireFolder($pathToTapplication . DS . "helpers");

      try {
        $typeController = ucfirst($application . "Controller");
        $str = "\$appController = new $typeController('$application');";
        // $str = '$appController = new RegistrationController("registration");';
        eval($str);
        $result = call_user_func_array(array($appController, $method), $args);
      } catch (Exception $e) {
        $LOG->log(Logger::ERROR, "failed to call");
        jsonOutput(array("_exception" => $e));
      }

      if (FALSE === $result) {
        $errors["_caller"][] = Error::callerFailure($function);
        jsonOutput($errors);
      }

    } else {
      //TODO: problem...
      $LOG->log(Logger::FATAL, "problem...");
    }


  }
}

?>
