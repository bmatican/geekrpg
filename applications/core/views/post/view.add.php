<?php

  class Add extends GeekView{

    public function __construct( array $args = array() ){
      parent::__construct( $args );

      Geek::setDefaults( $args, array(
        'controller'  => 'post',
        'result'      => false
      ));
      
      if( $args['result'] ){
        $this->add( '<h3>Success!</h3>' );
      } else {
        $form = $this->Form( 'post', $args['controller'].'/add', 'title,body,tags', array(
          'id'            => 'register',
          'autocomplete'  => 'off'
        ));
        $form->getValues( $args );

        $form->add('<h1>New Post</h1><section>');
        $form->input('title', array('placeholder'=>'title', 'title'=>'All posts myst have a title... duh!'));
        $form->textarea('body', array('placeholder'=>'Your text is here!'));
        $form->input('tags', array( 'placeholder' => 'tag1, tag2, ...', 'title' => 'at least 1 tag. Comma separated' ) );
        $form->input('submit', array('type'=>'submit', 'value'=>'Submit post', 'title'=>'hit click/enter'));
        $form->add('</section>');

      }
    }

  }

?>
