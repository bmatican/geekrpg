<?php

  class View{
    
    private $controller;
    private $viewArgs;
    
    public function __construct(){
      
    }
    
    public function setController( $val ){
      $this->controller = $val;
      return $this;
    }
    public function getController(){
      return $this;
    }
    
    public function setViewArgs( array $val ){
      $this->viewArgs = $val;
      return $this;
    }
    public function getViewArgs(){
      return $this->viewArgs;
    }
    
  }
  
?>
