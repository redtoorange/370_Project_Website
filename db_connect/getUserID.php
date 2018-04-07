<?php
	// Import the DB connection script
	require "info.php";
	
	// Insert the data into the DB
	$ID = InsertData( $_POST["inputSelect"], $_POST["ageSelect"], $_POST["skillSelect"]);

	echo json_encode(
		array(
			"ID" => $ID
		)
	);
?>