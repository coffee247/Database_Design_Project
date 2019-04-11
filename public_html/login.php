<?php
  // Start and regenerate session.
  session_start();
  @session_regenerate_id(true);
  $_SESSION['sessionid'] = session_id();
?>

<form name="UserLogin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<div><?php if($message!="") { echo $message; } ?></div>
		<table border="0" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
			<tr>
			<td align="center" colspan="2">Enter Login Details</td>
			</tr>
			<tr>
			<td>
			<input type="text" name="email" placeholder="User Name" class="login-input"></td>
			</tr>
			<tr>
			<td>
			<input type="password" name="password" placeholder="Password" class="login-input"></td>
			</tr>
			<tr>
			<td align="center" colspan="2"><input type="submit" name="submit" value="Submit" class="btnSubmit"></td>
			</tr>
		</table>
</form><br>


<?php
// define variables and set to empty values
$email = $password = "";

if (isset($_POST['submit'])) {
  $email = UserLogin($_POST["email"]);
  $password = UserLogin($_POST["password"]);
}
	require("cleanInputData.php");
?>

<?php require ("connect.php");
?>

<?php
if(isset($_POST['submit']))
	$stid = oci_parse($conn, 'select email, passwd from FR_Person where loginuserid like '.$email.' AND loginpasswd like '.$password);
	echo $stid;
	oci_execute($stid);
	$ncols = oci_num_fields($stid);
	if($ncols==0) {
		$message = "Invalid Username or Password!";
	} else {
		$message = "You are successfully authenticated!";
}
?>