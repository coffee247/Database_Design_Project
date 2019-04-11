<html>
<head>
<title>
  Identity Management, Part 1 - SignOnDB.php
</title>
<style type="text/css">
  div#centered {border:0;height:80%;top:10%;left:10%;width:90%;position:absolute;}
</style>
</head>
<body>

<?php
	require ("sessionStart.php");
?>

<div id=centered>
<form method="post" action="AddDbUser.php">
<table border="4" bgcolor="beige" bordercolor="silver" cellspacing="0">
  <tr><td align="center" width="400"><font size=+3>User Login</font></td></tr>
  <tr>
    <td>
      <table border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td align="right" width="200">User ID:</td>
          <td width="200"><input name="userid" type="text"></td>
        </tr>
        <tr>
          <td align="right">User Password:</td>
          <td><input name="passwd" type="password"></td>
        </tr>
        <tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <table border="0" cellpadding="5" cellspacing="0">
        <tr>
          <td align="center" valign="center">
            <input name="submit" type="submit" value="Login">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
</div>
</body>
</html>