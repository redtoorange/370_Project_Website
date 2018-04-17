<?php
/*
 *	Iteration 3 Data Controller Script
 *  Andrew McGuiness and Ryan Kelley
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
	$inputFilter = isset( $_GET["input"]) && $_GET["input"] != "";
	$ageFilter = isset( $_GET["age"]) && $_GET["age"] != "";
	$skillFilter = isset( $_GET["skill"]) && $_GET["skill"] != "";

	// If there are any filters, then add the elements to the query
	if( $inputFilter || $ageFilter || $skillFilter ){
		$queryInternal .= "WHERE ";

		if( $inputFilter ){
			$queryInternal .= "`input` = '".$_GET["input"]."' ";

			if( $ageFilter || $skillFilter)
				$queryInternal .= " AND ";
		}

		if( $ageFilter ){
			$queryInternal .= "`age` = '".$_GET["age"]."' ";

			if( $skillFilter)
				$queryInternal .= " AND ";
		}

		if( $skillFilter ){
			$queryInternal .= "`skill` = '".$_GET["skill"]."' ";
		}
	}

	$query =  $queryPrefix . $queryInternal . $queryPostfix;
	$result = $conn->query( $query );
	
	// Insert the data into the DB
	$scores = array();
	
	while($row = $result->fetch_assoc()){
		$scores[] = $row;
	}

	echo json_encode($scores);
?>