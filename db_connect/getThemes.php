<?php
/**
 *  ITEC 370: Spring 2018
 *	Final Code: Theme Query
 *  Andrew McGuiness, Andrew Albanese, Ryan Kelley, Michael Hall
*/

/**
 * Get the currently available themes from the database.  This can be changed
 * by the Administator from the Admin Panel.
 */
function getTheme()
{
    // Import the DB connection script
    include_once "databaseController.php";

    //Connect to the DB
    $conn = ConnectToDB();

    // Query Data from the DB
    $query = 'SELECT * FROM `game_preferences` WHERE `ID` = 1';
    $result = $conn->query($query);

    // Return just the text for what is available: "Space", "Carnival" or "Both"
    return $result->fetch_assoc()["AvailableThemes"];
}

?>