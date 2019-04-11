<?php
// Create the table
echo "<table class = \"tftable\">\n";
echo "<tr>"; // create a tabke row for the table headers
// Get the column names and set column headers.
$ncols = oci_num_fields($stid);
for ($i = 1; $i <= $ncols; $i++) {
    $column_name  = oci_field_name($stid, $i);
echo "<th>$column_name</</th>";
}
echo "</tr>";  // create a tabke row for the table headers

// Display the rows
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}

// end of Create the table
echo "</table>\n";
?>