<?php
echo "<article>";


$stid = oci_parse($conn, 'select firstname || \' \' || lastname as NAME, street, statename as State, zip from fr_person join fr_address on fr_person.AID = fr_address.AID');

oci_execute($stid);
echo "<h1>Persons who have recorded their addresses2</h1>";

require("drawTable.php"); //  require ("test.php");

// Close the Oracle connection
oci_close($conn);

// End of article
echo "</article>";
?>  