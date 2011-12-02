<?php
/**
  * This is generally where the license goes :)
  */

class SolutionController extends Geek_Controller {
	public $solutionModel;
	public $solutionCommentModel;
	
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->solutionModel        = new SolutionModel();
    $this->solutionCommentModel = new CommentModel($this->solutionModel->tablename);
  }
  
  public function index($problemid = null, $solutionid = null) {

    //TODO: PASS PARAMETERS FOR VIEWS ...need to know what to render
    $this->solutions = array(); 
    if (null === $problemid) {
      $this->render( '404' );
      /*
      //TODO: display all of your proposed/accepted solutions to each set of problems
      $this->solutions = $this->solutionModel->getAllWhere(array('userid = ' . $_SESSION['userid'] ));
      $this->render('solution/index.php');
      */
    } else if (null === $solutionid) {
      //TODO: display the solutions posted for this problem if you have rights to see it
      $this->problemid = $problemid;
      $this->solutions = $this->solutionModel->getAllSolutions($problemid, $_SESSION['user']['id']);
      $this->render('index.php');
    } else {
      //TODO: display the solution for this problem, if you have rights to see it
      $solution = $this->solutionModel->getById($solutionid);
      if (!isset($solution) 
        || $solution['userid'] != $_SESSION['user']['id'] 
        || $solution['problemid'] != $problemid) {
          
        $this->render('404.php');
      } else {
        //TODO: ....think of how you need the data in the view
        $this->solutions = array($solution);
        $this->render('index.php');
      }
    }
  }
  
  /**
   * Add a solution
   */
  public function add( $problemid = null, $title = null, $body = null, $state = PostModel::POST_OPEN ){
    //TODO: check rights
    $this->problemid = $problemid ? $problemid : null;
    if( $problemid === null ){
      $this->render( '404' );
    } else if( $title === null ){
      $this->render( 'add.php' );
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
    
        $userid = $_SESSION['user']['id'];
        $values = array(
          "userid"      => $userid,
          "problemid"   => $problemid,
          "title"       => $title,
          "body"        => $body,
          "dateAdded"   => time(),
          "state"       => $state,
        );
        $this->solutionModel->insert($values);
        Geek::redirectBack();
      }
      
    }
  }
  
  
  public function view( $id = null ){
    //TODO: PROPERLY CHECK FOR PERMISSION
    if( null !== $id && is_numeric($id) ){
      $this->solution = $this->solutionModel->getAllSolutions( $id );
      $this->solution = $this->solution[0];
      if( $this->solution ){
        $this->solution["comments"] = $this->solutionCommentModel->getComments($this->solution["id"]);
        $this->solution["comments"] = $this->solution["comments"][0]["children"];
      }
      $this->render( 'view.php' );
    } else {
      $this->render('404');
    }
  }
  
  
  /**
   * Post a comment for this specific problem, potentially replying to a 
   * comment.
   *
   * @param {int} $postid  the problem we are commenting on
   * @param {string} $body  the text of the comment
   * @param {int} $parentid
   * @param $state
   */
    public function comment($problemid = null, $body = null, $parentid = 0, $state = CommentModel::COMMENT_OPEN) {
      //TODO: check rights
      $userid = $_SESSION['user']['id'];
      $values = array(
        "userid"    => $userid,
        "postid"    => $problemid,
        "body"      => $body,
        "dateAdded" => time(),
        "parentid"  => $parentid,
        "state"     => $state
      );
      $this->solutionCommentModel->insert($values);
      Geek::redirectBack();
//      $this->render( 'problem/view/'.$postid );
    }
  
}

?>

