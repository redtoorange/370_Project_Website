<?php
	// Import the DB connection script
    require "info.php";
	
	//UploadTargetInfo( $ID, $number, $time, $accuracy)
    UploadTargetInfo( $_POST["ID"], $_POST["number"], $_POST["time"], $_POST["misses"], $_POST["totalTargets"]);
?>