<div style="text-align:right;margin-bottom:5px;padding:2px;border-bottom:1px solid #ccc;">
  <a href="<?php echo Geek::path('problem/add'); ?>">Post a problem</a>
</div>
<?php
  
  define( 'MAX_BODY_SIZE', 200 );  
  
  if( count( $this->controller->problems ) == 0 ){
    echo 'No problems found';
  } else {
    
    foreach( $this->controller->problems as $k => $v ){
      $body = strlen( $v['body'] ) > MAX_BODY_SIZE ? substr( $v['body'], 0, MAX_BODY_SIZE ) . '...' : $v['body'];
      $time = time() - intval( $v['dateAdded'] );
      $time = formatTime( timeVals( $time ) );
      $href = Geek::path('problem/view/'.$v['id']);
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
