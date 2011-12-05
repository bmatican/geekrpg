<?php

class Form extends GeekView{
  
  private $name;
  private $action;
  private $inputs = array();
  private $values = array();
  private $errors = array();
  public $data   = array();
  private $argsOrder;
  public $attributes;
  
  public function __construct( $name, $action, $argsOrder, array $attributes = array() ){
    $this->setName( $name );
    $this->setAction( $action );
    $this->setArgsOrder( $argsOrder );
    Geek::setDefaults( $attributes, array( 'method' => 'post' ) );
    $this->attributes = $attributes;
  }

  public function __call( $methodName, $arguments ){
    array_unshift( $arguments, $methodName );
    return call_user_func_array( array( $this, "addInput" ), $arguments );
  }
  
  public function addOpen(){
    
    $attr = array();
    foreach( $this->attributes as $k => $v ){
      $attr[] = $k.'="'.$v.'"';
    }
    $attr = implode( ' ', $attr );
    
    // open form code
    $h = '<form action="'.Geek::path($this->action).'" '.$attr.'>'."\n";
    
    // Render the data array as hidden inputs
    foreach( $this->data as $k => $v ){
      $hidden = $this->hidden( $k );
      $hidden->setValue( $v );
      $h .= $hidden->toString();
    }
    $this->prepend( $h );
  }

  public function addClose(){
    $this->add('</form>');
  }

  public static function returnName( $formName ){
    return "__form:$formName";
  }
  public static function returnValues( $formName, array $data = array() ){
    return array( self::returnName( $formName ) => $data );
  }
  
  /**
   * Gets the values from a viewArgs type array
   */
  public function getValues( array $arr = array() ){
    $values = isset( $arr['__post'] ) ? $arr['__post'] : $arr;
    if( !isset($arr[ self::returnName($this->getName()) ]) ){
      $formPrefix = $this->getName().'/';
      foreach( $values as $k => $v ){
        if( strpos( $k, $formPrefix ) == 0 ){
          $this->values[ substr( $k, strlen($formPrefix) ) ] = $v;
        }
      }
    } else {
      $data = $arr[ self::returnName( $this->getName() ) ];
      foreach( $data as $k => $v ){
        $this->values[ $k ] = $v;
      }
    }

    if( isset( $arr['__errors'] ) ){
      $this->errors = $arr['__errors'];
    }
  }
  
  public function addInput( $type, $name, array $attributes = array(), $wrap = true ){
    $n = $name;
    if( substr( $name, 0, 2 ) != '__' ){
      $n = $this->getName().'/'.$name;
    }
    $el = new $type( $n, $attributes, $wrap );
    $this->inputs[ $name ] = $el;
    
    if( isset($this->values[ $name ]) ){
      $el->setValue( $this->values[ $name ] );
    }
    
    if( isset($this->errors[ $name ]) ){
      $el->setError( $this->errors[ $name ] );
    }

    $this->add( $el );
    
    return $el;
  }

  public function toString(){
    $this->addOpen();
    foreach( $this->queue as $k => $v ){
      if( $v instanceof FormElement ){
        $this->queue[$k] = $v->toString();
      }
    }
    $this->addClose();
    return parent::toString();
  }

  public function setValues( array $arr = array() ){
    foreach( $arr as $k => $v ){
      $this->values[ $k ] = $v;
      if( isset( $this->inputs[ $k ] ) ){
        $this->inputs[ $k ]->setValue( $v );
      }
    }
    return $this;
  }
  
  public function setName( $val ){
    $this->name = $val;
    $this->addData(array(
      '__form_name' => $val
    ));
    return $this;
  }
  public function getName(){
    return $this->name;
  }
  
  public function addData( array $arr ){
    foreach( $arr as $k => $v ){
      $this->data[ $k ] = $v;
    }
    return $this;
  }

  public function setAction( $val ){
    $this->action = $val;
    return $this;
  }
  public function getAction(){
    return $this->action;
  }
  
  public function setArgsOrder( $val ){
    $this->argsOrder = $val;
    $this->addData(array(
      '__argumentsOrder' => $val
    ));
    return $this;
  }
  public function getArgsOrder(){
    return $this->argsOrder;
  }
}

class FormElement extends HtmlElement{
  protected $name;
  protected $error;
  protected $wrap;
  
  public function __construct( $tag, $name, array $attributes = array(), $wrap = true){
    parent::__construct( $tag, $attributes );
    $this->attributes['name'] = $name;
    $this->wrap               = $wrap;
    $this->setName( $name );
  }
  
  protected function wrapper( $string ){
    return '<span class="FormElement">'.
              '<span class="error">'.$this->getError().'</span>'.
              '<span class="element">'.
                $string.
              '</span>'.
            '</span>';
  }
  
  public function toString( $wrap = true ){
    if( $this->wrap && $wrap ){
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
  
  public function setName( $val ){
    $this->name = $val;
  }
  
  public function getName(){
    return $this->name;
  }
}

class Input extends FormElement{
  public function __construct( $name, array $attributes = array(), $wrap = true ){
    Geek::setDefaults( $attributes, array('type'=>'text') );
    parent::__construct( 'input', $name, $attributes, $wrap );
  }
}
class Password extends Input{
  public function __construct( $name, array $attributes = array(), $wrap = true ){
    $attributes[ 'type' ] = 'password';
    parent::__construct( $name, $attributes, $wrap );
  }
}
class Hidden extends Input{
  public function __construct( $name, array $attributes = array(), $wrap = false ){
    $attributes[ 'type' ] = 'hidden';
    parent::__construct( $name, $attributes, false );
  }
}
class CheckBox extends Input{
  public function __construct( $name, array $attributes = array(), $wrap = true ){
    $attributes[ 'type' ] = 'checkbox';
    parent::__construct( $name, $attributes, $wrap );
  }
}
class Radio extends Input{
  public function __construct( $name, array $attributes = array(), $wrap = true ){
    $attributes[ 'type' ] = 'radio';
    parent::__construct( $name, $attributes, $wrap );
  }
}
class Submit extends Input{
  public function __construct( $name, array $attributes = array(), $wrap = true ){
    $attributes[ 'type' ] = 'submit';
    parent::__construct( $name, $attributes, $wrap );
  }
}

class FormElementContainer extends FormElement{
  private $value;

  public function toTag(){
    return '<'.$this->getTag().' '.$this->makeAttributes($this->attributes).'>'.$this->getValue().'</'.$this->getTag().'>';
  }
  
  public function setValue( $val ){
    $this->value = $val;
  }
  public function getValue(){
    return $this->value;
  }
}

class TextArea extends FormElementContainer{
  public function __construct( $name, array $attributes = array(), $wrap = true ){
    parent::__construct( 'textarea', $name, $attributes, $wrap );
  }
}
class Select extends FormElementContainer{
  public function __construct( $name, array $attributes = array(), $wrap = true ){
    parent::__construct( 'select', $name, $attributes, $wrap );
    $this->setValue('Select tags are not implemented yet :(');
  }
}

?>
