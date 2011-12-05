<?php

  class HTML{

    public static function element(){
      return HTML::instantiate( 'HtmlElement', func_get_args() );
    }

    public static function container(){
      return HTML::instantiate( 'HtmlContainer', func_get_args() );
    }

    public static function instantiate( $class = null, $args = array() ){
      if( $class ){
        $elem = call_user_func_array( $class, $args );
        return $elem->toString();
      }
      return '';
    }
    
  }
  
  class HtmlElement {
    private $tag;
    private $permission;
    
    protected $attributes;

    public function __construct( $tag, array $attributes = array(), $permission = null ){
      $this->tag = $tag;
      $this->setAttributes( $attributes );
      $this->setPermission( $permission );
    }

    public function getTag(){
      return $this->tag;
    }
    
    public function toTag(){
      return '<'.$this->getTag().' '.$this->makeAttributes( $this->attributes ).' />';
    }

    public function toString( $checkPermission = true ){
      if( $checkPermission && !Geek::checkPermission( $this->getPermission() ) ){
        return '';
      } else {
        return $this->toTag();
      }
    }

    public function setPermission( $permission ){
      $this->permission = $permission;
      return $this;
    }
    public function getPermission(){
      return $this->permission;
    }
    
    public function setAttributes( array $attr ){
      foreach( $attr as $k => $v ){
        $this->attributes[ $k ] = $v;
      }
      return $this;
    }
    public function getAttributes(){
      return $this->attributes;
    }
    
    protected function makeAttributes( $attributes ){
      $h = array();
      foreach( $attributes as $k => $v ){
        $h[] = $k.'="'.$v.'"';
      }
      return implode( ' ', $h );
    }

  }

  class HtmlContainer extends HtmlElement {
    private $value;

    public function __construct( $tag, $value = '', array $attributes = array(), $permission = null ){
      parent::__construct( $tag, $attributes, $permission );
      $this->setValue( $value );
    }
    
    public function toTag(){
      if( ($value = $this->getValue()) instanceof HtmlElement ){
        $value = $value->toString();
      }
      return '<'.$this->getTag().' '.$this->makeAttributes($this->attributes).'>'.$value.'</'.$this->getTag().'>';
    }
    
    public function setValue( $val ){
      $this->value = $val;
    }
    
    public function getValue(){
      return $this->value;
    }
  }

  class Anchor extends HtmlContainer {
    public function __construct( $value, array $attributes = array(), $permission = null ){
      Geek::setDefaults( $attributes, array(
        'href'  => 'javascript:void(0)'
      ));
      parent::__construct( 'a', $value, $attributes , $permission);
    }
  }
  
?>
