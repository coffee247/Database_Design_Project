<?php
require ("dbCreds.php");
// Create connection to Oracle
$conn = oci_pconnect(constant("DBUSER"),constant("DBPASSWORD"),constant("DBHOST"));
if (!$conn) {
   $m = oci_error();
   echo $m['Failed to connect'], "\n";
   exit;
}
else {
   echo ("Connected to Footrace Database on Oracle!");
}
?>