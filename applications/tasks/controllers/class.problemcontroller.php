<?php
/**
  * This is generally where the license goes :)
  */

class ProblemController extends Geek_Controller {
	public $problemModel;
	public $problemCommentModel;
	public $problemTagModel;
	
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->problemModel         = new ProblemModel();
    $this->problemCommentModel  = new CommentModel($this->problemModel->tablename);
    $this->problemTagModel      = new TagModel($this->problemModel->tablename);
  }

  public function index( $limit = 20, $offset = 0 ) {
    $problems = $this->problemModel->getAllWhere( array("id > 0"), $limit, $offset );
    $this->render( null, array( 'problems' => $problems ) );
  }
  
  public function view( $id = null ){
    if( null != $id && is_numeric($id) ){
      $problem = $this->problemModel->getAllWhere( array("id = $id") );
      $problem = $problem[0];
      $problem["comments"] = $this->problemCommentModel->getComments( $problem["id"] );
      $problem["comments"] = $problem["comments"][0]["children"];
      $this->render( 'View', array( 'problem' => $problem ) );
    } else {
      $this->render( 'NotFound' );
    }
  }
  
  /**
   * View all problems by user
   */
  public function byuser($userid) {
    if (!is_numeric($userid) || $userid <= 0) {
      $this->render( '404' );
    } else {
      $this->problems = $this->problemModel->getAllWhere(array("userid = $userid"));
      $this->render();
    }
  }

  private function _check_editAdd( $view, $title = null, $body = null, $state = PostModel::POST_OPEN ){
    if( $title === null ){
      $this->render( $view );
      return null;
    } else {
      if ($state < 0 || $state >= PostModel::POST_MAX_STATE) {
        $this->render( '404' );
        return null;
      } else {
        if( strlen( $title ) < 4 || strlen( $title ) > 42 ){
          $this->_errors['title'] = 'Title must be between 4 and 42 characters long!';
        }
        if( strlen( $body ) < 10 ){
          $this->_errors['body'] = 'You can\'t find at least 10 characters for this textfield?';
        }
        if( !empty( $this->_errors ) ){
          $this->render( $view, array( '__errors' => $this->_errors ) );
        } else {
          $userid = $_SESSION['user']['id'];
          $values = array(
            "userid"    => $userid,
            "body"      => $body,
            "title"     => $title,
            "dateAdded" => time(),
            "state"     => $state,
          );
          
          return $values;
        }
      }
    }
  }
  
  /**
   * Add a problem to the set
   */
  public function add($title = null, $body = null, $state = PostModel::POST_OPEN) {
    // TODO: check rights??
     if( $values = $this->_check_editAdd( 'Add', $title, $body, $state ) ){
      $this->problemModel->insert($values);
      Geek::redirect( Geek::path('problem/index') );
    }
  }

  public function edit( $id, $title = null, $body = null, $state = PostModel::POST_OPEN ){
    $problem = $this->problemModel->getById( $id );

    if( isset( $_POST ) && isset( $_POST['__edit'] ) ){
      if( $values = $this->_check_editAdd( 'Add', $title, $body, $state ) ){
        $values['id'] = $id;
        $this->problemModel->update( $values );
        Geek::redirect( Geek::path('problem/index') );
      }
    } else {
      $addView = $this->getViewInstance( 'Add' );
      $addView
        ->get( 'form/problem' )
        ->setAction( 'problem/edit/'.$id )
        ->setArgsOrder( 'id,title,body' )
        ->addData( array('__edit' => 'yes', 'id' => $id) )
        ->setValues( $problem );

      $this->render( $addView );
    }
  }
  
  // COMMENTS
  
  /**
   * Post a comment for this specific problem, potentially replying to a 
   * comment.
   *
   * @param {int} $postid  the problem we are commenting on
   * @param {string} $body  the text of the comment
   * @param {int} $parentid
   * @param $state
   */
    public function comment($postid, $body, $parentid = 0, $state = CommentModel::COMMENT_OPEN) {
      //TODO: check rights
      $userid = $_SESSION['user']['id'];
      $values = array(
        "userid"    => $userid,
        "postid"    => $postid,
        "body"      => $body,
        "dateAdded" => time(),
        "parentid"  => $parentid,
        "state"     => $state,
      );
      $this->problemCommentModel->insert($values);
      Geek::redirectBack();
    }  
  
  private function _getComments($problemid) {
    return $this->problemCommentModel->getAllWhere(array("postid = $problemid"));
  }
  
  // TAGS
  
  public function tag($tags = FALSE, $method = "and", $limit = FALSE, $offset = FALSE) {
    if (FALSE === $tags) {
      $this->tags = $this->problemTagModel->getAllWhere(array());
      $this->render( 'Index' );
    } else {
      if (!in_array($method, array("and", "or"))) {
        $this->render( '404' );
      } else {
        $tags = explode(",", $tags);
        $this->problems = $this->problemTagModel->getObjectsFor(
          $tags,
          'id',
          $method == "and" ? TRUE : FALSE,
          $limit,
          $offset
        );
        $this->render( 'Index' );
      }
    }
  }
  
  public function createTag($name = FALSE, $description = FALSE) {
    if (FALSE !== $name && FALSE !== $description) {
      $this->problemTagModel->createTags(array(array(
        "name" => $name,
        "description" => $description,
      )));
    }
    $this->render('CreateTag' );
  }
  
}

?>

