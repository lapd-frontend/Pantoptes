<?php
include_once("vendor/autoload.php");
include_once("util.php");

if (isset($_GET['pageno']) and $_GET["pageno"] != '') {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$no_of_records_per_page = 30;

$offset = ($pageno-1) * $no_of_records_per_page;

$query = "SELECT * FROM packets";
$filter = "WHERE ";
$paginate = "ORDER BY packets.id desc LIMIT $no_of_records_per_page OFFSET $offset  ";
?>
<table class="table table-striped table-sm">
    <thead>
    <tr>
        <th>Packet ID</th>
        <th>Tool</th>
        <th>Packet Type</th>
        <th>Source IP</th>
        <th>Destination IP</th>
        <th>Source Country</th>
        <th>Destination Country</th>
    </tr>
    </thead>
    <tbody>
    <?php

    // TODO: SANITATION
    $protocol = "";
    $tool = "";
    $country = "";
    if (isset($_GET["protocol"])) {
        $protocol = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET["protocol"]);
    }
    if (isset($_GET["country"])) {
        $country = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET["country"]);
    }
    if (isset($_GET["tool"])) {
        $tool = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET["tool"]);
    }
    if ($protocol != '') { $filter  .= "packet_type = '" . $_GET["protocol"] ."'"; } else { $filter  .= "1=1"; };
    if ($country != '') { $filter  .= " AND source_country = '" . $_GET["country"] ."'"; };
    if ($tool != '') { $filter  .= " AND tool = " . $_GET["tool"]; };

    $query = $query.' '.$filter.' '.$paginate;
    $query = pg_query($conn, $query);
    while ($row = pg_fetch_assoc($query)) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . tool_id_to_string($row["tool"]) . "</td>";
        echo "<td>" . $row["packet_type"] . "</td>";
        echo "<td>" . $row["source_ip"] . "</td>";
        echo "<td>" . $row["destination_ip"] . "</td>";
        echo "<td>" . $row["source_country"] . "</td>";
        echo "<td>" . $row["destination_country"] . "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>