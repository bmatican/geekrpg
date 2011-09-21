<?php
/**
  * This is generally where the license goes :)
  */

class RegistrationController extends Geek_Controller {
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
  }

  public function logIn( $username, $password ){
    session_start();
    $query = mysql_query( "SELECT * FROM Users WHERE username='$username' AND password='$password'" );
  }
  
  public functiong logOut() {
   session_destroy();
  }
  
  /**
   * Checks for username length and availability
   * @param {String} $username
   * @returns {Boolean}
   */
  private function checkUsername(&$username) {
    global $errors;
    $length = strlen($username);
      $result = true;

    if (MIN_LENGTH_USERNAME > $length) { 
      $errors['username'][] = Error::usernameMinLength($username);
      $result = false;
    } else if (MAX_LENGTH_USERNAME < $length) {
      $errors['username'][] = Error::usernameMaxLength($username);
      $result = false;
    } else {
      $query = "SELECT * FROM Users WHERE username='$username'";
      $rows = mysql_query($query);
      if (FALSE === $rows) {
        //TODO: error?
        $result = false;
      } else {
        if (0 < mysql_num_rows($rows)) {
          $errors['username'][] = Error::usernameTaken($username);
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
    global $errors;
    $length = strlen($password);
    $result = true;
    
    if (MIN_LENGTH_PASSWORD > $length) {
      $errors['password'][] = Error::passwordMinLength();
      $result = false;
    } else if (MAX_LENGTH_PASSWORD < $length) {
      $errors['password'][] = Error::passwordMaxLength();
      $result = false;
    } 
    
    if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
      $errors['password'][] = Error::passwordInvalid();
      $result = false;
    }
    
    return $result;
  }

  /**
   * Check if the passwords enters were identical
   */
  private function checkPasswordRepeat(&$password, &$passwordRepeat) {
    global $errors;
    $result = true;
    
    if ($password != $passwordRepeat) {
      $errors['passwordRepeat'][] = Error::passwordRepeatInvalid();
      $result = false;
    }
    
    return $result;
  }

  /**
   * Checks if the email entered appears valid
   */
  private function checkEmail(&$email) {
    global $errors;
    $result = true;
    
    if (0 == preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$/", $email)) {
      $errors['email'][]  = Error::emailInvalid($email);
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
  public function signUp($username , $password1, $password2, $email) {
    global $errors;

    $LOG->log(Logger::FATAL, "in signup");

    checkUsername($username);
    checkPassword($password1);
    checkPasswordRepeat($password1, $password2);
    checkEmail($email);

    if (!empty($errors)) {
      jsonOutput($errors);
    } else {
       $password = md5($password1);
       if( !mysql_query("INSERT INTO Users(username, password, email) VALUES ('$username', '$password', '$email')") ){
          $errors['_database'][] = Error::debug( mysql_error() );
       }
       jsonOutput( $errors );
    }
  }
}

?>

