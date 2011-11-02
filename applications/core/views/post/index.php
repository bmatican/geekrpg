<?php
  
  define( 'MAX_BODY_SIZE', 200 );  
  
  foreach( $this->controller->posts as $k => $v ){
    $body = strlen( $v['body'] ) > MAX_BODY_SIZE ? substr( $v['body'], 0, MAX_BODY_SIZE ) . '...' : $v['body'];
    $time = time() - intval( $v['dateAdded'] );
    $time = formatTime( timeVals( $time ) );
    $href = Geek::path('post/index/'.$v['id']);
    echo <<<POST
      <div class="post">
        <h4><a href="$href">$v[title]</a></h4>
        <div class="meta">
          $time
        </div>
        <div class="body">$body</div>
      </div>
POST;
  }
  
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
