<?php
   
   require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'globals.php';
   
	// file to be used for general errors;
	class Error {
		private function __construct() {
			$super->__construct();
		}
    
    static function debug( $error ){
       return DEBUG ? $error : false;
    }
    
		static function usernameMinLength($username) {
			return "Username $username too short";
		}

		static function usernameMaxLength($username) {
			return "Username $username too long";
		}

		static function usernameTaken($username) {
			return "Username $username is already taken";
		}

		static function emailMaxLength($email) {
			return "Email $email too long";
		}

		static function emailInvalid($email) {
			return "Email $email is invalid";
		}

		static function passwordMinLength() {
			return "Password is too short";
		}

		static function passwordMaxLength() {
			return "Password is too long";
		}

		static function passwordInvalid() {
			return "Password must only contain characters [a-z], [A-Z], [0-9]";
		}

		static function passwordRepeatInvalid() {
			return "Password repeat does not match";
		}

		static function databaseConnectivity($database) {
			return "Could not connect to the database $database";
		}

		static function databaseSelection($database) {
			return "Failed to select database $database";
		}

    static function callerFailure($function) {
      return "Failed to call function $function";
    }
	}
?>
