<?php
<article>
$stid = oci_parse($conn, 'SELECT * FROM FR_Person');
oci_execute($stid);
echo "<h1>All Persons in our FR_Person table</h1>";
echo "<table class = \"tftable\">\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
// Close the Oracle connection
oci_close($conn);
</article>
?>  