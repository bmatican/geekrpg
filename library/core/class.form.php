<?php

class Form{
  
  private $action;
  private $argumentsOrder;
  
  public function __construct( $action = '', $argumentsOrder = array() ){
    $this->action         = $action;
    $this->argumentsOrder  = $argumentsOrder;
  }
  
  public function open( $attributes = null ){
    
    $defaults = array(
      'method'  => 'post'
    );
    
    $attributes = $attributes ? $attributes : array();
    foreach( $defaults as $k => $v ){
      $attributes[ $k ] = isset( $attributes[ $k ] ) ? $attributes[ $k ] : $v;
    }
    
    
    $attr = array();
    foreach( $attributes as $k => $v ){
      $attr[] = $k.'="'.$v.'"';
    }
    $attr = implode( ' ', $attr );
    
    echo '<form action="'.( HTTP_ROOT . $this->action ).'" '.$attr.'>'."\n";
    echo '<input type="hidden" name="_argumentsOrder" value="'.implode( ',', $this->argumentsOrder ).'" />'."\n";
    
  }
  
  public function close(){
    echo '</form>'."\n";
  }
  
}

?>
