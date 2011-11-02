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
    $this->problemModel = new ProblemModel();
    $this->problemCommentModel = new CommentModel($this->problemModel->tablename);
    $this->problemTagModel = new TagModel($this->problemModel->tablename);
  }

  /**
   * Index all problems or view a specific problem
   * @param {INT} $id
   */
  public function index($id = FALSE, $limit = FALSE, $offset = FALSE) {
  	if (FALSE !== $id) {
  		if(is_numeric($id)) {
        $this->problems = $this->problemModel->getAllWhere(array("id = $id"), $limit, $offset);
        if (!empty($this->problems)) {
          // should only be one
          $this->problems[0]["comments"] = $this->_getComments($this->problems[0]["id"]);
        }
  	  } else {
        $this->render("404.php");
      }
    } else {
      $this->problems = $this->problemModel->getAllWhere(array("id > 0"));
    }
    $this->render();
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
  public function add() {
    $errors = array();
    
    if (!$this->isPost()) {
      $formValues = $this->getFormValues();

      // check for data integrity
      if (! $formValues["body"]) {
        $errors["body"] = "Cannot be null";
      }
      if (! $formValues["title"]) {
        $errors["title"] = "Cannot be null";
      }
      if (strlen($formValues["title"]) > 30) {
        $errors["title"] = "Cannot be longer than " . "30" . " characters"; 
      }
      
      // render back with errors or save and redirect
      //TODO: redirects?
      if(!empty($errors)) {
        $this->render("problem/add.php", $errors);
      } else {
        $this->setFormValue("dateAdded", time());
        $this->setFormValue("userid", 0);
        $this->problemModel->insert($this->getFormValues());
        
        $this->render("problem/index.php");
      }
    } else {
      $this->render("problem/add.php");
    }
  }
  
  // COMMENTS
  
  /**
   * Post a comment for this specific problem, potentially replying to a 
   * comment.
   *
   * @param $problemid the problem we are commenting on
   * @param $commentid the comment we are replying to
   */
  public function comment($problemid, $commentid = 0) {
    if (!is_numeric($problemid) || !is_numeric($commentid)) {
      $this->render("problem/404.php");
    } else {
      if (0 < count($this->problemCommentModel->getAllWhere(
        array(
          'id = "' . $commentid .'"',
          'postid = "' . $problemid. '"',
        ),
        $this->problemCommentModel->commentTable))) {
          if($this->isPost()) {
            //TODO: handle post
          } else {
            //TODO: pass arguments??
            $this->render("problem/comment.php");
          }
      }
    }
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
    $this->render();
  }
  
  // OTHER
}

?>

