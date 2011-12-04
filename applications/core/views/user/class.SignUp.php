<?php

  class SignUp extends GeekView{

    public function __construct( array $args = array() ){
      parent::__construct( $args );
      if( isset($args['result']) && $args['result'] ){
        $this->add( "User <b>".$args['username']."</b> registered successfully!" );
      } else {
        $form = $this->Form( 'signup', 'user/signup', 'username,password1,password2,email', array(
          'id'            => 'register',
          'autocomplete'  => 'off'
        ));
        $form->getValues( $args );
        $form->add('
          <h1>
            Register-a-Geek Form
          </h1>
          <section>
            <div style="margin:auto; text-align:right; width: 330px;">'
        );
        $form->input('username', array('placeholder'=>'username', 'title'=>'Input your desired username'));
        $form->input('email', array('placeholder'=>'e-mail', 'title'=>'Type in your email'));
        $form->input('password1', array( 'type'=>'password', 'placeholder'=>'password', 'title'=>'Choose your password'));
        $form->input('password2', array( 'type'=>'password', 'placeholder'=>'re-type password', 'title'=>'Copy paste what you have above :)'));
        $form->add('<div style="text-align:right">');
        $form->input('submit', array('type'=>'submit', 'value'=>'B A Geek', 'title'=>'proceed'));
        $form->add('</div>
            </div>
          </section>'
        );
      }
    }
    
  }
?>
