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
  	$this->VIEW = "index.php";
  	if (FALSE !== $id) {
  		if(is_numeric($id)) {
        $this->problems = $this->problemModel->getAllWhere(array("id = $id"));
  		} else {
  			$this->VIEW = "404.php";
  		}
    } else {
      $this->problems = $this->problemModel->getAllWhere(array("id > 0"));
    }
  	
  	$this->render();
  }
  
  public function addComment($problemid) {
  	
  }
}

?>

