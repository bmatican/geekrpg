<?php

// get controller
$controller = $this->getController();

$page = "";
$page .= "<div>";

foreach ($controller->problems as $problem) {
  $page .= "<div style='color : red'>" . $problem["dateAdded"] . "</div>";
}

$page .= "</div>";

// actually echo the page
echo $page;

?>