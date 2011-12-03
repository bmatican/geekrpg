<?php

  class Home extends GeekView{

    public function __construct( $args ){
      parent::__construct( $args );
      $this->add( "You are HOME!" );
    }
    
  }

?>