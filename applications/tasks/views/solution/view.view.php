<?php
  
  $p          = $this->controller->solution;
  $time       = time() - intval( $p['dateAdded'] );
  $time       = formatTime( timeVals( $time ) );
  $href       = Geek::path('solution/view/'.$p['id']);
  echo <<<POST
    <div class="post">
      <h4><a href="$href">$p[title]</a></h4>
      <div class="meta">
        $time
      </div>
      <div class="body">$p[body]</div>
      <div class="actions">
        <a href="#">delete</a>
      </div>
    </div>
POST;
  
  function formatTime( array $arr ){
    /*
    foreach( $arr as $k => $v ){
      $arr[ $k ] = $v <= 9 ? '0'.$v : $v;
    }
    */
    return $arr['days'].'d '.$arr['hours'].'h '.$arr['minutes'].'m '.$arr['seconds'].'s ago';
  }
  
  function timeVals( $timestamp ){
    $a['hours']   = floor( $timestamp / 3600 );
    $timestamp    = $timestamp % 3600;
    $a['minutes'] = floor( $timestamp / 60 );
    $a['seconds'] = $timestamp % 60;
    $a['days']    = floor( $a['hours'] / 24 );
    $a['hours']   = $a['hours'] % 24;
    return $a;
  }
  
?>

<?php

  /** Comment Form */
  
  $args = $this->getViewArgs();
  $form = new Form( 'comment', 'solution/comment', 'postid,body,parentid', array(
    'id'            => 'register',
    'autocomplete'  => 'off'
  ));
  $form->getValues( $args );
  
  foreach( $p['comments'] as $v ){
    $c = $v["value"];
    $user = 'UID('.$c['userid'].')';
    $time = formatTime( timeVals( time() - intVal($c['dateAdded']) ) );
    echo <<<COMMENT
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
  
  echo $form->input('postid', array( 'type' => 'hidden', 'value' => $p['id'] ) );
  echo $form->textarea('body', array( 'placeholder'=>'Spam your comment here please', 'title'=>'You should not spam though... comment wisely or funny'));
  echo $form->input('parentid', array( 'type' => 'hidden', 'value' => 0 ) );
  echo $form->input('submit', array('type'=>'submit', 'value'=>'Comment'));
  
?>
