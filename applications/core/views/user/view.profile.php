<?php

  class Profile extends GeekView{

    public function __construct( array $args = array() ){
      parent::__construct( $args );

      $actions = '';
      if( $_SESSION['user']['roleid'] == ROLE_ADMIN ){
        $delete = Geek::path( 'user/delete/'.$args['id'] );
        $actions = '
          <div class="actions">
            <a href="'.$delete.'">delete</a>
          </div>
        ';
      }
      
      $h = '
        <div class="post">
          <h3>'.$args['username'].'</h3>
          <div>'.$args['email'].'</div>
          '.$actions.'
        </div>
        ';
      $this->add( $h );
    }

    
  
  }

?>
