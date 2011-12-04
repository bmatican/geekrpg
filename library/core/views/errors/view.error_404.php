<?php

  class ERROR_404 extends ErrorView {

    public function __construct( $args ){
      $this->prepend(<<<HTML
        <div style="text-align:center; margin-bottom:5px;"><img src="images/404.png" alt="404 not found thumbeast image" /></div>
        <div style="text-align:right;">
          * This is not the page you are seeking young Padawan
        </div>
HTML
      );
      parent::__construct( $args );
    }
    
  }
  
?>
