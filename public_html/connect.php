<?php
// Create connection to Oracle
$conn = oci_connect("V00859712", "V00859712", "//localhost");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
  // print "Connected to Oracle!";
}
?>