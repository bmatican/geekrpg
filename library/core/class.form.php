<?php

class Form{
  
  private $name;
  private $action;
  private $inputs = array();
  private $values = array();
  
  public function __construct( $name, $action ){
    $this->name   = $name;
    $this->action = $action;
  }
  
  public function open( array $attributes = array() ){
    
    $defaults = array(
      'method'  => 'post'
    );
    
    Geek::setDefaults( $attributes, array('method'=>'post') );
    
    $attr = array();
    foreach( $attributes as $k => $v ){
      $attr[] = $k.'="'.$v.'"';
    }
    $attr = implode( ' ', $attr );
    
    echo '<form action="'.Geek::path($this->action).'" '.$attr.'>'."\n";
  }
  
  public function close(){
    echo '</form>'."\n";
  }
  
  public function getValues( array $arr ){
    $formPrefix = $this->getName().'/';
    foreach( $arr as $k => $v ){
      if( strpos( $k, $formPrefix ) == 0 ){
        $this->values[ substr( $k, strlen($formPrefix) ) ] = $v;
      }
    }
  }
  
  public function input( $name, $attributes = array() ){
    $el = new Input( $this->getName().'/'.$name, $attributes );
    if( isset($this->values[ $name ]) ){
      $el->setValue( $this->values[ $name ] );
    }
    $inputs[ $name ] = $el;
    return $el->toString();
  }
  
  public function getName(){
    return $this->name;
  }
  
}

class FormElement{
  private $tag;
  private $name;
  public $attributes;
  private $error;
  
  public function __construct( $tag, $name, $attributes = array() ){
    $this->tag = $tag;
    $this->setName( $name );
    $this->attributes = $attributes;
  }
  
  public function toString(){
    return '<span class="FormElement">'.
              '<span class="error">'.$this->getError().'</span>'.
              '<span class="element">'.
                '<'.$this->getTag().' '.$this->makeAttributes( $this->attributes ).' />'.
              '</span>'.
            '</span>';
  }
  
  public function setValue( $val ){
    $this->attributes[ 'value' ] = $val;
  }
  
  public function getValue(){
    return $this->attributes[ 'value' ];
  }
  
  public function setError( $val ){
    $this->error = $val;
  }
  
  public function getError(){
    return $this->error;
  }
  
  protected function makeAttributes( $attributes ){
    $h = array();
    foreach( $attributes as $k => $v ){
      $h[] = $k.'="'.$v.'"';
    }
    return implode( ' ', $h );
  }
  
  public function getTag(){
    return $this->tag;
  }
  public function setName( $val ){
    $this->name = $val;
  }
  
  public function getName(){
    return $this->name;
  }
}

class Input extends FormElement{
  
  public function __construct( $name, $attributes = array() ){
    Geek::setDefaults( $attributes, array('type'=>'text') );
    parent::__construct( 'input', $name, $attributes );
  }
  
}

?>
