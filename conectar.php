<?php
$mysqli = new mysqli("127.0.0.1", "hernan", "Quesos", "gt");
if ($mysqli === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}
$mysqli->set_charset('utf8');
?>
