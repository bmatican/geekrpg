<?php

  class Add extends GeekView{

    public function __construct( array $args = array() ){
      parent::__construct( $args );
      if( isset($args['result']) && $args['result'] ){
        $this->add( "User <b>".$args['username']."</b> registered successfully!" );
      } else {
        $form = $this->Form( 'problem', 'problem/add', 'title,body', array(
          'id'            => 'register',
          'autocomplete'  => 'off'
        ));
        $form->getValues( $args );

        $form->add('<h1>Give \'em a problem</h1><section>');
        $form->input('title', array('placeholder'=>'title', 'title'=>'All posts myst have a title... duh!'));
        $form->textarea('body', array('placeholder'=>'Your text is here!'));
        $form->input('submit', array('type'=>'submit', 'value'=>'Submit post', 'title'=>'hit click/enter'));
        $form->add('</section>');

      }
    }
  
  }
  
?>
