<?php
/*
 *	Iteration 3 Data Controller Script
 *  Andrew McGuiness and Ryan Kelley
*/

    /**
     * 
     */
    function getUserCount(){
        include_once "databaseController.php";

        $connect = ConnectToDB();

        $query = "select * from test_data where LastSeem > date_sub(now(), interval 3 minute)";
        $result = $connect->query( $query );

        return $result->num_rows;
    }
    
?>