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
      $this->problem = $this->problemModel->getAllWhere( array("id = $id") );
      $this->problem = $this->problem[0];
      $this->problem["comments"] = $this->problemCommentModel->getComments($this->problem["id"]);
      $this->problem["comments"] = $this->problem["comments"][0]["children"];
      $this->render( 'view.php' );
    } else {
      $this->render('notFound.php');
    }
  }
  
  /**
   * View all problems by user
   */
  public function byuser($userid) {
    if (!is_numeric($userid) || $userid <= 0) {
      $this->render("404.php");
    } else {
      $this->problems = $this->problemModel->getAllWhere(array("userid = $userid"));
      $this->render();
    }
  }
  
  /**
   * Add a problem to the set
   */
  public function add($title = null, $body = null, $state = PostModel::POST_OPEN) {
    // TODO: check rights??
    if( $title === null ){
      $this->render('add.php');
    } else {
      if ($state < 0 || $state >= PostModel::POST_MAX_STATE) {
        $this->render("404.php");
      } else {
        
        if( strlen( $title ) < 4 || strlen( $title ) > 42 ){
          $this->_errors['title'] = 'Title must be between 4 and 42 characters long!';
        }
        if( strlen( $body ) < 10 ){
          $this->_errors['body'] = 'You can\'t find at least 10 characters for this textfield?';
        }
        
        if( !empty( $this->_errors ) ){
          $this->render( 'add.php', array( '__errors' => $this->_errors ) );
        } else {
          $userid = $_SESSION['user']['userid'];
          $values = array(
            "userid"    => $userid,
            "body"      => $body,
            "title"     => $title,
            "dateAdded" => time(),
            "state"     => $state,
          );
            
          $this->problemModel->insert($values);
          header('Location:'.Geek::path('problem/index'));
        }
        
      }
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
//      $this->render( 'problem/view/'.$postid );
    }  
  
  private function _getComments($problemid) {
    return $this->problemCommentModel->getAllWhere(array("postid = $problemid"));
  }
  
  // TAGS
  
  public function tag($tags = FALSE, $method = "and", $limit = FALSE, $offset = FALSE) {
    if (FALSE === $tags) {
      $this->tags = $this->problemTagModel->getAllWhere(array());
      $this->render("index.php");
    } else {
      if (!in_array($method, array("and", "or"))) {
        $this->render("404.php");
      } else {
        $tags = explode(",", $tags);
        $this->problems = $this->problemTagModel->getObjectsFor(
          $tags,
          'id',
          $method == "and" ? TRUE : FALSE,
          $limit,
          $offset
        );
        $this->render("index.php");
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
    $this->render('createTag.php');
  }
  
}

?>

