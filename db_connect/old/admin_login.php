<?php
	// Import the DB connection script
	require "info.php";
	
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
?>