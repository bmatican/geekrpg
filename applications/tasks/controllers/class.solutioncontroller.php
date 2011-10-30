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
  
  public function index($problemid = FALSE, $solutionid = FALSE) {
    //TODO: remove this...it's for testing
    $_SESSION['userid'] = 0;
    
    //TODO: PASS PARAMETERS FOR VIEWS ...need to know what to render
    $this->solutions = array(); 
    if (FALSE === $problemid) {
      //TODO: display all of your proposed/accepted solutions to each set of problems
      $this->solutions = $this->solutionModel->getAllWhere(array('userid = ' . $_SESSION['userid'] ));
      
      $this->render('solution/index.php');
    } else {
      if (FALSE === $solutionid) {
        //TODO: display the solutions posted for this problem if you have rights to see it
        $this->solutions = $this->solutionModel->getAllSolutions($problemid, $_SESSION['userid']);  

        $this->render('solution/index.php');
      } else {
        //TODO: display the solution for this problem, if you have rights to see it
        $solution = $this->solutionModel->getById($solutionid);
        if (!isset($solution) 
          || $solution['userid'] != $_SESSION['userid'] 
          || $solution['problemid'] != $problemid) {
            
          $this->render('solution/404.php');
        } else {
          //TODO: ....think of how you need the data in the view
          $this->solutions = array($solution);
          $this->render('solution/index.php');
        }
      }
            
    }
  }
}

?>

