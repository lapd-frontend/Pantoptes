<?php
/**
 * Created by PhpStorm.
 * User: agott
 * Date: 2018-11-16
 * Time: 19:02
 */
// Uncomment the following line to debug from your machine (tells your local PHP instance where to find the DB).
// Comment it out before pushing to master!
putenv("DATABASE_URL=postgres://eqdvefruwrhirc:57bbdd00b6b88481eebeeea8c11b52776d0ec96f9e3dd9a21d12f6d9376b9a62@ec2-54-83-27-162.compute-1.amazonaws.com:5432/dqt8lhkkbe5h7");
$conn = pg_connect(getenv("DATABASE_URL"));

/**
 * Returns an human-readable representation of the tool name used to capture a packet.
 * @param $tool_id 0 (libprotoident) or 1 (nDPI)
 * @return string
 */
function tool_id_to_string($tool_id)
{
    if ($tool_id == "0") {
        return "Libprotoident";
    } else {
        return "nDPI";
    }
}

/**
 * Returns a list of protocols used in the database.
 * @param $conn resource DB connection
 * @return array list of protocols used in the database
 */
function get_protocols($conn)
{
    $acc = array();
    $query = "SELECT DISTINCT packet_type FROM packets LIMIT 500";
    $query = pg_query($conn, $query);
    while ($row = pg_fetch_assoc($query)) {
        array_push($acc, $row["packet_type"]);
    }
    $acc = array_unique($acc);
    sort($acc);
    return $acc;
}

/**
 * Returns a list of countries used in the database.
 * @param $conn resource DB connection
 * @return array list of countries in the database
 */
function get_countries($conn)
{
    $acc = array();
    $query = "SELECT DISTINCT source_country FROM packets LIMIT 500";
    $query = pg_query($conn, $query);
    while ($row = pg_fetch_assoc($query)) {
        array_push($acc, $row["source_country"]);
    }
    $query = "SELECT DISTINCT destination_country FROM packets LIMIT 500";
    $query = pg_query($conn, $query);
    while ($row = pg_fetch_assoc($query)) {
        array_push($acc, $row["destination_country"]);
    }
    $acc = array_unique($acc);
    sort($acc);
    return $acc;
}

function get_packet_count($conn) {
    $query = "SELECT count(id) FROM packets";
    $query = pg_query($conn, $query);
    return pg_fetch_result($query,0,0);
}