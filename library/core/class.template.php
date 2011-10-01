<?php

  class Geek_Template{  
    
    private $title      = "The Geek without a name";
    private $js         = array();
    private $css        = array();
    private $controller = null;
    private $viewArgs   = array();
    
    public function __construct(){
      
    }
    
    public function addJS( $path ){
      if( is_array( $path ) ){
        foreach( $path as $v ){
          $this->addJS( $v );
        }
      } else
        $this->js[] = $path;
    }
    
    public function printJS( $path = null ){
      if( !$path ){
        $output = array();
        foreach( $this->js as $k => $v ){
          $output[] = $this->printJS( $v );
        }
        return implode("\n\t", $output);
      } else 
        return '<script type="text/javascript" src="'.$path.'"></script>';
    }
    
    public function addCSS( $path ){
      if( is_array( $path ) ){
        foreach( $path as $v ){
          $this->addCSS( $v );
        }
      } else
        $this->css[] = $path;
    }
    
    public function printCSS( $path = null ){
      if( !$path ){
        $output = array();
        foreach( $this->css as $k => $v ){
          $output[] = $this->printCSS( $v );
        }
        return implode("\n\t", $output);
      } else 
        return '<link rel="stylesheet" type="text/css" href="'.$path.'" />';
    }
    
    public function setTitle( $value ){
      $this->title = $value;
      return $this;
    }
    public function getTitle(){
      return $this->title;
    }
    
    public function setController( $controller ){
      $this->controller = $controller;
      return $this;
    }
    public function getController(){
      return $this->controller;
    }
    
    public function setViewArgs( $args ){
      $this->viewArgs = $args;
      return $this;
    }
    public function getViewArgs(){
      return $this->viewArgs;
    }
    
    public function getTop(){
      return '
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>'.$this->name.'</title>
    '.$this->printCSS().'
    '.$this->printJS().'
  </head>
  
  <body>
      ';
    }
    
    public function getBottom(){
        echo '
  </body>
</html>';
    }
    
    public function render( $view, $deliveryType = DELIVERY_TYPE_FULL ){
      switch( $deliveryType ){
      case DELIVERY_TYPE_CONTENT:
        require_once( $view );
        break;
      default:
      case DELIVERY_TYPE_FULL:
        echo $this->getTop();
        require_once( $view );
        echo $this->getBottom();
        break;
      }
    }
  }
  
?>
