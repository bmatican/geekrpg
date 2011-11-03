<?php
  $args = $this->getViewArgs();
  if( isset($args['result']) && $args['result'] ){
    echo "User <b>".$args['username']."</b> registered successfully!";
  } else {
    $form = new Form( 'registration', 'post/add' );
    $form->open( 'title,body', array(
      'id'            => 'register',
      'autocomplete'  => 'off'
    ));
    $form->getValues( $args );
?>
  <h1>
    Post'o'Post form 
  </h1>
  <section>
    <?php 
      echo $form->input('title', array('placeholder'=>'title', 'title'=>'All posts myst have a title... duh!'));
      echo $form->textarea('body', array('placeholder'=>'Your text is here!'));
      echo '<div style="text-align:right">'.$form->input('submit', array('type'=>'submit', 'value'=>'B A Geek', 'title'=>'proceed')).'</div>';
    ?>
  </section>
<?
  $form->close();
  }
?>
