<?php
    $conn=oci_connect("V00859712","V00859712","128.172.188.107");
    If (!$conn)
        echo 'Failed to connect to Oracle';
    else
        echo 'Succesfully connected with Oracle DB';
 
oci_close($conn);
?>
