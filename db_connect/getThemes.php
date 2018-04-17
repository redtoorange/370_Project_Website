<?php
/*
 *	Iteration 3 Data Controller Script
 *  Andrew McGuiness and Ryan Kelley
*/

	/**
	 * 
	 */
	function getTheme(){
		// Import the DB connection script
		include_once "databaseController.php";

		//function to create a table
		$conn = ConnectToDB();

		// Query Data from the DB
		$query = 'SELECT * FROM `game_preferences` WHERE `ID` = 1';
		$result = $conn->query($query);

		return $result->fetch_assoc()["AvailableThemes"];
	}
?>