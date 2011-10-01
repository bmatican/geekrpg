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
    $this->solutionModel = new SolutionModel();
    $this->solutionCommentModel = new CommentModel($this->solutionModel->tablename);
  }
  
  public function test() {
  	echo "here";
  }
}

?>

