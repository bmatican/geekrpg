<?php

  class ERROR_500 extends GeekView{
  
    public function __construct( $args ){
      parent::__construct( $args );
      $this->add(<<<HTML
        <div style="text-align:center; margin-bottom:5px;"><img src="images/500.png" alt="500 not found thumbeast image" /></div>
        <div style="text-align:right;">*
          500 - Something went wrong behind the scenes. <br />
          Bear with us for a moment while we catch the culprit :)
        </div>
HTML
      );
    }
    
  }
?>
