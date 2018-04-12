<?php
/*
**	Iteration 0 Data Testing and Upload Script
** Andrew McGuiness and Ryan Kelley
*/

//	!!!!This file should be kept inside of a folder on 
//	!!!!	the server that is secured.

	require "createConnection.php";

	// Upload some data into the DB
    function InsertData( $mouse, $age_group, $level )
    {
		// Get the connection
        $conn = ConnectToDB();

		// Create the query
        $query = "INSERT INTO `test_data` (`ID`, `input`, `age`, `skill`, `score`) VALUES (NULL, '" . $mouse . "', '" . $age_group . "', '" . $level . "', 0);";
		
		// Validate it
        $valid = $conn->query( $query );
        $ID = -1;
        if( !$valid ){
            die("Failed to insert into database");
        }
        else{
            $ID = $conn->insert_id;
        }

        $conn->close();

		// Return the ID for the data we uploaded
        return $ID;
    }

    function getUserID()
    {
        // Insert the data into the DB
        $ID = InsertData( $_POST["inputSelect"], $_POST["ageSelect"], $_POST["skillSelect"]);

        echo json_encode(
            array(
                "ID" => $ID
            )
        );
    }
	
	// Upload a hit event for a target to the database
	function UploadTargetInfo( $ID, $number, $time, $misses, $totalTargets)
	{
		// Get the connection
        $conn = ConnectToDB();
		
        $query = "UPDATE `test_data` SET `T".$number."_Time` = '".$time."', `T".$number."_MISS` = '".$misses."', `Targets` = '".$totalTargets."'  WHERE `test_data`.`ID` = ".$ID.";";
		
		$valid = $conn->query( $query );
        if( !$valid ){
            die("Failed to insert into database");
        }
		
        $conn->close();
	}
	
	// Upload the player's score to the database
	function UploadScore( $ID, $score )
	{
		// Get the connection
        $conn = ConnectToDB();
		
        $query = "UPDATE `test_data` SET `score` = '".$score."' WHERE `test_data`.`ID` = ".$ID.";";
		
		$valid = $conn->query( $query );
        if( !$valid ){
            die("Failed to insert into database");
        }
		
        $conn->close();
	}

	function SetTheme()
    {
        // Get the connection
        $conn = ConnectToDB();

        $query = "UPDATE `game_preferences` SET `AvailableThemes` = '".$_POST["theme"]."' WHERE `game_preferences`.`ID` = 1;";

        $valid = $conn->query( $query );
        if( !$valid ){
            die("Failed to insert into database");
        }

        $conn->close();
    }


    function DeleteData()
    {
        //function to create a table
        $conn = ConnectToDB();

        // Query Data from the DB
        $query = 'TRUNCATE `test_data`';
        $conn->query($query);

        echo "success";
    }

    function AdminLogin()
    {
        session_start();

        if(isset($_POST['do_login'])) {


            //function to create a table
            $connect = ConnectToDB();

            $username=$_POST['username'];
            $password=$_POST['password'];

            $select_data = $connect->query("SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'");

            if($row = $select_data->fetch_assoc() ) {
                $_SESSION['username'] = $row['username'];
                echo "success";
            }
            else {
                echo "fail";
            }

            exit();
        }
    }

    if( isset($_POST["whatToDo"])){
        $whatToDo = $_POST["whatToDo"];

        if( $whatToDo == "uploadScore"){
            UploadScore( $_POST["ID"], $_POST["score"] );
        }
        elseif ($whatToDo == "uploadHit"){
            UploadTargetInfo( $_POST["ID"], $_POST["number"], $_POST["time"], $_POST["misses"], $_POST["totalTargets"]);
        }
        elseif ($whatToDo == "generateID"){
            getUserID();
        }
        elseif ($whatToDo == "setTheme"){
            SetTheme();
        }
        elseif ($whatToDo == "deleteData"){
            DeleteData();
        }
        elseif ($whatToDo == "adminLogin"){
            AdminLogin();
        }
    }
?>