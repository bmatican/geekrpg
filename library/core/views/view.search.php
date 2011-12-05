<?php

  class Search extends GeekView{

    public function __construct( array $args = array() ){
      parent::__construct( $args );

      Geek::setDefaults( $args, array(
        'display'   => 'inline',
        'name'      => 'search',
        'argsOrder' => 'query'
      ));
      
      switch( $args['display'] ){
        default: case 'inline': $this->display_inline( $args ); break;
      }
    }

    private function display_inline( array $args ){
      $this->add('<div class="search search-inline" style="text-align:right; margin-bottom:10px;">');
      $form = $this->Form( $args['name'], $args['action'], $args['argsOrder'] );
      $form->input( 'query', array( 'size' => 25 ), false );
      $form->submit( 'Search', array(), false );
      $this->add( '</div>' );
    }
    
  }

?>
