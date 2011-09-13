<?php
	require_once "globals.php";
	require_once "errors.php";
	require_once "db.php";
	require_once "login.php";

	/**
	 * Checks for username length and availability
	 */
	function checkUsername(&$username) {
		global $MIN_LENGTH_USERNAME;
		global $MAX_LENGTH_USERNAME;
		global $errors;
		$length = strlen($username);

		if (MIN_LENGTH_USERNAME > $length) { 
			$errors['username'][] = Error::usernameMinLength($username);
		} else if (MAX_LENGTH_USERNAME < $length) {
			$errors['username'][] = Error::usernameMaxLength($username);
		} else {
			$query = "SELECT * FROM Users WHERE username='$username'";
			$rows = mysql_query($query);
			if (FALSE == $rows) {
				//TODO: ERROR
			} else {
				if (0 < mysql_num_rows($rows)) {
					$errors['username'][] = Error::usernameTaken($username);
				}
			}
		}
	}

	/**
	 * Check if the password is of proper length and containing proper chars
	 */
	function checkPassword(&$password) {
		global $errors;
		$length = strlen($password);
		if (MIN_LENGTH_PASSWORD > $length) {
			$errors['password'][] = Error::passwordMinLength();
		} else if (MAX_LENGTH_PASSWORD < $length) {
			$errors['password'][] = Error::passwordMaxLength();
		} 
		
		if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
			$errors['password'][] = Error::passwordInvalid();
		}
	}

	/**
	 * Check if the passwords enters were identical
	 */
	function checkPasswordRepeat(&$password, &$passwordRepeat) {
		global $errors;
		if ($password != $passwordRepeat) {
			$errors['passwordRepeat'][] = Error::passwordRepeatInvalid();
		}
	}

	/**
	 * Checks if the email entered appears valid
	 */
	function checkEmail(&$email) {
		global $errors;
		if (0 == preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]$/", $email)) {
			$errors['email'][]  = Error::emailInvalid($email);
		}
	}

	/**
	 * Tries to sign up the user; prints out errors in json format in case of failures
	 */
	function signUp() {
		global $errors;
		$username = $_REQUEST["username"];
		$password = $_REQUEST["password"];
		$passwordRepeat = $_REQUEST["passwordRepeat"];
		$email = $_REQUEST["email"];

		checkUsername($username);
		checkPassword($password);
		checkPasswordRepeat($password, $passwordRepeat);
		checkEmail($email);

		if (!empty($errors)) {
			jsonOutput($errors);
		} else {
			//TODO: signIn();
		}
	}

	signUp();
?>
