<?php

  class ErrorView extends GeekView {

    public function __construct( $args ){
      parent::__construct( $args );
      $this->printError();
    }

    public function printError() {
      foreach ($this->args as $a) {
        $this->add( '<pre>'.var_export($a, true).'</pre>' );
      }
    }
  }
?>
 
