<?php
	require_once "globals.php";
	require_once "db.php";
	require_once "login.php";

	/**
	 * Checks for username length and availability
	 * @param {String} $username
	 * @returns {Boolean}
	 */
	function checkUsername(&$username) {
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
			if (FALSE == $rows) {
				//TODO: ERROR
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
	function checkPassword(&$password) {
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
	function checkPasswordRepeat(&$password, &$passwordRepeat) {
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
	function checkEmail(&$email) {
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
	 */
	function signUp() {
		global $errors;
		$username   = $_REQUEST["username"];
		$password1  = $_REQUEST["password1"];
		$password2  = $_REQUEST["password2"];
		$email      = $_REQUEST["email"];

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

	signUp();
?>
