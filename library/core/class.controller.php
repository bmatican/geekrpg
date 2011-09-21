<?php

/**
  * Stub for the controller classes we will create.
  */
class Geek_Controller {
  private $_application;
  public function __construct($application) {
    $this->_application = $application;
  }

  /**
    * Function will automatically include the respective view into the page
    * template for displaying.
    */
  public function render($view) {
    $viewPath = PATH_APPLICATIONS . DS . $this->_application . DS . "views";
    include_once $viewPath . DS . $view;
  }
}

?>
