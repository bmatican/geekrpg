<?php

class Form extends GeekView{
  
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
  
  public function __call( $methodName, $arguments ){
    array_unshift( $arguments, $methodName );
    return call_user_func_array( array( $this, "addInput" ), $arguments );
  }
  
  public function open( $argsOrder, array $attributes = array() ){
    
    Geek::setDefaults( $attributes, array('method'=>'post') );
    
    $attr = array();
    foreach( $attributes as $k => $v ){
      $attr[] = $k.'="'.$v.'"';
    }
    $attr = implode( ' ', $attr );
    
    // open form code
    $h = '<form action="'.Geek::path($this->action).'" '.$attr.'>'."\n";
    
    $this->addData(array(
      '__argumentsOrder'  => $argsOrder
    ));
    
    // Render the data array as hidden inputs
    foreach( $this->data as $k => $v ){
      $hidden = $this->input( $k, array( 'type'=>'hidden' ) );
      $hidden->setValue( $v );
      $h .= $hidden->toString();
    }

    $this->add( $h );
  }

  public function close(){
    $this->add('</form>');
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
  
  public function addInput( $type, $name, array $attributes = array() ){
    $n = $name;
    if( substr( $name, 0, 2 ) != '__' ){
      $n = $this->getName().'/'.$name;
    }
    $el = new $type( $n, $attributes );
    $inputs[ $name ] = $el;
    
    if( isset($this->values[ $name ]) ){
      $el->setValue( $this->values[ $name ] );
    }
    
    if( isset($this->errors[ $name ]) ){
      $el->setError( $this->errors[ $name ] );
    }

    $this->add( $el );
    
    return $el;
  }

  public function render(){
    foreach( $this->queue as $k => $v ){
      if( $v instanceof FormElement ){
        $this->queue[$k] = $v->toString();
      }
    }
    parent::render();
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

  public function toTag(){
    return '<'.$this->getTag().' '.$this->makeAttributes( $this->attributes ).' />';
  }
  
  public function toString( $wrap = true ){
    if( $wrap ){
      return $this->wrapper( $this->toTag() );
    } else {
      return $this->toTag();
    }
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
class Password extends Input{
  public function __construct( $name, $attributes = array() ){
    $attributes[ 'type' ] = 'password';
    parent::__construct( 'input', $name, $attributes );
  }
}
class Hidden extends Input{
  public function __construct( $name, $attributes = array() ){
    $attributes[ 'type' ] = 'hidden';
    parent::__construct( 'input', $name, $attributes );
  }
  public function render(){
    parent::render( false );
  }
}
class CheckBox extends Input{
  public function __construct( $name, $attributes = array() ){
    $attributes[ 'type' ] = 'checkbox';
    parent::__construct( 'input', $name, $attributes );
  }
}
class Radio extends Input{
  public function __construct( $name, $attributes = array() ){
    $attributes[ 'type' ] = 'radio';
    parent::__construct( 'input', $name, $attributes );
  }
}
class Submit extends Input{
  public function __construct( $name, $attributes = array() ){
    $attributes[ 'type' ] = 'submit';
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
class Select extends FormElementContainer{
  public function __construct( $name, array $attributes = array() ){
    parent::__construct( 'select', $name, $attributes );
    $this->setValue('Select tags are not implemented yet :(');
  }
}

?>
