<?php

    function getUserCount(){
        require "databaseController.php";

        $connect = ConnectToDB();

        $query = "select * from test_data where LastSeem > date_sub(now(), interval 3 minute)";
        $result = $connect->query( $query );

        return $result->num_rows;
    }
    
?>