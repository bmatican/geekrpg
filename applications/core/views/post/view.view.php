<?php

  class View extends GeekView{

    private $MAX_BODY_SIZE = 200;

    public function __construct( $args ){
      parent::__construct( $args );

      Geek::setDefaults( $args, array(
        'controller'  => 'post',
        'problem'     => array()
      ));

      $p            = $args['post'];
      $time         = time() - intval( $p['dateAdded'] );
      $time         = formatTime( timeVals( $time ) );
      $href         = Geek::path( $args['controller'].'/view/'.$p['id'] );
      $edit         = Geek::path( $args['controller'].'/edit/'.$p['id'] );
      $tags         = "";
      foreach( $p['tags'] as $t ){
        $tags .= '<a href="'.Geek::path("post/tags/$t").'" class="tag">'.$t.'</a>';
      }
      $h = <<<POST
        <div class="post">
          <h4><a href="$href">$p[title]</a></h4>
          <div class="meta">
            $tags$time
          </div>
          <div class="body">$p[body]</div>
          <div class="actions">
            <a href="$edit">Edit</a>
          </div>
        </div>
POST;

      $this->add( $h );

      $commentArgs = array(
        'id'          => $p['id'],
        'form_action' => $args['controller'].'/comment',
        'comments'    => $p['comments']
      );
      $this->add( Geek::getView('Comment', null, $commentArgs ) );

    }

  }

?>
