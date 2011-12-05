<?php

  class Index extends GeekView{

    public function __construct( array $args = array() ){
    
      $this->add(
        Geek::getView( 'search', null, array(
          'action'    => 'user/search'
        ))
      );
      
      if( empty( $args['users'] ) ){
        $this->add( 'No users found' );
      } else {
        foreach( $args['users'] as $k => $v ){
          $userLink = Geek::path( 'user/profile/'.$v['username'] );
          $this->add(
            '<div class="post">
                <a href="'.$userLink.'"><b>'.$v['username'].'</b>: '.$v['email'].'</a>
            </div>'
          );
        }
      }
      
    }
    
  }

?>
