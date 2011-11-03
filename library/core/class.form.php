<?php

class Form{
  
  private $name;
  private $action;
  private $inputs = array();
  private $values = array();
  private $errors = array();
  // list of key=>value pairs that will be associated with hidden inputs within the form
  private $data   = array();
  
  public function __construct( $name, $action ){
    $this->name   = $name;
    $this->action = $action;
    $this->addData(array(
      '__form_name' => $this->getName()
    ));
  }
  
  public function open( $argsOrder, array $attributes = array() ){
    
    Geek::setDefaults( $attributes, array('method'=>'post') );
    
    $attr = array();
    foreach( $attributes as $k => $v ){
      $attr[] = $k.'="'.$v.'"';
    }
    $attr = implode( ' ', $attr );
    
    // open form code
    echo '<form action="'.Geek::path($this->action).'" '.$attr.'>'."\n";
    
    $this->addData(array(
      '__argumentsOrder'  => $argsOrder
    ));
    
    // Render the data array as hidden inputs
    foreach( $this->data as $k => $v ){
      $hidden = new Input( $k, array( 'type'=>'hidden' ) );
      $hidden->setValue( $v );
      echo $hidden->toString();
    }
    
  }
  
  public function close(){
    echo '</form>'."\n";
  }
  
  /**
   * Gets the values from a viewArgs type array
   */
  public function getValues( array $arr ){
    $values = isset( $arr['__post'] ) ? $arr['__post'] : $arr;
    $formPrefix = $this->getName().'/';
    foreach( $values as $k => $v ){
      if( strpos( $k, $formPrefix ) == 0 ){
        $this->values[ substr( $k, strlen($formPrefix) ) ] = $v;
      }
    }

    if( isset( $arr['__errors'] ) ){
      $this->errors = $arr['__errors'];
    }
    
  }
  
  public function formElement( $type, $name, array $attributes = array() ){
    $el = null;
    $n = $this->getName().'/'.$name;
    switch( $type ){
      case 'textarea': $el = new TextArea( $n, $attributes ); break;
      default: case 'input': $el = new Input( $n, $attributes ); break;
    }
    
    if( isset($this->values[ $name ]) ){
      $el->setValue( $this->values[ $name ] );
    }
    if( isset($this->errors[ $name ]) ){
      $el->setError( $this->errors[ $name ] );
    }
    $inputs[ $name ] = $el;
    return $el->toString();
  }
  
  public function input( $name, array $attributes = array() ){
    return $this->formElement( 'input', $name, $attributes );
  }
  
  public function textarea( $name, array $attributes = array() ){
    return $this->formElement( 'textarea', $name, $attributes );
  }
  
  public function getName(){
    return $this->name;
  }
  
  public function addData( array $arr ){
    foreach( $arr as $k => $v ){
      $this->data[ $k ] = $v;
    }
  }
}

class FormElement{
  private $tag;
  private $name;
  private $error;
  
  public $attributes;
  
  public function __construct( $tag, $name, $attributes = array() ){
    $this->tag = $tag;
    $this->setName( $name );
    $this->attributes = $attributes;
    $this->attributes['name'] = $name;
  }
  
  protected function wrapper( $string ){
    return '<span class="FormElement">'.
              '<span class="error">'.$this->getError().'</span>'.
              '<span class="element">'.
                $string.
              '</span>'.
            '</span>';
  }
  
  public function toString(){
    return $this->wrapper( '<'.$this->getTag().' '.$this->makeAttributes( $this->attributes ).' />' );
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

class FormElementContainer extends FormElement{
  private $value;
  
  public function toString(){
    return $this->wrapper( '<'.$this->getTag().' '.$this->makeAttributes($this->attributes).'>'.$this->getValue().'</'.$this->getTag().'>' );
  }
  public function setValue( $val ){
    $this->value = $val;
  }
  public function getValue(){
    return $this->value;
  }
}

class TextArea extends FormElementContainer{
  public function __construct( $name, array $attributes = array() ){
    parent::__construct( 'textarea', $name, $attributes );
  }
}

?>
