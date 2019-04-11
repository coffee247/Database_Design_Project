<?php
// Declare control variable.
$valid_user = false;

// Authenticate user.
if ((isset($_SERVER['PHP_AUTH_USER'])) && (isset($_SERVER['PHP_AUTH_PW'])))
  if (verify_db_login($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']))
    $valid_user = true;
    // Check for authorized account.
function verify_db_login($userid,$passwd)
{
  // Attempt connection.
  if ($c = @oci_connect(SCHEMA,PASSWD,TNS_ID))
  {
    // Return a row.
    $s = oci_parse($c,"SELECT   NULL
                       FROM     system_user
                       WHERE    system_user_name = :userid
                       AND      system_user_password = :passwd
                       AND      SYSDATE BETWEEN start_date
                                        AND NVL(end_date,SYSDATE)");

    // Encrypt password.
    $newpassword = sha1($passwd);
    // Bind variables as strings.
    oci_bind_by_name($s,":userid",$userid);
    oci_bind_by_name($s,":passwd", $newpassword));

      // Execute the query.
      if (@oci_execute($s,OCI_DEFAULT))
      {
        // Check for a validated user, also known as a fetched row.
        if (oci_fetch($s))
           return true;
        else
          return false;
      }
      else
      {
        // Print error when execution fails.
        $errorMessage = "Check for a missing SYSTEM_USER table.<br />";
        print $errorMessage;
      }

    // Close connection.
    oci_close($c);
  }
  else
  {
    $errorMessage = oci_error();
    print htmlentities($errorMessage['message'])."<br />";
  }
}
?>