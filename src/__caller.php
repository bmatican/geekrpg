<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "globals.php";

$LOG->log(Logger::WARN, "trying to call");

$namespace = $_REQUEST['namespace'];
$function = $_REQUEST['function'];
$args = json_decode(stripslashes($_REQUEST['args']), TRUE);

requireFolder(dirname(__FILE__) . DIRECTORY_SEPARATOR . $namespace);

try {
  $result = call_user_func($function, $args);
  $LOG->log(Logger::DEBUG, "managed to call");
} catch (Exception $e) {
  $LOG->log(Logger::ERROR, "failed to call");
  jsonOutput(array("_exception" => $e));
}

if (FALSE === $result) {
  $errors["_caller"][] = Error::callerFailure($function);
  jsonOutput($errors);
}

?>
