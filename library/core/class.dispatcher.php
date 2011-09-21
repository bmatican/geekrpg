<?php

class Geek_Dispatcher {
  public function __construct() {

  }

  public function Dispatch($application, $method, $args) {
    $pathToTapplication = WEB_ROOT . DIRECTORY_SEPARATOR . "applications" . DIRECTORY_SEPARATOR . $application;

    if (is_dir($pathToTapplication)) {
      requireFolder($pathToTapplication . DIRECTORY_SEPARATOR . "controllers");
      requireFolder($pathToTapplication . DIRECTORY_SEPARATOR . "models");
      requireFolder($pathToTapplication . DIRECTORY_SEPARATOR . "helpers");

      try {
        $typeController = ucfirst($application . "Controller");
        $appController = new $typeController;
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
    }


  }
}

?>
