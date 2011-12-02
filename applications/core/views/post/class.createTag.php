<?php
  $args = $this->getViewArgs();
    $form = new Form( 'post', 'post/createTag' );
    $form->open( 'name,description', array(
      'id'            => 'register',
      'autocomplete'  => 'off'
    ));
    $form->getValues( $args );
?>
  <h1>
    Create a tag
  </h1>
  <section>
    <?php 
      echo $form->input('name', array('placeholder'=>'Name', 'title'=>'The name of the tag!'));
      echo $form->input('description', array('placeholder'=>'Description', 'title'=>'Give a description of your tag'));
      echo $form->input('submit', array('type'=>'submit', 'value'=>'Submit post', 'title'=>'hit click/enter'));
    ?>
  </section>
<?
  $form->close();
?>
