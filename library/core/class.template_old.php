<?php

  class Geek_Template{  
    
    private $title        = "The Geek without a name";
    private $js           = array();
    private $css          = array();
    private $head         = array();
    private $controller   = null;
    private $viewArgs     = array();
    private $defaultPages = array();
    
    public function __construct(){
      $this->defaultPages = array(
        '404' => PATH_VIEWS . '404.php',
        '500' => PATH_VIEWS . '500.php'
      );
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
    
    public function addHeadContent( $content ){
      $this->head[] = $content;
    }
    public function printHeadContent(){
      return implode( "\n", $this->head );
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
    
    public function getHead(){
      return '
  <head>
    <title>'.$this->title.'</title>
    '.$this->printCSS().'
    '.$this->printJS().'
    '.$this->printHeadContent().'
  </head>';
    }
    
    public function getTop(){
      return '
<!DOCTYPE html>
<html lang="en">
  '.$this->getHead().'
  <body>
      ';
    }
    
    public function getBottom(){
        echo '
  </body>
</html>';
    }
    
    public function getPage( $id = 404 ){
      if( isset( $this->defaultPages[ $id ] ) ){
        return $this->defaultPages[ $id ];
      } else {
        return $this->defaultPages[ 500 ];
      }
    }
    
    public function render( $view, $deliveryType = DELIVERY_TYPE_FULL ){
      switch( $deliveryType ){
      
        case DELIVERY_TYPE_CONTENT:
          if( file_exists( $view ) ){
            require_once( $view );
          } else {
            //TODO: WHAT NOW?!
          }
          break;
          
        default:
        case DELIVERY_TYPE_FULL:
          echo $this->getTop();
          if( file_exists( $view ) ){
            require_once( $view );
          } else {
            if( !isset( $this->defaultPages[$view] ) ){
              require_once( $this->getPage( '404' ) );
            } else {
              require_once( $this->getPage( $view ) );
            }
          }
          echo $this->getBottom();
          break;
          
      }
    }
  }
  
?>
