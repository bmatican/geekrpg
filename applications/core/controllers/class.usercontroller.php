<?php
/**
  * This is generally where the license goes :)
  */

class UserController extends Geek_Controller {
  public $userModel;
  public $roleModel;

  /**
   * Holds errors throughout the signup phase.
   */
  private $_errors;

  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->userModel = new UserModel();
    $this->roleModel = new RoleModel();
    $this->_errors = array();
  }

  public function login( $username = null, $password = null ){
    $user = $this->userModel->validateUser($username, $password);
    if(!empty($user)){
      $role = $this->roleModel->getRole($user[0]['roleid']);
      $_SESSION['user']['role'] = $role[0];
      $_SESSION['time']     = time();
      foreach( $user[0] as $k => $v ){
        $_SESSION['user'][ $k ] = $v;
      }
    }
    Geek::redirectBack();
  }
  
  public function logout() {
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
      $this->render( 'signup.php' );
    } else {
      $this->_checkUsername($username);
      $this->_checkPassword($password1);
      $this->_checkPasswordRepeat($password1, $password2);
      $this->_checkEmail($email);
      if (!empty($this->_errors)) {
        $this->_errors['result'] = false;
        $this->render( 'signup.php', array( '__errors' => $this->_errors ) );
      } else {
        if( !$this->userModel->insert(array(
          "username"  => $username,
          "password"  => md5( $password1 ),
          "email"     => $email
        ))) {
           $this->_errors['_database'] = Error::debug( mysql_error() );
        }
        if (empty($this->_errors)) {
          $this->login($username, $password1);
          $this->render( 'signup.php', array( 'result' => true, 'username' => $username ) );
  //        Geek::$Template->render( WEB_ROOT . $file );
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
      $this->_errors['username'] = Error::usernameMinLength($username);
      $result = false;
    } else if (MAX_LENGTH_USERNAME < $length) {
      $this->_errors['username'] = Error::usernameMaxLength($username);
      $result = false;
    } else {
      if ($this->userModel->existsUser($username)) {
        $this->_errors['username'] = Error::usernameTaken($username);
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
      $this->_errors['password'] = Error::passwordMinLength();
      $result = false;
    } else if (MAX_LENGTH_PASSWORD < $length) {
      $this->_errors['password'] = Error::passwordMaxLength();
      $result = false;
    } 
    
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
      $this->_errors['password'] = Error::passwordInvalid();
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
      $this->_errors['password1'] = Error::passwordRepeatInvalid();
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
      $this->_errors['email']  = Error::emailInvalid($email);
      $result = false;
    }
    
    return $result;
  }

  public function search($queryusers = null) {
    if (null == $queryusers) {
      $this->render("index.php"); //TODO: this is bullshit
    } else {
      //TODO: must implement form separately...!!!
      $queryusers = explode(",", $queryusers);
      
      $this->users = $this->userModel->searchUser($queryusers);
      $this->render("search.php");
    }
  }
  
  public function profile($username = null) {
    //TODO: unhack?? dunno what to put here...
    if (null == $username) {
      $this->user = $_SESSION["user"];
      $this->render("profile.php");
    } else {
      $this->user = $this->userModel->getUserInformation($username);
      if (!empty($this->user)) {
        $this->render("profile.php");
      } else {
        $this->render("404.php");
      }
    }
  }
}

?>

