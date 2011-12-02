<?php

  class GeekView{
    
    private $name;
    private $queue  = array();
    
    public function __construct( $name ){
      $this->name = $name;
    }
    
    public function add( $text ){
      $this->queue[ count($this->queue) ] = $text;
    }
    
    public function addJS( $path ){
      Geek::$Template->addJs( $path );
    }
    public function addCSS( $path ){
      Geek::$Template->addCss( $path );
    }
    public function addHeadContent( $content ){
      Geek::$Template->addHeadContent( $content );
    }
    
    public function render(){
      foreach( $this->queue as $k => $v ){
        if( $v instanceof GeekView ){
          $v->render();
        } else {
          echo $v;
        }
      }
    }
    
  }
  
?>
