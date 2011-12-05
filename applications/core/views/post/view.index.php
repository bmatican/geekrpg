<?php

  class Index extends GeekView{

    private $MAX_BODY_SIZE = 200;

    public function __construct( $args ){
      parent::__construct( $args );

      Geek::setDefaults( $args, array(
        'controller'  => 'post',
        'posts'       => array()
      ));

      $this->add(
        Geek::getView( 'search', null, array(
          'action' => 'post/search'
        ))
      );
      
      $h = '<div style="text-align:right;margin-bottom:5px;padding:2px;border-bottom:1px solid #ccc;">
              <a href="'.Geek::path( $args['controller'].'/add' ).'">Post</a>
            </div>';

      if( count( $args['posts'] ) == 0 ){
        $h .= 'No posts found';
      } else {
        foreach( $args['posts'] as $k => $v ){
          $body = strlen( $v['body'] ) > $this->MAX_BODY_SIZE ? substr( $v['body'], 0, $this->MAX_BODY_SIZE ) . '...' : $v['body'];
          $time = time() - intval( $v['dateAdded'] );
          $time = formatTime( timeVals( $time ) );
          $href = Geek::path( $args['controller'].'/view/'.$v['id'] );
          $tags = "";
          foreach( $v['tags'] as $t ){
            $tags .= '<a href="'.Geek::path("post/tags/$t").'" class="tag">'.$t.'</a>';
          }
          $h .= <<<POST
            <div class="post">
              <h4><a href="$href">$v[title]</a></h4>
              <div class="meta">
                $tags $time
              </div>
              <div class="body">$body</div>
            </div>
POST;
        }
      }

      $this->add( $h );

    }

  }

?>
