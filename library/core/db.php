<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "globals.php";

if (!($con = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD))) {
  $errors['_database'][] = Error::databaseConnectivity(DB_DATABASE);
  jsonOutput($errors);
}

if (!mysql_select_db(DB_DATABASE,$con)) {
  $errors['_database'][] = Error::databaseSelection(DB_DATABASE);
  jsonOutput($errors);
}

?>
