<?php

  define( 'WEB_ROOT'  , dirname(__FILE__));
  define( 'HTTP_ROOT' , 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/' );

  require_once "config/config.php";
  require_once PATH_CORE . "globals.php";
  
  Geek::requireFolder( PATH_CORE );
  
  require_once PATH_TEMPLATES . PATH_CURRENT_TEMPLATE . "default.php";
  
  // Instantiate the Geek Global class so its static attributes are initialized in the constructor
  new Geek();
  
  $q = isset($_GET['q']) ? $_GET['q'] : 'home.php';

  $pathComponents = explode("/", $q);
  if( count($pathComponents) < 2 ){
    
  } else {
    $application  = $pathComponents[0];
    $method       = $pathComponents[1];
    $args         = array_slice($pathComponents, 2);
    $dispatcher   = new Geek_Dispatcher();
    
    $dispatcher->dispatch($application, $method, $args);
  }
  
  $file = "views/$q";
  if( file_exists( $file ) ){
    Geek::$Template->render( WEB_ROOT . DS . $file );
  } else {
    Geek::$Template->render( 'views/404.php' );
  }
?>
