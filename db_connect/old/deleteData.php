<?php
	// Import the DB connection script
	require "info.php";
					
	//function to create a table
	$conn = ConnectToDB();

	// Query Data from the DB
	$query = 'TRUNCATE `test_data`';
	$result = $conn->query($query);
	
	echo "success";
?>