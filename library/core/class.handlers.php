<?php

class Geek_Handlers {

  /**
    * Registered handlers
    */
  private $_handlers;
  
  /**
    * Registered methods
    */
  private $_methods;

  /**
    * Each child class should only be instantiated once actually...
    */
  public function __construct() {
    $this->_handlers = array();
    $this->_methods = array();
  }

  /**
    * Creates hook handlers dynamically, which will be run on every provided hook
    * Should only be called once per hook, per controller, per instance of this class. Will only apply the first function attached!
    * @param $controller the controller name 
    * @param $hook the hook to handle
    * @param $function the function to call
    */
  public function registerHandler($controller, $hook, $function) {
    if (isset($this->_handlers[$controller][$hook])) {
      //TODO: do nothing? maybe warn the user ? 
      Geek::$LOG->log(Logger::WARN, "Attached two different handlers for controller $controller with hook $hook. Now have $function, previously had " . $this->_handlers[$controller][$hook][get_class($this)]);
    } else {
      //TODO: replace get_class() with $this maybe??
      $this->_handlers[$controller][$hook][get_class($this)] = $function;
    }
  }

  /**
    * Creates a method dynamically on a controller
    * Should only be called once per controller, per method name. Will only apply the first function attached!
    * @param $controller the controller name
    * @param $methodname the method to create
    */
  public function registerMethod($controller, $methodname) {
    if (method_exists($this, $methodname)) {
      if (isset($this->_methods[$controller][get_class($this)])
         && !in_array($methodname, $this->_methods[$controller][get_class($this)])) {
        $this->_methods[$controller][get_class($this)][] = $methodname;
      } else {
        $this->_methods[$controller][get_class($this)] = array($methodname);
      }
    } else {
      Geek::$LOG->log(Logger::WARN, "Attached method name $methodname without actually having defined it");
    }
  }

  /**
    * @return the hooks setup in this instance
    */
  public function getHandlers() {
    return $this->_handlers;
  }

  public function getMethods() {
    return $this->_methods;
  }
}

?>
