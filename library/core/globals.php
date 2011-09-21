<?php
  require_once PATH_CORE . DS . "errors.php";
  require_once PATH_CORE . DS . "class.logger.php";

	// Will hold global accessible values throughout the application, if needed
   
   
  // DATABASE SPECIFIC
	define( 'DB_HOST', 'localhost');
	define( 'DB_USER', 'geek' );
	define( 'DB_PASSWORD', 'geekPassword' );
	define( 'DB_DATABASE', 'geekrpg');
   
  // CUSTOMIZATIONS
	define( 'MIN_LENGTH_USERNAME', 5 );
	define( 'MAX_LENGTH_USERNAME', 20 );
	define( 'MAX_LENGTH_EMAIL', 60 ); 
	define( 'MIN_LENGTH_PASSWORD', 6 );
	define( 'MAX_LENGTH_PASSWORD', 30 );

  // SITE SPECIFIC
  define( 'DEFAULT_LOGGING_LEVEL', Logger::ALL );
  define( 'DEFAULT_LOGGING_FOLDER', '/tmp/geekrpglog');

  $LOG = new Logger(DEFAULT_LOGGING_LEVEL, DEFAULT_LOGGING_FOLDER);

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
        if ( strlen($file) - 4 == strpos($file, ".php")) {
          require_once $filePath;
        }
      } 
    }
  }
?>
