<?php
  
  require_once PATH_CORE . DS . "errors.php";
  require_once PATH_CORE . DS . "class.logger.php";

  // SITE SPECIFIC
  define( 'DEFAULT_LOGGING_LEVEL', Logger::ALL );
  define( 'DEFAULT_LOGGING_FOLDER', '/tmp/geekrpglog');

  class Geek{
    
    public static function Logger(){
      return Logger::getInstance(DEFAULT_LOGGING_LEVEL, DEFAULT_LOGGING_FOLDER);
    }
    
    public static function Template(){
      return call_user_func(CURRENT_TEMPLATE.'::getInstance');
    }

    /**
     * Exits the current script by setting headers for json output and printing
     * the appropriate errors.
     */    
    public static function jsonOutput( $errors ) {
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
    public static function requireFolder( $folder ) {
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
	  
  }

?>
