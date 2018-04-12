<?php
	// Import the DB connection script
	require "createConnection.php";
					
	//function to create a table
	$conn = ConnectToDB();

	// Query Data from the DB
	$query = 'SELECT * FROM `game_preferences` WHERE `ID` = 1';
	$result = $conn->query($query);

	echo json_encode($result->fetch_assoc());
?>