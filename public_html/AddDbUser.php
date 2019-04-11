<!-- AddDbUser.php
adapted from tutorial by 
Michael McLaughlin  found at the following link
 https://www.oracle.com/technetwork/articles/dsl/mclaughlin-phpid1-091467.html 
-->   

<html>
              
<head>
<title>
AddDBUser.php
</title>
<style type="text/css">
  div#centered {border:0;height:80%;top:10%;left:10%;width:90%;position:absolute;}
</style>
</head>
<body>
<?php
  // Start session.
  session_start();
  $_SESSION['sessionid'] = session_id();

  // Set database credentials.
  include_once("Credentials1.inc");

  // Set control variable.
  $authenticated = false;

  // Define global constants for error management.
  define('USER_VALID',0);
  define('USER_EXISTS',1);
  define('USER_LENGTH_ZERO',2);
  define('USER_STARTS_WITH_NUMBER',3);
  define('USER_LENGTH_OUTSIDE_RANGE',4);
  define('USER_PASSWORD_LENGTH',5);


  // Define global constants for database integrity.
  define('DATABASE_VALID',0);
  define('DATABASE_INVALID',1);

  // Set assumed database integrity level.
  $_SESSION['db_error'] = DATABASE_VALID;

  // Assign initial credentials to local variables.
  $userid = @$_POST['userid'];
  $passwd = @$_POST['passwd'];

  // Check for valid session and regenerate when session is invalid:
  // ----------------------------------------------------------------
  //  Rule #1: The session is not registered in the database; or
  //  Rule #2: The last user and current user are the same; and
  //  Rule #3: The user is validating initial login.
  // -----------------------------------------------------------------
  if ((get_session($_SESSION['sessionid'],$userid,$passwd) == 0) ||
      (($_SESSION['userid'] != $userid) && ($userid)))
  {
    // Regenerate session ID.
    session_regenerate_id(true);
    $_SESSION['sessionid'] = session_id();
  }
  else
  {
    $authenticated = true;
  }

  // Check whether the program should:
  // -----------------------------------------------------------------
  //  Action #1: Verify new credentials and start a database session.
  //  Action #2: Continue a session on refresh button.
  //  Action #3: Provide a new form after adding a user.
  //  Action #4: Provide a new form after failing to add a user.
  // -----------------------------------------------------------------
  if (($authenticated) || (authenticate($userid,$passwd)))
  {
    // Assign inputs to variables.
    $newuserid = @$_POST['newuserid'];
    $newpasswd = @$_POST['newpasswd'];

    // Set message and write new credentials.
    if ((isset($newuserid)) && (isset($newpasswd)) &&
        (($code = verify_credentials($newuserid,$newpasswd)) !== 0))
    {
      // Render empty form with error message from prior attempt.
      addUserForm(array("code"=>$code
                       ,"form"=>"AddDbUser.php"
                       ,"userid"=>$newuserid));
    }
    else
    {
      // Create new user only when authenticated.
      if (!(isset($userid)) && (isset($_SESSION['userid'])))
       create_new_db_user($_SESSION['db_userid'],$newuserid,$newpasswd);

      // Render fresh empty form.
      addUserForm(array("form"=>"AddDbUser.php"));
    }
  }
  else
  {
    // Destroy the session and force re-authentication.
    session_destroy();

    // Redirect to the login form.
    signOnForm();
  }

  /* Library functions.
  || ----------------------------------------------------------------
  ||  Function Name               Return Type  Parameters
  || ---------------------------  -----------  ----------------------
  ||  authenticate()              bool
  ||  create_new_db_user()        void         string   $userid
  ||                                           string   $newuserid
  ||                                           string   $newpasswd
  ||  get_message()               string       int      $code
  ||                                           string   $userid
  ||  get_session()               int          string   $sessionid
  ||                                           string   $userid = null
  ||                                           string   $passwd = null
  ||  is_inserted()               bool         resource $c
  ||                                           string   $newuserid
  ||  isset_sessionid()           void         resource $c
  ||                                           string   $sessionid
  ||  record_session()            void         resource $c
  ||                                           string   $sessionid
  ||  register_session()          void         resource $c
  ||                                           string   $userid
  ||                                           string   $sessionid
  ||  set_error()                 void         string   $function
  ||                                           array    $table
  ||  update_session()            void         resource $c
  ||                                           string   $sessionid
  ||                                           string   $remote_address
  ||  verify_credentials()        int          string   $userid
  ||                                           string   $passwd
  ||  verify_db_login()           bool         string   $userid
  ||                                           string   $passwd
  */

  // ----------------------------------------------------------------

  // Authenticate sign on.
  function authenticate($userid,$passwd)
  {
    // Check session variables for authentication.
    if ((isset($userid)) && (isset($passwd)))
      return verify_db_login($userid,$passwd);
  }

  // ----------------------------------------------------------------

  // Add a new user to the authorized control list.
  function create_new_db_user($userid,$newuserid,$newpasswd)
  {
    // Attempt connection and evaluate password.
    if ($c = @oci_connect(SCHEMA,PASSWD,TNS_ID))
    {
      // Check for prior insert, possible on web page refresh.
      if (!is_inserted($c,$newuserid))
      {
        // Encrypt password.
        $newpassword = sha1($newpasswd);

        // Return database UID.
        $s = oci_parse($c,"INSERT INTO FR_Person
                           ( LOGINUSERID
                           , LOGINpasswd
                           , creation_date
                           , last_update_date )
                           VALUES
                           ( :newuserid
                           , :newpasswd
                           , SYSDATE
                           , SYSDATE)");

        // Bind the variables as strings.
        oci_bind_by_name($s,":newuserid",$newuserid);
        oci_bind_by_name($s,":newpasswd",$newpassword);

        // Execute the query, error handling should be added.
        if (!@oci_execute($s,OCI_COMMIT_ON_SUCCESS))
        {
          // Set error message.
          set_error(__FUNCTION__,array('FR_Person'));
        }
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

  // Build new user error message string.
  function get_message($code,$userid)
  {
    // Designate message by error code.
    switch ($code)
    {
      case USER_VALID:
        return "You have added user [$userid] successfully.";
      case USER_EXISTS:
        return "User ID [$userid] is already in use.";
      case USER_LENGTH_ZERO:
        return "User ID [$userid] cannot be a null value.";
      case USER_STARTS_WITH_NUMBER:
        return "User ID [$userid] must start with a character.";
      case USER_LENGTH_OUTSIDE_RANGE:
        return "User ID [$userid] must be between 6 and 10 characters.";
      case USER_PASSWORD_LENGTH:
        return "The password must be between 6 and 10 characters.";
    }
  }

  // ----------------------------------------------------------------

  // Get a valid session.
  function get_session($sessionid,$userid = null,$passwd = null)
  {
    // Attempt connection and evaluate password.
    if ($c = @oci_connect(SCHEMA,PASSWD,SRVR))
    {
      // Assign metadata to local variable.
      $remote_address = $_SERVER['REMOTE_ADDR'];

      // Return database UID within 5 minutes of session registration.
      // The Oracle DATE data type is a timestamp where .003472222 is
      // equal to 5 minutes.
      $s = oci_parse($c,"SELECT   su.LOGINUSERID
                         ,        ss.system_remote_address
                         ,        ss.system_session_id
                         FROM     FR_Person su JOIN system_session ss
                         ON       su.LOGINUSERID = ss.system_user_id
                         WHERE    ss.system_session_number = :sessionid
                         AND     (SYSDATE - ss.last_update_date) <= .003472222");

      // Bind the variables as strings.
      oci_bind_by_name($s,":sessionid",$sessionid);

      // Execute the query, error handling should be added.
      if (@oci_execute($s,OCI_DEFAULT))
      {
        // Check for a validated user, also known as a fetched row.
        if (oci_fetch($s))
        {
          // Assign unqualified values.
          $_SESSION['userid'] = oci_result($s,'SYSTEM_USER_NAME');

          // Check for same remote address.
          if ($remote_address == oci_result($s,'SYSTEM_REMOTE_ADDRESS'))
          {
            // Refresh last update timestamp of session.
            update_session($c,$sessionid,$remote_address);
            return (int) oci_result($s,'SYSTEM_SESSION_ID');
          }
          else
          {
            // Log attempted entry.
            record_session($c,$sessionid);
            return 0;
          }
        }
        else
        {
          // Record when not first login.
          if (!isset($userid) && !isset($passwd))
            record_session($c,$sessionid);
            return 0;
        }
      }
      else
      {
        // Set error message.
        set_error(__FUNCTION__,array('SYSTEM_USER','SYSTEM_SESSION'));
      }

      // Close the connection.
      oci_close($c);
    }
    else
    {
      $errorMessage = oci_error();
      print htmlentities($errorMessage['message'])."<br />";
      return 0;
    }
  }

  // ----------------------------------------------------------------

  // Define a duplicate error avoidance function for page refreshes.
  function is_inserted($c,$newuserid)
  {
    // Check for existing user.
    $s = oci_parse($c,"SELECT   null
                       FROM     system_user
                       WHERE    system_user_name = :newuserid");

    // Bind the variables as strings.
    oci_bind_by_name($s,":newuserid",$newuserid);

    // Execute the query, error handling should be added.
    if (@oci_execute($s,OCI_DEFAULT))
    {
      // Check for a existing entry.
      if (oci_fetch($s))
        return true;
      else
        return false;
    }
    else
    {
      // Set error message.
      set_error(__FUNCTION__,array('SYSTEM_USER'));
    }
  }

  // ----------------------------------------------------------------

  // Confirm session ID is registered.
  function isset_sessionid($c,$sessionid)
  {
    // Find recorded session data.
    $s = oci_parse($c,"SELECT   NULL
                       FROM     system_session
                       WHERE    system_session_number = :sessionid");

    // Bind the variables as strings.
    oci_bind_by_name($s,":sessionid",$sessionid);

    // Execute the query, error handling should be added.
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
      // Set error message.
      set_error(__FUNCTION__,array('SYSTEM_SESSION'));
    }
  }

  // ----------------------------------------------------------------

  // Register session ID.
  function record_session($c,$sessionid)
  {
    // Insert a new session.
    $s = oci_parse($c,"INSERT
                       INTO     invalid_session
                       VALUES
                       (invalid_session_s1.nextval
                       ,:sessionid
                       ,:remote_address
                       , -1
                       ,SYSDATE
                       , -1
                       ,SYSDATE)");

    // Bind the variables as strings.
    oci_bind_by_name($s,":sessionid",$sessionid);
    oci_bind_by_name($s,":remote_address",$_SERVER['REMOTE_ADDR']);

    // Execute the query, error handling should be added.
    if (!@oci_execute($s,OCI_COMMIT_ON_SUCCESS))
    {
      // Set error message.
      set_error(__FUNCTION__,array('INVALID_SESSION'));
    }
  }

  // ----------------------------------------------------------------

  // Register session ID.
  function register_session($c,$userid,$sessionid)
  {
    // Insert a new session.
    $s = oci_parse($c,"INSERT
                       INTO     system_session
                       VALUES
                       (system_session_s1.nextval
                       ,:sessionid
                       ,:remote_address
                       ,:userid1
                       ,:userid2
                       ,SYSDATE
                       ,:userid3
                       ,SYSDATE)");

    // Bind the variables as strings.
    oci_bind_by_name($s,":sessionid",$sessionid);
    oci_bind_by_name($s,":remote_address",$_SERVER['REMOTE_ADDR']);
    oci_bind_by_name($s,":userid1",$userid);
    oci_bind_by_name($s,":userid2",$userid);
    oci_bind_by_name($s,":userid3",$userid);

    // Execute the query, error handling should be added.
    if (!@oci_execute($s,OCI_COMMIT_ON_SUCCESS))
    {
      // Set error message.
      set_error(__FUNCTION__,array('SYSTEM_SESSION'));
    }

    // Return current session ID.
    $s = oci_parse($c,"SELECT   system_session_id
                       FROM     system_session
                       WHERE    system_session_number = :sessionid
                       AND      system_user_id = :userid");

    // Bind the variables as strings.
    oci_bind_by_name($s,":sessionid",$sessionid);
    oci_bind_by_name($s,":userid",$userid);

    // Execute the query, error handling should be added.
    if (@oci_execute($s,OCI_DEFAULT))
    {
      // Check for a validated user, also known as a fetched row.
      if (oci_fetch($s))
        $_SESSION['session_id'] = oci_result($s,'SYSTEM_SESSION_ID');
      else
        $_SESSION['session_id'] = 0;
    }
    else
    {
      // Set error message.
      set_error(__FUNCTION__,array('SYSTEM_SESSION'));
    }
  }

  // ----------------------------------------------------------------

  // Print function message.
  function set_error($function,$table)
  {
    // Set session error flag to suppress printing form.
    if (!$_SESSION['db_error'])
      $_SESSION['db_error'] = DATABASE_INVALID;

    // Set error message.
    $errorMessage  = "Run against [<b><i>".$SCHEMA."</b></i>]<br />";
    $errorMessage .= "Thrown in [<b><i>".$function."()</b></i>] ";
    $errorMessage .= "function because of a missing or altered ";

    // Set the ends of the range.
    $start = 0;
    $end = count($table);

    // Loop through the list of possible missing or altered tables.
    for ($i = $start;$i < $end;$i++)
      if (($i == $start) && ($i == $end))
        $errorMessage .= $table[$i]."<br />";
      else if ($i == $end - 1)
        $errorMessage .= $table[$i]." table.<br />";
      else if (($i >= $start) && ($i < $end - 2))
        $errorMessage .= $table[$i].", ";
      else if (($i >= $start) && ($i < $end - 1))
        $errorMessage .= $table[$i]." or ";

     // Print the message.
     print $errorMessage;
  }

  // ----------------------------------------------------------------

  // Refresh last update value of session ID.
  function update_session($c,$sessionid,$remote_address)
  {
    // Insert a new session.
    $s = oci_parse($c,"UPDATE   system_session
                       SET      last_update_date = SYSDATE
                       WHERE    system_session_number = :sessionid
                       AND      system_remote_address = :remote_address");

    // Bind the variables as strings.
    oci_bind_by_name($s,":sessionid",$sessionid);
    oci_bind_by_name($s,":remote_address",$remote_address);

    // Execute the query, error handling should be added.
    if (!@oci_execute($s,OCI_COMMIT_ON_SUCCESS))
    {
      // Set error message.
      set_error(__FUNCTION__,array('SYSTEM_SESSION'));
    }
  }

  // ----------------------------------------------------------------

  // Validate new accounts meets identity management rules.
  function verify_credentials($userid,$passwd)
  {
    switch(true)
    {
      // Does user name already exist.
      case (verify_db_login($userid,$passwd) != 0):
        return USER_EXISTS;

      // Is user name greater than zero.
      case (strlen($userid) == 0):
        return USER_LENGTH_ZERO;

      // Does user name start with an alphabetic character.
      case (ereg("([0-9])",substr($userid,0,1))):
        return USER_STARTS_WITH_NUMBER;

      // Does user name start with a letter for a 6-10 character string.
      case (!ereg("([a-zA-Z]+[a-zA-Z0-9]{5,10})",$userid)):
        return USER_LENGTH_OUTSIDE_RANGE;

      // Does password start contain a 6-10 character string.
      case (!ereg("([a-zA-Z0-9]{5,10})",$passwd)):
        return USER_PASSWORD_LENGTH;

      // Acknowledge everything is fine.
      default:
        return USER_VALID;
        print "Right here<br />";
    }
  }

  // ----------------------------------------------------------------

  // Check for authorized account.
  function verify_db_login($userid,$passwd)
  {
    // Attempt connection and evaluate password.
    if ($c = @oci_connect(SCHEMA,PASSWD,TNS_ID))
    {
      // Return database UID.
      $s = oci_parse($c,"SELECT   system_user_id
                         FROM     system_user
                         WHERE    system_user_name = :userid
                         AND      system_user_password = :passwd
                         AND      SYSDATE BETWEEN start_date
                                          AND NVL(end_date,SYSDATE)");

      // Bind the variables as strings.
      oci_bind_by_name($s,":userid",$userid);
      oci_bind_by_name($s,":passwd",sha1($passwd));

      // Execute the query, error handling should be added.
      if (@oci_execute($s,OCI_DEFAULT))
      {
        // Check for a validated user, also known as a fetched row.
        if (oci_fetch($s))
        {
           // Confirm session and collect foreign key reference column.
           if ((!isset($_SESSION['session_id'])) ||
               (!isset_sessionid($c,$_SESSION['sessionid'])))
           {
             $_SESSION['db_userid'] = oci_result($s,1);
             register_session($c,(int) $_SESSION['db_userid'],$_SESSION['sessionid']);
           }

           // User verified.
           return true;
        }
        else
        {
          // User not verified.
          return false;
        }
      }
      else
      {
        // Set error message.
        set_error(__FUNCTION__,array('SYSTEM_USER'));
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

  /* Form Rendering functions.
  || ----------------------------------------------------------------
  ||  Function Name               Return Type  Parameters
  || ---------------------------  -----------  ----------------------
  ||  addUserForm()               void         array    $args
  ||  signOnForm()                void
  */

  // ----------------------------------------------------------------

  // Build dynamic data entry form.
  function addUserForm($args)
  {
    // Suppress form rendering when encountering a database failure.
    if ($_SESSION['db_error'] == DATABASE_VALID)
    {
      // Define local variables.
      $code;
      $form;
      $userid;

      // Parse form parameters.
      foreach ($args as $name => $value)
      {
        switch (true)
        {
          case ($name == "form"):
            $form = $value;
            break;
          case ($name == "code"):
            $code = $value;
            break;
          case ($name == "userid"):
            $userid = $value;
            break;
        }
      }

      // Initialize return variable.
      $out  = '<div id=centered>';

      // Set and append next form target file.
      $out .= '<form method="post" action="'.$form.'">';

      // Append balance of form header.
      $out .= '<table border="4"
                      bgcolor="beige"
                      bordercolor="silver"
                      cellspacing="0">';
      $out .= '<tr><td align="center" width="400">';
      $out .= '<font size=+3>New User</font>';
      $out .= '</td></tr>';

      // Check for and display error message.
      if ((isset($code)) && (is_int($code)))
      {
        $out .= '<tr><td align="center" bgcolor="white" width="400">';
        $out .= '<font color=blue>'.get_message($code,$userid).'</font>';
        $out .= '</td></tr>';
      }

      // Append standard data entry components.
      $out .= '<tr><td>';
      $out .= '<table border="0" cellpadding="5" cellspacing="0">';
      $out .= '<tr>';
      $out .= '<td align="right" width="200">User ID:</td>';
      $out .= '<td width="200">';
      $out .= '<input name="newuserid" type="text">';
      $out .= '</td>';
      $out .= '</tr>';
      $out .= '<tr>';
      $out .= '<td align="right">User Password:</td>';
      $out .= '<td><input name="newpasswd" type="password"></td>';
      $out .= '</tr>';
      $out .= '<tr>';
      $out .= '</table>';
      $out .= '</td></tr>';
      $out .= '<tr><td align="center" colspan="2">';
      $out .= '<table border="0" cellpadding="5" cellspacing="0">';
      $out .= '<tr>';
      $out .= '<td align="center" valign="center">';
      $out .= '<input name="submit" type="submit" value="Add User">';
      $out .= '</td>';
      $out .= '</tr>';
      $out .= '</table>';
      $out .= '</td></tr>';
      $out .= '</table>';
      $out .= '</form>';
      $out .= '<form method="post" action="SignOnDB.php">';
      $out .= '<table border="0" cellpadding="5" cellspacing="0">';
      $out .= '<tr>';
      $out .= '<td align="right" valign="center" width="400">';
      $out .= '<input name="submit" type="submit" value="Log Out">';
      $out .= '</td>';
      $out .= '</tr>';
      $out .= '</table>';
      $out .= '</div>';

      // Return the form for rendering in a web page.
      print $out;
    }
  }

  // ----------------------------------------------------------------

  // Build static data entry form.
  function signOnForm()
  {
    // Suppress form rendering when encountering a database failure.
    if ($_SESSION['db_error'] == DATABASE_VALID)
    {
      // Initialize return variable.
      $out  = '<div id=centered>';

      // Set and append next form target file.
      $out .= '<form method="post" action="AddDbUser.php">';

      // Append balance of form header.
      $out .= '<table border="4"
                      bgcolor="beige"
                      bordercolor="silver"
                      cellspacing="0">';
      $out .= '<tr><td align="center" width="400">';
      $out .= '<font size=+3>User Login</font>';
      $out .= '</td></tr>';
      $out .= '<tr><td>';
      $out .= '<table border="0" cellpadding="5" cellspacing="0">';
      $out .= '<tr>';
      $out .= '<td align="right" width="200">User ID:</td>';
      $out .= '<td width="200"><input name="userid" type="text"></td>';
      $out .= '</tr>';
      $out .= '<tr>';
      $out .= '<td align="right">User Password:</td>';
      $out .= '<td><input name="passwd" type="password"></td>';
      $out .= '</tr>';
      $out .= '<tr>';
      $out .= '</table>';
      $out .= '</td></tr>';
      $out .= '<tr><td align="center" colspan="2">';
      $out .= '<table border="0" cellpadding="5" cellspacing="0">';
      $out .= '<tr>';
      $out .= '<td align="center" valign="center">';
      $out .= '<input name="submit" type="submit" value="Login">';
      $out .= '</td>';
      $out .= '</tr>';
      $out .= '</table>';
      $out .= '</td></tr>';
      $out .= '</table>';
      $out .= '</form>';
      $out .= '</div>';

      // Return the form for rendering in a web page.
      print $out;
    }
  }
?>
</body>
</html>