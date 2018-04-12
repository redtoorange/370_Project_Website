<?php
	// Import the DB connection script
	require "createConnection.php";
					
	//function to create a table
	$conn = ConnectToDB();

	// Query Data from the DB
	$query = 'SELECT `score` FROM `test_data` ORDER BY `score` DESC LIMIT 10';
	$result = $conn->query($query);
	
	// Insert the data into the DB
	$scores = array();
	
	while($row = $result->fetch_assoc()){
		$scores[] = $row;
	}

	echo json_encode($scores);
?>