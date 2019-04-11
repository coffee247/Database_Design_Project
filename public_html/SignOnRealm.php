<html>
<! SignOnRealm.php                                            >
<! OTN PHP Identity Management, Part 1                        >
<! by Michael McLaughlin                                      >
<!  adapted by James Stallings and Daniel Macnamara           >
<! This demonstrates a basic HTTP authentication against an   >
<! Oracle 10g database.                                       >
<head>
<title>
  SignOn page
</title>
<style type="text/css">
  div#centered {border:0;height:80%;top:10%;left:10%;width:80%;
                position:absolute;}
</style>
</head>
<body>
<?php
  // Declare control variable.
  $valid_user = false;

  // Set database credentials.
  include_once("Credentials1.inc");

  // Authenticate user..
  if ((isset($_SERVER['PHP_AUTH_USER'])) && (isset($_SERVER['PHP_AUTH_PW'])))
    if (verify_db_login($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']))
      $valid_user = true;

  // When credentials are invalid prompt for them.
  if (!$valid_user)
  {
    // Send headers to force basic HTTP authentication.
    header('WWW-Authenticate: Basic Realm="http://128.172.188.107"');
    header('HTTP/1.0 401 Unauthorized');

    // Print failed validation message after 3 attempts.
    print "<html><body><font size=+2>";
    print "Three strikes, you're out!";
    print "</font></body></html>";
    exit;
  }
  else
  {
    // Provide an entry form.
    displayForm();
  }

  // ----------------------------------------------------------------

  // Check for authorized account.
  function verify_db_login($userid,$passwd)
  {
    // Attempt connection and evaluate password.
    if ($c = @oci_connect(SCHEMA,PASSWD,TNS_ID))
    {
      // Return database UID.
      $s = oci_parse($c,"SELECT   NULL
                         FROM     FR_PERSON
                         WHERE    LOGINUSERID = :userid
                         AND      LOGINPASSWD = :passwd");

      // Encrypt password.
      $newpassword = sha1($passwd);

      // Bind the variables as strings.
      oci_bind_by_name($s,":userid",$userid);
      oci_bind_by_name($s,":passwd",$newpassword);


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
        // Print error when oci_execute() fails.
        $errorMessage = "Check for a missing SYSTEM_USER table.<br />";
        print $errorMessage;
      }

      // Close the connection.
      oci_close($c);
    }
    else
    {
      $errorMessage = oci_error();
      print htmlentities($errorMessage['message'])."<br />";
    }
  }

  // ----------------------------------------------------------------

  // Build dynamic data entry form.
  function displayForm()
  {
    // Initialize return variable.
    $out  = '<div id=centered>';
    $out .= '<form method="post" action="SignOnRealm.php">';
    $out .= '<table border="4"
                    bgcolor="beige"
                    bordercolor="silver"
                    cellspacing="0">';
    $out .= '<tr><td align="center" width="400">';
    $out .= '<font size=+3>Sign-on Verified</font>';
    $out .= '</td></tr>';
    $out .= '<tr><td>';
    $out .= '<table border="0" cellpadding="5" cellspacing="0">';
    $out .= '<tr>';
    $out .= '<td align="right" width="200">User ID:</td>';
    $out .= '<td width="200">';
    $out .= '<input name="newuserid"
                    type="text" value="'.$_SERVER['PHP_AUTH_USER'].'">';
    $out .= '</td>';
    $out .= '</tr>';
    $out .= '<tr>';
    $out .= '<td align="right">User Plain Text Password:</td>';
    $out .= '<td><input name="newpasswd"
                        type="text" value="'.$_SERVER['PHP_AUTH_PW'].'">';
    $out .= '</td>';
    $out .= '</tr>';
    $out .= '<tr>';
    $out .= '<td align="right">User Encrypted Text Password:</td>';
    $out .= '<td><input name="newpasswd"
                        type="text" value="'.sha1($_SERVER['PHP_AUTH_PW']).'">';
    $out .= '</td>';
    $out .= '</tr>';
    $out .= '<tr>';
    $out .= '</table>';
    $out .= '</td></tr>';
    $out .= '</table>';
    $out .= '</form>';
    $out .= '</div>';

    // Return the form for rendering in a web page.
    print $out;
  }
?>
</body>
</html>
