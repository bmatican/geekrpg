<?php

  define( 'DS'        , DIRECTORY_SEPARATOR  );
  define( 'WEB_ROOT'  , dirname(__FILE__) . DS );
  define( 'HTTP_ROOT' , 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/' );

  require_once "config/config.php";
  require_once PATH_CORE . "globals.php";
  
  Geek::requireFolder( PATH_CORE );
  
  require_once PATH_TEMPLATES . PATH_CURRENT_TEMPLATE . "default.php";
  
  // Instantiate the Geek Global class so its static attributes are initialized in the constructor
  new Geek();
  
  $q = isset($_GET['q']) ? $_GET['q'] : 'home.php';
  $pathComponents = explode("/", $q);
  
  //TODO: should cache these
  //TODO: are these global?
  $controllerInstances = array();
  $enabledApplications = getEnabledApplications();
  $handlers = array();
  
  $application  = $pathComponents[0];

  foreach ($enabledApplications as $app) {
    $someHandlers = loadApplication($app, $controllerInstances);
    $handlers     = array_merge_recursive($handlers, $someHandlers);
  }

  $handlerName = Geek::getControllerName($application);
  $handlers = isset( $handlers[ $handlerName ] ) ? $handlers[ $handlerName ] : null;
  
  if( count($pathComponents) < 2 ){
    $path = $pathComponents[0];
    $controllerInstances['PageController']->render( $path );
    exit();
  }
  
  $method       = $pathComponents[1];
  
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      if( !$application || $method === null ){
        //TODO: decide here...
      } else {
        $args         = array_slice($pathComponents, 2);
        $dispatcher   = new Geek_Dispatcher($application, $method, $args, $handlers, $controllerInstances);

        //TODO: mb handle all rendering here if dispatcher fails?
        $return = $dispatcher->dispatch();
      }
/*
      $file = "views/$q";
      if( file_exists( $file ) ){
        Geek::$Template->render( WEB_ROOT . $file );
      } else {
        Geek::$Template->render( 'views' . DS . '404.php' );
      }
*/
      break;
      
    case "POST":
      $args = array();
      
      if( isset( $_POST['_argumentsOrder'] ) ){
        $order = explode(',', $_POST['_argumentsOrder']);
        foreach( $order as $v ){
          $args[] = isset( $_POST[ $v ] ) ? $_POST[ $v ] : '';
        }
      }
      
      $dispatcher = new Geek_Dispatcher( $pathComponents[0], $pathComponents[1], $args, $handlers, $controllerInstances );
      $return = $dispatcher->dispatch();
      if( $return ){
        Geek::$Template->render( WEB_ROOT . (isset($_POST['__view']) ? $_POST['__view'] : 'home.php') );
      } else {
        echo 'a';
      }
      break;
      
    default:
      break;
  }

  

  function getEnabledApplications() {
    //TODO: unhack :P ?
    $dirs = scandir(PATH_APPLICATIONS);
    return array_filter(
      $dirs, 
      function($file) {
        $filePath = PATH_APPLICATIONS . $file;
        if ("." == $file || ".." == $file) {
          return FALSE;
        }

        if (is_dir($filePath)) {
          return TRUE;
        }

        return FALSE;
      }
    );
  }

  function loadApplication($application, &$controllerInstances) {
    $pathToApplication = PATH_APPLICATIONS . $application . DS;

    if (is_dir($pathToApplication)) {
      // models first
      Geek::requireFolder($pathToApplication . "models");
      $pathToControllers = $pathToApplication . "controllers" . DS;
      foreach (glob($pathToControllers . "*controller.php") as $file) {
        require_once $file;
        $controller = substr(basename($file), strlen("class."), - strlen("controller.php"));
        $controllerName = ucfirst($controller) . "Controller";
        if (!isset($controllerInstances[$controllerName])) {
          $controllerInstance = new $controllerName();
          $controllerInstance->APPLICATION_NAME = $application;
          $controllerInstances[$controllerName] = $controllerInstance;
        } else {
          //TODO: UNHACK the error message and death...!
          Geek::$LOG->log(FATAL, "Cannot load two controllers with the same name");
        }
      }
      require_once $pathToApplication . "helpers" . DS . "class.handlers.php";
      $className = ucfirst($application) . "Handlers";
      $ApplicationHandlers = new $className();
      $handlers = $ApplicationHandlers->getHandlers();
      return $handlers;
    }
}

?>
