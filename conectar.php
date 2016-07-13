<?php
$mysqli = new mysqli("localhost", "hbraile", "Quesos", "develgt");
if ($mysqli === false) {
  die("ERROR: Could not connect. " . mysqli_connect_error());
}
$mysqli->set_charset('utf8');
?>
