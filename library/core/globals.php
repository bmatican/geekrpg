<?php
  
  session_start();
  
  require_once PATH_CORE . DS . "errors.php";
  require_once PATH_CORE . DS . "class.logger.php";

  // SITE SPECIFIC
  define( 'DEFAULT_LOGGING_LEVEL', Logger::ALL );
  define( 'DEFAULT_LOGGING_FOLDER', '/tmp/geekrpglog');
  
  define( 'DELIVERY_TYPE_FULL', 1 );
  define( 'DELIVERY_TYPE_CONTENT', 2 );
  
  /**
   * A global object with static methods and attributes. It is prezent at the top of every file.
   * The file which includes it (it is meant to be loaded only via /index.php) must also
   *  instantiate a new Geek() object so the constructor is called and the attributes defined
   */
  class Geek{
    
    public static $Template;

    public static $LOG;
    
    public function __construct(){
      $template       = CURRENT_TEMPLATE;
      self::$Template = new $template();
      self::$LOG      = Logger::getInstance(DEFAULT_LOGGING_LEVEL, DEFAULT_LOGGING_FOLDER);
    }
    
    public static function setDefaults( array &$arr, array $vals ){
      foreach( $vals as $k => $v ){
        if( !isset( $arr[ $k ] ) ){
          $arr[ $k ] = $v;
        }
      }
    }
    
    public static function path( $url ){
      return HTTP_ROOT."?q=$url";
    }
    
    /**
     * Checks to see if an array is associative
     * Only validates empty or completely associative arrays
     * @param {Array} $arr 
     * @return Boolean
     */
    function is_assoc ( array $arr ) {
        return (count(array_filter(array_keys($arr),'is_string')) == count($arr));
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
	   * Escapes a string for mysql usage
	   * @param {string} $value  The value to be escaped
	   */
    public static function escape( $value ){
      return mysql_real_escape_string( $value );
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

    public static function getControllerName($application) {
      return ucfirst($application) . "Controller";
    }
	  
  }
  
?>
