<?php

class Geek_Handlers {

  private $_hooks;

  /**
    * Each child class should only be instantiated once actually...
    */
  public function __construct() {
    $this->_hooks = array();
  }

  /**
    * Should only be called once per hook, per controller, per instance of this class. Will only apply the last function attached!
    * @param $controller the controller name 
    * @param $hook the hook to handle
    * @param $function the function to call
    */
  public function registerHandler($controller, $hook, $function) {
    if (isset($this->_hooks[$controller][$hook])) {
      //TODO: do nothing? maybe warn the user ? 
      Geek::$LOG->log(Logger::WARN, "Attached two different handlers for controller $controller with hook $hook. Now have $function, previously had " . $this->_hooks[$controller][$hook][get_class($this)]);
    } else {
      //TODO: replace get_class() with $this maybe??
      $this->_hooks[$controller][$hook][get_class($this)] = $function;
    }
  }

  /**
    * @return the hooks setup in this instance
    */
  public function getHandlers() {
    return $this->_hooks;
  }
}

?>
