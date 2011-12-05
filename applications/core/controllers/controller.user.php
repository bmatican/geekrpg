<?php
/**
  * This is generally where the license goes :)
  */

class UserController extends Geek_Controller {
  public $userModel;
  public $roleModel;
  
  private $ERROR_SIGNUP_EMAIL;
  private $ERROR_SIGNUP_PASSWORD;
  private $ERROR_SIGNUP_PASSWORD2;
  private $ERROR_SIGNUP_USERNAME;
  private $ERROR_SIGNUP_USERNAME2;

  /**
   * Holds errors throughout the signup phase.
   */
  private $_errors;

  /**
    * Default constructor.
    */
  public function __construct() {
    $this->ERROR_SIGNUP_EMAIL = "Email must be valid and less than: "
    . MAX_LENGTH_EMAIL
    . " characters";
    $this->ERROR_SIGNUP_USERNAME = "Username must be between " 
      . MIN_LENGTH_USERNAME 
      . " and " 
      . MAX_LENGTH_USERNAME
      . " characters";
    $this->ERROR_SIGNUP_USERNAME2 = "Username is alread taken: ";
    $this->ERROR_SIGNUP_PASSWORD = "Password must only contain [a-zA-Z0-9] characters and must be between " 
      . MIN_LENGTH_PASSWORD 
      . " and " 
      . MAX_LENGTH_PASSWORD
      . " characters";
    $this->ERROR_SIGNUP_PASSWORD2 = "Passwords must match!";
    parent::__construct();
    $this->userModel = new UserModel();
    $this->roleModel = new RoleModel();
    $this->_errors = array();
  }

  public function login( $username = null, $password = null, $remember = null, $redirect = true ){
    $user = $this->userModel->validateUser($username, $password);
    if( !empty($user) ){
      $role = $this->roleModel->getRole($user[0]['roleid']);
      $_SESSION['user']['role'] = $role[0];
      $_SESSION['time']     = time();
      foreach( $user[0] as $k => $v ){
        $_SESSION['user'][ $k ] = $v;
      }
    }
    if( $redirect ){
      Geek::redirectBack();
    }
  }
  
  public function logout() {
    unset( $_SESSION );
    session_destroy();
    Geek::redirectBack();
  }

  /**
   * Tries to sign up the user; prints out errors in json format in case of failures
   * 
   * @param $username
   * @param $password1
   * @param $password2
   * @param $email
   */
  public function signup($username = null , $password1 = null, $password2 = null, $email = null) {
    if( $username === null ){
      $this->render( 'SignUp' );
    } else {
      $this->_checkUsername($username);
      $this->_checkPassword($password1);
      $this->_checkPasswordRepeat($password1, $password2);
      $this->_checkEmail($email);
      if (!empty($this->_errors)) {
        $this->_errors['result'] = false;
        $this->render( 'SignUp', array( '__errors' => $this->_errors ) );
      } else {
        $result = $this->userModel->insert(array(
          "username"  => $username,
          "password"  => md5( $password1 ),
          "email"     => $email
        ));
        if( !$result ) {
           $this->_errors['_database'] = $result;
        }
        if (empty($this->_errors)) {
          $this->login($username, $password1, null, false);
          $this->render( 'SignUp', array( 'result' => true, 'username' => $username ) );
          return true;
        } else {
          return false;
        }
      }
    }
  }
  
  /**
   * Checks for username length and availability
   * @param {String} $username
   * @returns {Boolean}
   */
  private function _checkUsername(&$username) {
    $length = strlen($username);
      $result = true;

    if (MIN_LENGTH_USERNAME > $length) { 
      $this->_errors['username'] = $this->ERROR_SIGNUP_USERNAME;
      $result = false;
    } else if (MAX_LENGTH_USERNAME < $length) {
      $this->_errors['username'] = $this->ERROR_SIGNUP_USERNAME;
      $result = false;
    } else {
      if ($this->userModel->existsUser($username)) {
        $this->_errors['username'] = $this->ERROR_SIGNUP_USERNAME2 . $username;
        $result = false;
      } 
    }
    
    return $result;
  }

  /**
   * Check if the password is of proper length and containing proper chars
   * @param {String} $password
   * @returns {Boolean}
   */
  private function _checkPassword(&$password) {
    $length = strlen($password);
    $result = true;
    
    if (MIN_LENGTH_PASSWORD > $length) {
      $this->_errors['password1'] = $this->ERROR_SIGNUP_PASSWORD;
      $result = false;
    } else if (MAX_LENGTH_PASSWORD < $length) {
      $this->_errors['password1'] = $this->ERROR_SIGNUP_PASSWORD;
      $result = false;
    } 
    
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
      $this->_errors['password1'] = $this->ERROR_SIGNUP_PASSWORD;
      $result = false;
    }
    return $result;
  }

  /**
   * Check if the passwords enters were identical
   */
  private function _checkPasswordRepeat(&$password, &$passwordRepeat) {
    $result = true;
    
    if ($password != $passwordRepeat) {
      $this->_errors['password2'] = $this->ERROR_SIGNUP_PASSWORD2;
      $result = false;
    }
    
    return $result;
  }

  /**
   * Checks if the email entered appears valid
   */
  private function _checkEmail(&$email) {
    $result = true;
    
    if (0 == preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$/", $email)) {
      $this->_errors['email']  = $this->ERROR_SIGNUP_EMAIL;
      $result = false;
    }
    
    return $result;
  }

  public function index() {
    $users = $this->userModel->getAllWhere( array('id>=0') );
    $this->render('index', array( 'users' => $users ));
  }
  
  public function search( $query = null ) {
    if ( null == $query ) {
      $this->render('index');
    } else {
      $query = explode(",", $query);
      
      $users = $this->userModel->searchUsers( $query );
      $this->render('index', array( 'users' => $users ));
    }
  }

  public function delete( $id ){
    $this->userModel->removeById( $id );
    $this->render();
  }
  
  public function profile($username = null) {
    //TODO: unhack?? dunno what to put here...
    if (! $username) {
      $user = $_SESSION["user"];
      $this->render('profile', $user);
    } else {
      $user = $this->userModel->getUserInformation($username);
      if (!empty($user)) {
        $this->render('profile', $user[0]);
      } else {
        $this->renderErrorView( '404' );
      }
    }
  }
}

?>

