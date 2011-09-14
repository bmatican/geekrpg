<?php
	require_once "errors.php";

	// Will hold global accessible values throughout the application, if needed
   
   define( 'DEBUG', true );                     // debug mode
   
	define( 'DB_HOST', 'localhost');
	define( 'DB_USER', 'geek' );
	define( 'DB_PASSWORD', 'geekPassword' );
	define( 'DB_DATABASE', 'geekrpg');
   
	define( 'MIN_LENGTH_USERNAME', 5 );
	define( 'MAX_LENGTH_USERNAME', 20 );
	define( 'MAX_LENGTH_EMAIL', 60 ); 
	define( 'MIN_LENGTH_PASSWORD', 6 );
	define( 'MAX_LENGTH_PASSWORD', 30 );

	/**
	 * The errors to report in case of problems
	 */
  $errors = array();

	function jsonOutput($errors) {
		if (!headers_sent()) {
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
		}

		exit(json_encode($errors));
	}
?>
