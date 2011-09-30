<?php
/**
  * This is generally where the license goes :)
  */

class RegistrationController extends Geek_Controller {
  /**
    * For future use...when we switch to models and stuff...
    */
  private $_database;

  private $_errors;

  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->_database = Geek_Database::getInstance();
    $this->_errors = array();
  }

  public function login( $username, $password ){
    $rows = mysql_query( "SELECT * FROM Users WHERE username='$username' AND password='" . md5($password) . "'");
  }
  
  public function logout() {
   session_destroy();
  }
  
  /**
   * Checks for username length and availability
   * @param {String} $username
   * @returns {Boolean}
   */
  private function checkUsername(&$username) {
    $length = strlen($username);
      $result = true;

    if (MIN_LENGTH_USERNAME > $length) { 
      $this->_errors['username'][] = Error::usernameMinLength($username);
      $result = false;
    } else if (MAX_LENGTH_USERNAME < $length) {
      $this->_errors['username'][] = Error::usernameMaxLength($username);
      $result = false;
    } else {
      $query = "SELECT * FROM Users WHERE username='$username'";
      $rows = mysql_query($query);
      if (FALSE === $rows) {
        //TODO: error?
        $result = false;
      } else {
        if (0 < mysql_num_rows($rows)) {
          $this->_errors['username'][] = Error::usernameTaken($username);
          $result = false;
        }
      }
    }
    
    return $result;
  }

  /**
   * Check if the password is of proper length and containing proper chars
   * @param {String} $password
   * @returns {Boolean}
   */
  private function checkPassword(&$password) {
    $length = strlen($password);
    $result = true;
    
    if (MIN_LENGTH_PASSWORD > $length) {
      $this->_errors['password'][] = Error::passwordMinLength();
      $result = false;
    } else if (MAX_LENGTH_PASSWORD < $length) {
      $this->_errors['password'][] = Error::passwordMaxLength();
      $result = false;
    } 
    
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
      $this->_errors['password'][] = Error::passwordInvalid();
      $result = false;
    }
    
    return $result;
  }

  /**
   * Check if the passwords enters were identical
   */
  private function checkPasswordRepeat(&$password, &$passwordRepeat) {
    $result = true;
    
    if ($password != $passwordRepeat) {
      $this->_errors['passwordRepeat'][] = Error::passwordRepeatInvalid();
      $result = false;
    }
    
    return $result;
  }

  /**
   * Checks if the email entered appears valid
   */
  private function checkEmail(&$email) {
    $result = true;
    
    if (0 == preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$/", $email)) {
      $this->_errors['email'][]  = Error::emailInvalid($email);
      $result = false;
    }
    
    return $result;
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
      $this->checkUsername($username);
      $this->checkPassword($password1);
      $this->checkPasswordRepeat($password1, $password2);
      $this->checkEmail($email);

      if (!empty($this->_errors)) {
        $this->_errors['result'] = false;
        $this->render( 'signup.php', $this->_errors );
//        Geek::jsonOutput($this->_errors);
      } else {
        $password = md5($password1);
        $query = "INSERT INTO Users(username, password, email) "
          . " VALUES ('" 
          . mysql_real_escape_string($username)
          . "', '"
          . mysql_real_escape_string($password)
          . "', '"
          . mysql_real_escape_string($email)
          . "')";
        if( !mysql_query($query)) {
           $this->_errors['_database'][] = Error::debug( mysql_error() );
        }

        if (empty($this->_errors)) {
          $this->login($username, $password1);
          $this->render( 'signup.php', array( 'result' => true, 'username' => $username ) );
  //        Geek::$Template->render( WEB_ROOT . $file );
          return true;
        } else {
          //Geek::jsonOutput( $this->_errors );
          return false;
        }
      }
    }
  }
}

?>

