<?php
// Always start this first
  session_start();
@session_regenerate_id(true);
  $_SESSION['sessionid'] = session_id();

if ( ! empty( $_POST ) ) {
    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) ) {
        // Getting submitted user data from database
        require ("connect.php");
        $stmt = oci_parse($conn, 'select * from fr_person');
        $stmt->bind_param('s', $_POST['username']);
		oci_execute($stid);
        $result = $stmt->get_result();
    	$user = $result->fetch_object();
    		
    	// Verify user password and set $_SESSION
    	if ( password_verify( $_POST['password'], $user->loginpasswd ) ) {
    		$_SESSION['user_id'] = $user->PID;
    	}
    }
}
?>

<form action="" method="post">
    <input type="text" name="username" placeholder="Enter your email address" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <input type="submit" value="Submit">
</form>
