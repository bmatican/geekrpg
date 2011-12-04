<?php

  define( 'HTTP_ROOT' , 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/' );

  require_once "config/config.php";
  require_once PATH_CORE . "class.geek.php";
  
  Geek::requireFolder( PATH_CORE );
  
  require_once PATH_TEMPLATES . PATH_CURRENT_TEMPLATE . "default.php";
  
  // Instantiate the Geek Global class so its static attributes are initialized in the constructor
  new Geek();
  // setup a default Guest environment if user is not logged on
  if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = Geek::guestUser();
  }
  // set a global warning handler
  function __problemHandler($errno, $errstr) {
    $error = "<b>Problem: </b> [$errno] $errstr";
    Geek::$LOG->log(Logger::WARN, $error);
    Geek::ERROR('500', array($error));
  }
  set_error_handler('__problemHandler', E_WARNING | E_ERROR);
  
  $q = isset($_GET['q']) ? $_GET['q'] : 'home';
  $pathComponents = explode("/", $q);
  
  //TODO: should cache these
  //TODO: are these global?
  $controllerInstances = array();
  $enabledApplications = getEnabledApplications();
  $handlers = array();
  $methods = array();
  
  $application  = $pathComponents[0];

  foreach ($enabledApplications as $app) {
    $someHandlers = array();
    $newMethods = array();
    loadApplication($app, $controllerInstances, $someHandlers, $newMethods);
    $handlers = array_merge_recursive($handlers, $someHandlers);
    $methods = array_merge_recursive($methods, $newMethods);
  }

  $handlerName = Geek::getControllerName($application);
  $handlers = isset( $handlers[ $handlerName ] ) ? $handlers[ $handlerName ] : null;
  $methods = isset( $methods[ $handlerName ] ) ? $handlers[ $handlerName ] : null;
  
  if( count($pathComponents) < 2 ){
    $path = $pathComponents[0];
    $controllerInstances['PageController']->render( $path );
    exit();
  } else if( !$pathComponents[1] ){
    $pathComponents[1] = 'index';
  }
  
  $method = $pathComponents[1];
  
  if( !$application || $method === null ){
    //TODO: decide here...
  } else {
    $args         = array_map( "Geek::escape", array_slice($pathComponents, 2) ) ;
    $dispatcher   = new Geek_Dispatcher($application, $method, $args, $handlers, $methods, $controllerInstances);

    //TODO: mb handle all rendering here if dispatcher fails?
    $return = $dispatcher->dispatch();
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

  function loadApplication($application, &$controllerInstances, &$handlers, &$newmethods) {
    $pathToApplication = PATH_APPLICATIONS . $application . DS;

    if (is_dir($pathToApplication)) {
      // models first
      Geek::requireFolder($pathToApplication . "models");
      $pathToControllers = $pathToApplication . "controllers" . DS;
      foreach (glob($pathToControllers . "controller.*.php") as $file) {
        require_once $file;
        $controller = substr(basename($file), strlen("controller."), - strlen(".php"));
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
      Geek::requireFolder($pathToApplication . "helpers");
      $className = ucfirst($application) . "Handlers";
      $ApplicationHandlers = new $className();
      $handlers = $ApplicationHandlers->getHandlers();
      $newmethods = $ApplicationHandlers->getMethods();
    }
}

?>
