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
    $form->input('postid', array( 'type' => 'hidden', 'value' => $args['id'] ) );
    $form->textarea('body', array( 'placeholder'=>'Spam your comment here please', 'title'=>'You should not spam though... comment wisely or funny'));
    $form->input('parentid', array( 'type' => 'hidden', 'value' => 0 ) );
    $form->input('submit', array('type'=>'submit', 'value'=>'Comment'));

    $this->prepend( $this->_generateComments( $args, $args['comments'] ) );
  }
  
  private function _generateComments( $args, $comments ){
    $h = '';
    
    foreach( $comments as $v ){
      $c = $v['value'];
      //$user = 'UID('.$c['userid'].')';
      $user = $c['username'];
      $time = formatTime( timeVals( time() - intVal($c['dateAdded']) ) );
      $comment = Geek::getView( 'Blank' );
      $commentForm = $comment->Form( 'comment', $args['form_action'].'/'.$c['id'], $args['form_fields'], array('style'=>'display:none') );
      $commentForm->input('postid', array( 'type' => 'hidden', 'value' => $args['id'] ) );
      $commentForm->textarea('body', array( 'placeholder'=>'Spam your comment here please', 'title'=>'You should not spam though... comment wisely or funny'));
      $commentForm->input('parentid', array( 'type' => 'hidden', 'value' => $c['id'] ) );
      $commentForm->input('submit', array('type'=>'submit', 'value'=>'Comment'));
      $commentForm = $commentForm->toString();
      $children = '';
      if( !empty( $v['children'] ) ){
        $children .= $this->_generateComments( $args, $v['children'] );
      }
      $h .= <<<COMMENT
        <div class="comment">
          <div class="body">$c[body]</div>
          <div class="actions">
            <span class="meta"> <i>$time</i> ago by <b>$user</b> </span>
            <div class="buttons">
              <a href="javascript:void(0)" onclick="$(this).next().next().slideToggle()">Reply</a>
              <a href="javascript:void(0)">Delete</a>
              $commentForm
              $children
            </div>
          </div>
        </div>
COMMENT;
    }
    return $h;
  }
  
}

?>
