<?php

require_once dirname(__FILE__) . "/../../config/config.php";
require_once dirname(__FILE__) . "/../../library/core/class.geek.php";
require_once dirname(__FILE__) . "/../../library/core/class.database.php";

$db = Geek_Database::getInstance();

$query = 'SHOW TABLE STATUS FROM ' . DB_DATABASE;

$result = mysql_query($query) or die(mysql_error());
$tables = array();

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $tables[] = $row["Name"];
}

var_export($tables);

$query = '';

?>
