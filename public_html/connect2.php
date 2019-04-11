<!DOCTYPE html>
<?php
  session_start();
  @session_regenerate_id(true);
  $_SESSION['sessionid'] = session_id();
?>
<html>
<head>
	<title>Test</title>
</head>
<body>
<?php
  require ("connect.php");
  require ("loginform.php");
  ?>
  </body>
  </html>  

