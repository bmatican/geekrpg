<?php

  $args       = $this->getViewArgs();
  $problemID  = $this->controller->problemid;
  if( isset($args['result']) && $args['result'] ){
    echo "Meh, success!";
  } else {
    $form = new Form( 'solution', 'solution/add/'.$problemID, 'problemid,title,body', array(
      'id'            => 'register',
      'autocomplete'  => 'off'
    ));
    $form->getValues( $args );
?>
  <h1>
    Give 'em a problem
  </h1>
  <section>
    <?php 
      echo $form->input('problemid', array('type'=>'hidden', 'value'=>$problemID));
      echo $form->input('title', array('placeholder'=>'title', 'title'=>'All posts myst have a title... duh!'));
      echo $form->textarea('body', array('placeholder'=>'Your text is here!'));
      echo $form->input('submit', array('type'=>'submit', 'value'=>'Submit post', 'title'=>'hit click/enter'));
    ?>
  </section>
<?
  }
?>
