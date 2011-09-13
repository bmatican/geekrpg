<?php
	require_once "globals.php";

	/**
	 * 
	 */
	function checkUsername(&$username) {
		global $MIN_LENGTH_USERNAME;
		global $MAX_LENGTH_USERNAME;
		global $errors;
		$length = strlen($username);

		if ($MIN_LENGTH_USERNAME > $length) { 
			$errors[] = "MIN_LENGTH_USERNAME";
		} else if ($MAX_LENGTH_USERNAME < $length) {
			$errors[] = "MAX_LENGTH_USERNAME";
		} else {
			// TODO: check with the DB
			// dbCheckUsername($username);
			// add to errors?
		}
	}

	$username = $_REQUEST["username"];
	$password = $_REQUEST["password"];
	$passwordRepeat = $_REQUEST["passwordRepeat"];
	$email = $_REQUEST["email"];
	$errors = array();

	checkUsername($username);

	var_export($errors);
?>
