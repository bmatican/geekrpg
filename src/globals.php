<?php
	require_once "errors.php";
  require_once "logger.php";

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

  define( 'DEFAULT_LOGGING_LEVEL', Logger::ALL );

	/**
	 * The errors to report in case of problems
	 */
  $errors = array();

  /**
    * Exits the current script by setting headers for json output and printing
    * the appropriate errors.
    */
	function jsonOutput($errors) {
		if (!headers_sent()) {
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
		}

		exit(json_encode($errors));
	}

  /**
    * Calls require_once on all the php files inside a folder.
    * @param $folder the target folder
    */
  function requireFolder($folder) {
    $files = scandir($folder);
    if (FALSE == $files) {
      //TODO: log something?
      return;
    }

    foreach ($files as $file) {
      if (FALSE !== strpos($file, ".php")) {
        require_once "$folder/$file";
      }
    }
  }
?>
