<?php

  class GeekView{
    
    public $args;
    protected $queue = array();
    
    public function __construct( $viewArgs = array() ){
      $this->args = $viewArgs;
    }

    public function prepend( $object ){
      array_unshift( $this->queue, $object );
    }
    
    public function add( $object ){
      $this->queue[ count($this->queue) ] = $object;
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

    /**
     * Form Factory
     */
    public function Form( $name, $action = '' ){
      $form = new Form( $name, $action );
      $this->add( $form );
      return $form;
    }

    public function toString(){
      $h = '';
      foreach( $this->queue as $k => $v ){
        if( $v instanceof GeekView ){
          $h .= $v->toString();
        } else {
          $h .= $v;
        }
      }
      return $h;
    }
    
    public function render(){
      echo $this->toString();
    }

  }
  
?>
