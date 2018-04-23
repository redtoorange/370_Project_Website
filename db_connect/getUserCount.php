<?php
/**
 *  ITEC 370: Spring 2018
 *	Final Code: Active Record Query
 *  Andrew McGuiness, Andrew Albanese, Ryan Kelley, Michael Hall
*/

/**
 * Get the number of currently active records in the database.  This will give
 * a rough estimate of how many people are currently playing the game.
 */
function getUserCount()
{
    include_once "databaseController.php";

    // Connect to the DB
    $connect = ConnectToDB();

    // Query recently active records
    $query = "select * from test_data where LastSeem > date_sub(now(), interval 3 minute)";
    $result = $connect->query($query);

    // Return the number of rows that we got back
    return $result->num_rows;
}

?>