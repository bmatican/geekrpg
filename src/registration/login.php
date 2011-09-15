<?php
	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "db.php";

   session_start();

   function logIn( $username, $password ){
      $query = mysql_query( "SELECT * FROM Users WHERE username='$username' AND password='$password'" );
   }
   
   logIn( $_REQUEST['username'], $_REQUEST['password'] );
?>
