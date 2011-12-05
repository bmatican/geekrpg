<?php
  
  session_start();
  
  require_once PATH_CORE . DS . "class.logger.php";
 
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

    public static function dump( $val ){
      echo '<pre>';
      var_dump( $val );
      echo '</pre>';
    }
    public static function export( $val ){
      echo '<pre>';
      var_export( $val );
      echo '</pre>';
    }
    
    public static function setDefaults( array &$arr, array $vals ){
      foreach( $vals as $k => $v ){
        if( !isset( $arr[ $k ] ) ){
          $arr[ $k ] = $v;
        }
      }
    }

    /**
     * Checks if a user is logged in or not.
     *
     * @return true if the user is logged in, false otherwise.
     */
    public static function isOnline() {
      return $_SESSION['user']['role']['p_loggedin'];
    }

    /**
     * Provides a global way of creating a guest user instance.
     *
     * @return an array with guest user priviliges
     */
    public static function guestUser() {
      return array(
        'id'        => 0,
        'username'  => 'Guest',
        'email'     => null,
        'roleid'    => ROLE_GUEST,
        'role'      => array(
          'p_loggedin' => 0
        )
      );
    }

    /**
     * Checks if a user has a certain persmission. 
     *
     * @param $name the permission name
     * @return if the permission exists and is set to 1.
     */
    public static function checkPermission($name) {
      if (!isset($_SESSION['user']['role'][$name])
        || 1 != $_SESSION['user']['role'][$name]) {
        return false;
      } else {
        return true;
      }
    }

    /**
     *  Renders an error given the name of the view: e.g. 404, 500.
     *
     * @param $view the view name 
     * @param $args the view arguments
     */
    public static function ERROR( $view, array $args = array() ){
      self::$Template->render( self::getErrorView( $view , $args ) );
    }
    
    /**
     * Generates a view object for future rendering.
     *
     * @param $view the view name
     * @param $path the view path, if desired to not be from library
     * @param $viewArgs the view arguments
     */
    public static function getView($view, $path = null, $viewArgs = array()) {
      $view = strtolower($view);
      if( !$path ){
        $path = PATH_CORE . 'views' . DS;
      } else {
        $path = PATH_APPLICATIONS . $path . DS;
      }

      $viewPath = $path . "view." . $view . ".php";
      if( file_exists( $viewPath ) ){
        require_once($viewPath);
        $view = new $view( $viewArgs );
        return $view;
      } else {
        return null;
      }
    }

    public static function getErrorView( $view, $viewArgs = array() ){
      $view = strtolower($view);
      $view     = 'error_'.$view;
      $path     = PATH_CORE . 'views' . DS . 'errors' . DS;
      $viewPath = $path . "view." . $view . ".php";
      if( file_exists( $viewPath ) ){
        require_once($viewPath);
        $view = new $view( $viewArgs );
        return $view;
      } else {
        return self::getErrorView( '404', $viewArgs );
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
      return htmlspecialchars(mysql_real_escape_string( $value ));
    }
    
    /**
     * Escapes a string for mysql usage
     * @param {array} $value  The value to be escaped
     */
    public static function escapeArray( array $arr ){
      $newValue = array();
      foreach ($arr as $key => $value) {
        $newValue[ Geek::escape($key) ] = Geek::escape($value);
      }
      return $newValue;
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

      $stack = array();
      
      foreach ($files as $file) {
        $filePath = $folder . DS . $file;
        if (is_dir($filePath) 
            && !is_link($filePath) 
            && $file !== "." 
            && $file !== "..") {
              $stack[] = $folder . DS . $file;
        } elseif (is_file($filePath)) {
          if ( strlen($file) - 4 == strpos($file, ".php")) {
            require_once $filePath;
          }
        }
      }
      
      foreach( $stack as $f ){
        Geek::requireFolder( $f );
      }
      
    }

    public static function getControllerName($application) {
      return ucfirst($application) . "Controller";
    }
	  
    public static function redirect($url) {
      header('Location:' . $url );
    }

    public static function redirectBack() {
      Geek::redirect($_SERVER['HTTP_REFERER']);
    }
  }
  
?>
