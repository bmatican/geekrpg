<?

  class Index extends GeekView{
    
    private $MAX_BODY_SIZE = 200;
    
    public function __construct( $args ){
      parent::__construct( $args );
      $h = '<div style="text-align:right;margin-bottom:5px;padding:2px;border-bottom:1px solid #ccc;">
              <a href="'.Geek::path('problem/add').'">Post a problem</a>
            </div>';
      
      if( count( $args['problems'] ) == 0 ){
        $h .= 'No problems found';
      } else {
        foreach( $args['problems'] as $k => $v ){
          $body = strlen( $v['body'] ) > $this->MAX_BODY_SIZE ? substr( $v['body'], 0, $this->MAX_BODY_SIZE ) . '...' : $v['body'];
          $time = time() - intval( $v['dateAdded'] );
          $time = $this->formatTime( $this->timeVals( $time ) );
          $href = Geek::path('problem/view/'.$v['id']);
          $h .= <<<POST
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
      
      $this->add( $h );
      
    }
    
    public function formatTime( array $arr ){
      /*
      foreach( $arr as $k => $v ){
        $arr[ $k ] = $v <= 9 ? '0'.$v : $v;
      }
      */
      return $arr['days'].'d '.$arr['hours'].'h '.$arr['minutes'].'m '.$arr['seconds'].'s ago';
    }
    
    public function timeVals( $timestamp ){
      $a['hours']   = floor( $timestamp / 3600 );
      $timestamp    = $timestamp % 3600;
      $a['minutes'] = floor( $timestamp / 60 );
      $a['seconds'] = $timestamp % 60;
      $a['days']    = floor( $a['hours'] / 24 );
      $a['hours']   = $a['hours'] % 24;
      return $a;
    }
    
  }

?>