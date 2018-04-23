<?php
/**
 *  ITEC 370: Spring 2018
 *	Final Code: High Score Query
 *  Andrew McGuiness, Andrew Albanese, Ryan Kelley, Michael Hall
*/

// Import the DB connection script
include_once "createConnection.php";

//function to create a table
$conn = ConnectToDB();

// Query Data from the DB
$queryPrefix = 'SELECT `score` FROM `test_data`';
$queryInternal = ' ';
$queryPostfix = ' ORDER BY `score` DESC LIMIT 10';

// Check for post data from the filters
$inputFilter = isset($_GET["input"]) && $_GET["input"] != "";
$ageFilter = isset($_GET["age"]) && $_GET["age"] != "";
$skillFilter = isset($_GET["skill"]) && $_GET["skill"] != "";

// If there are any filters, then add the elements to the query
if ($inputFilter || $ageFilter || $skillFilter) {
    $queryInternal .= "WHERE ";

    // Apply the input filter?
    if ($inputFilter) {
        $queryInternal .= "`input` = '" . $_GET["input"] . "' ";

        if ($ageFilter || $skillFilter)
            $queryInternal .= " AND ";
    }

    // Apply the age filter?
    if ($ageFilter) {
        $queryInternal .= "`age` = '" . $_GET["age"] . "' ";

        if ($skillFilter)
            $queryInternal .= " AND ";
    }

    // Apply the skill filter?
    if ($skillFilter) {
        $queryInternal .= "`skill` = '" . $_GET["skill"] . "' ";
    }
}

// Query the DB for the high scores based on the filters
$query = $queryPrefix . $queryInternal . $queryPostfix;
$result = $conn->query($query);

// Insert the data into the DB
$scores = array();

// Build an array from the DB rows
while ($row = $result->fetch_assoc()) {
    $scores[] = $row;
}

// Print the data out as a JSON so the game can load it in as an object
echo json_encode($scores);
?>