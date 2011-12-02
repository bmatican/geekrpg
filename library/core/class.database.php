<?php

class Geek_Database {
  private static $instance = NULL;

  private function __construct() {
    // maybe some default setup?
    // singleton
  }

  /**
    * We should be using POD and returning a pointer to it for use...
    * @return $this or fails 
    */
  public static function getInstance() {
    $errors = array();
    $con = NULL;
    if (!self::$instance) {
      if (!($con = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD))) {
        $errors['_database'][] = Error::databaseConnectivity(DB_DATABASE);
      }

      if (!mysql_select_db(DB_DATABASE,$con)) {
        $errors['_database'][] = Error::databaseSelection(DB_DATABASE);
      }
    }

    if (empty($errors)) {
      self::$instance = $con;
    } else {
      Geek::jsonOutput($errors);
    }

    return self::$instance;
  }

  private function __clone() {
    // singleton...
  }

}

?>
