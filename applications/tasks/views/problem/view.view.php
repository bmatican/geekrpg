<?

  class View extends GeekView{
    
    private $MAX_BODY_SIZE = 200;
    
    public function __construct( $args ){
      parent::__construct( $args );

      $p            = $args['problem'];
      $time         = time() - intval( $p['dateAdded'] );
      $time         = formatTime( timeVals( $time ) );
      $href         = Geek::path('problem/view/'.$p['id']);
      $addSolution  = Geek::path('solution/add/'.$p['id']);
      $viewSolution = Geek::path('solution/index/'.$p['id']);
      $edit         = Geek::path('problem/edit/'.$p['id']);
      $h = <<<POST
        <div class="post">
          <h4><a href="$href">$p[title]</a></h4>
          <div class="meta">
            $time
          </div>
          <div class="body">$p[body]</div>
          <div class="actions">
            <a href="$edit">Edit</a> |
            <a href="$viewSolution">View Solutions</a> |
            <a href="$addSolution">Add Solution</a>
          </div>
        </div>
POST;

      $this->add( $h );

      $commentArgs = array(
        'id'          => $p['id'],
        'form_action' => 'problem/comment',
        'comments'    => $p['comments']
      );
      
      $this->add( Geek::getView('Comment', null, $commentArgs ) );
      
    }
    
  }

?>
