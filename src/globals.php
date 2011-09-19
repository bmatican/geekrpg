<?php
	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "errors.php";
  require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "logger.php";

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
  define( 'DEFAULT_LOGGING_FOLDER', '/tmp/geekrpglog');

  // global Logger
  $LOG = new Logger(DEFAULT_LOGGING_LEVEL, DEFAULT_LOGGING_FOLDER);

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
    * @param $folder the target folder in absolute path
    */
  function requireFolder($folder) {
    $files = scandir($folder);
    if (FALSE === $files) {
      return;
    }

    foreach ($files as $file) {
      $filePath = $folder . DIRECTORY_SEPARATOR . $file;
      if (is_dir($filePath) 
          && !is_link($filePath) 
          && $file !== "." 
          && $file !== "..") {
        requireFolder($folder . DIRECTORY_SEPARATOR . $file);
      } elseif (is_file($filePath)) {
        if (FALSE !== strpos($file, ".php")) {
          require_once $filePath;
        }
      } 
    }
  }
?>
