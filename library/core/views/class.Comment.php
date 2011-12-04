<?php

class Comment extends GeekView{

  public function __construct( $args ){
  
    parent::__construct( $args );
    Geek::setDefaults( $args, array(
      'form_name'   => 'comment',
      'form_action' => '',
      'form_fields' => 'postid,body,parentid',
      'form_attributes' => array(
        'id'            => 'register',
        'autocomplete'  => 'off'
      )
    ));
    
    $form = $this->Form( $args['form_name'], $args['form_action'], $args['form_fields'], $args['form_attributes'] );
    $form->getValues( $args );

    $h = '';
    foreach( $args['comments'] as $v ){
      $c = $v['value'];
      $user = 'UID('.$c['userid'].')';
      $time = formatTime( timeVals( time() - intVal($c['dateAdded']) ) );
      $h .= <<<COMMENT
        <div class="comment">
          <div class="body">$c[body]</div>
          <div class="actions">
            <span class="meta"> <i>$time</i> ago by <b>$user</b> </span>
            <div class="buttons">
              <a href="javascript:void(0)">Reply</a>
              <a href="javascript:void(0)">Delete</a>
              <a href="javascript:void(0)">Spam</a>
            </div>
          </div>
        </div>
COMMENT;
    }

    $form->add( $h );
    $form->input('postid', array( 'type' => 'hidden', 'value' => $args['id'] ) );
    $form->textarea('body', array( 'placeholder'=>'Spam your comment here please', 'title'=>'You should not spam though... comment wisely or funny'));
    $form->input('parentid', array( 'type' => 'hidden', 'value' => 0 ) );
    $form->input('submit', array('type'=>'submit', 'value'=>'Comment'));
  
  }

}

?>
