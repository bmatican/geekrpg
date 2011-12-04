<?php

  class GeekTemplate extends GeekView{
    
    private $title  = "";
    private $js     = array();
    private $css    = array();
    private $head   = array();
    
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
    
    public function getHead(){
      return '
  <head>
    <title>'.$this->title.'</title>
    '.$this->printCSS().'
    '.$this->printJS().'
    '.$this->printHeadContent().'
  </head>';
    }
    
    protected function addTop(){
      $this->prepend('
<!DOCTYPE html>
<html lang="en">
  '.$this->getHead().'
  <body>
      ');
    }
    
    protected function addBottom(){
        $this->add('
  </body>
</html>');
    }
    
    public function render( $view = null, $deliveryType = DELIVERY_TYPE_FULL ){
      if( $view ){
        $this->add( $view );
      }
      switch( $deliveryType ){
        case DELIVERY_TYPE_CONTENT:
          parent::render();
          break;
        default:
        case DELIVERY_TYPE_FULL:
          $this->addTop();
          $this->addBottom();
          parent::render();
          break;
          
      }
      exit();
    }
  }
  
?>
