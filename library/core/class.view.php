<?php

  class GeekView{
    
    public $args;
    protected $queue = array();
    
    public function __construct( array $args = array() ){
      $this->args = $args;
    }

    public function prepend( $object ){
      array_unshift( $this->queue, $object );
    }
    
    public function add( $object, $name = null ){
      if( $name === null ){
        $this->queue[] = $object;
      } else {
        $this->queue[ $name ] = $object;
      }
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
    public function Form( $name, $action = '', $argsOrder = '', array $attributes = array() ){
      $form = new Form( $name, $action, $argsOrder, $attributes );
      $this->add( $form, "form/$name" );
      return $form;
    }

    public function get( $name ){
      if( isset( $this->queue[$name] ) ){
        return $this->queue[$name];
      } else {
        return null;
      }
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
