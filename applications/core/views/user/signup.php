<?php
  $args = $this->getViewArgs();
  if( isset($args['result']) && $args['result'] ){
    echo "User <b>".$args['username']."</b> registered successfully!";
  } else {
    $form = new Form( 'signup', 'user/signup' );
    $form->open( 'username,password1,password2,email', array(
      'id'            => 'register',
      'autocomplete'  => 'off'
    ));
    $form->getValues( $args );
?>
  <h1>
    Register-a-Geek Form
  </h1>
  <section>
    <div style="margin:auto; text-align:right; width: 330px;">
      <?php 
        echo $form->input('username', array('placeholder'=>'username', 'title'=>'Input your desired username'));
        echo $form->input('email', array('placeholder'=>'e-mail', 'title'=>'Type in your email'));
        echo $form->input('password1', array( 'type'=>'password', 'placeholder'=>'password', 'title'=>'Choose your password'));
        echo $form->input('password2', array( 'type'=>'password', 'placeholder'=>'re-type password', 'title'=>'Copy paste what you have above :)'));
        echo '<div style="text-align:right">'.$form->input('submit', array('type'=>'submit', 'value'=>'B A Geek', 'title'=>'proceed')).'</div>';
      ?>
    </div>
  </section>
<?
  $form->close();
  }
?>
