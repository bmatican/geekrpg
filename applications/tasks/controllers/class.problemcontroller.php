<?php
/**
  * This is generally where the license goes :)
  */

class ProblemController extends Geek_Controller {
	public $problemModel;
	public $problemCommentModel;
	
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->problemModel = new ProblemModel();
    $this->problemCommentModel = new CommentModel($this->problemModel->tablename);
  }

  /**
   * Index all problems or 
   * @param unknown_type $id
   */
  public function index($id = FALSE) {
  	$this->VIEW = "problem/index.php";
  	if (FALSE !== $id) {
  		if(is_numeric($id)) {
        $this->problems = $this->problemModel->getAllWhere(array("id = $id"));
  	  } else {
        $this->VIEW = "problem/404.php";
      }
    } else {
      $this->problems = $this->problemModel->getAllWhere(array("id > 0"));
    }
    
    $this->render();
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
}

?>

