<?php

  class LogIn extends GeekView{

    public function __construct( array $args = array() ){
      parent::__construct( $args );
      Geek::setDefaults( $args, array(
        'display' => 'inline'
      ));

      call_user_func_array( array( $this, "display_".$args['display'] ), $args );

    }

    public function display_inline(){
      if( Geek::isOnline() ){
        $this->add('
          <div>
            Welcome <b>'.$_SESSION['user']['username'].'</b>.
            <a href="'.Geek::path('user/logout').'">log out</a>
          </div>'
        );
      } else {
        $form = $this->Form( 'login', 'user/login', 'username,password,remember' );
        $form->input( 'username', array( 'size' => 10, 'placeholder' => 'username', 'title' => 'Username' ), false );
        $form->password( 'password', array( 'size' => 10, 'placeholder' => 'password', 'title' => 'Password' ), false );
        $form->checkbox( 'remember', array( 'title' => 'Remember?' ), false );
        $form->submit( 'submit', array( 'value' => 'Log In!', 'title' => 'GO!' ), false );
      }
    }

    public function display_block(){
      if( !Geek::isOnline() ){
        $this->add('
          <div>
            Welcome <b>'.$_SESSION['user']['username'].'</b>.
            <a href="'.Geek::path('user/logout').'">log out</a>
          </div>'
        );
      } else {
        $form = $this->Form( 'login', 'user/login', 'username,password,remember' );
        $form->input( 'username', array( 'size' => 10, 'placeholder' => 'username', 'title' => 'Username' ) );
        $form->password( 'password', array( 'size' => 10, 'placeholder' => 'password', 'title' => 'Password' ) );
        $form->checkbox( 'remember', array( 'title' => 'Remember?' ) );
        $form->submit( 'submit', array( 'value' => 'Log In!', 'title' => 'GO!' ) );
      }
    }
    
  }

?>
