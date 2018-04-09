<?php
	// Import the DB connection script
    require "info.php";
	
	// Get the connection
	$conn = ConnectToDB();
	
	$query = "UPDATE `game_preferences` SET `AvailableThemes` = '".$_POST["theme"]."' WHERE `game_preferences`.`ID` = 1;";
	
	$valid = $conn->query( $query );
	if( !$valid ){
		die("Failed to insert into database");
	}
	
	$conn->close();
?>