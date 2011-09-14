<?php
  require_once "globals.php";

  $namespace = $_REQUEST['namespace'];
  $function = $_REQUEST['function'];
  $params = json_decode(stripslashes($_REQUEST['args']), TRUE);

  requireFolder($namespace);
  $result = call_user_func($function, $params);
  //TODO: check result?
?>
