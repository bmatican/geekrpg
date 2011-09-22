<?php

  class Geek_Template{  
    
    protected static $instance  = null;
    
    private $name = "No Name";
    private $js   = array();
    private $css  = array();
    
    private function __construct(){
      
    }
    
    public static function getInstance(){
      if( null == self::$instance ){
        self::$instance = new self();
      }
      return self::$instance;
    }
    
    public function addJS( $path ){
      $js[] = $path;
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
      $css[] = $path;
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
    
    public function render( $view ){
      echo '
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>'.$this->name.'</title>
    '.$this->printCSS().'
    '.$this->printJS().'
  </head>
  
  <body>
    '.$view.'
  </body>
</html>';
    }
    
  }
  
?>
