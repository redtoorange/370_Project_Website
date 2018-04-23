<!--
/*
 *  ITEC 370: Spring 2018
 *	Final Code: Admin Page
 *  Andrew McGuiness, Andrew Albanese, Ryan Kelley, Michael Hall
 *	
 * Original design by:
 *	Identity by HTML5 UP
 *	html5up.net | @ajlkn
 *	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
 *
 *	All PHP is custom by the team.
*/
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Researcher Data</title>


    <!-- Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!-- Bootstrap Core Styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- DataTables Bootstrap Style -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>

    <!-- Custom Styling -->
    <link href="css/admin.css" rel="stylesheet">

    <?php
    // Import the scripts for later usage
    include_once "../db_connect/getThemes.php";
    include_once "../db_connect/getUserCount.php";

    session_start();

    // Log the user out if they are logged in and requested a logout
    if (isset($_POST['logout'])) {
        unset($_SESSION['username']);
    }
    ?>
</head>


<body class="fixed-nav bg-dark" id="page-top">

<!-- Top navbar begin -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand mr-auto" href="../index.html">Data Administration</a>
    <?php
    // If the user is logged in, display the logout button
    if (isset($_SESSION['username'])) {
        echo '
			<form method="post">
				<button id="logoutButton" type="submit" name="logout" value="logout" class="btn btn-outline-danger my-2 my-sm-0">Logout</button>
			</form>
			';
    } // If the user is NOT logged in, display the login button
    else {
        echo '<button id="loginButton" class="btn btn-outline-success my-2 my-sm-0">Admin Login</button>';
    }
    ?>
</nav>
<!-- Top navbar end -->


<div class="content-wrapper">
    <!-- Data Controls Bit -->
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3><i class="fa fa-download"></i> Data Controls</h3>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <a class="btn btn-secondary" href="/admin/dataFile.txt" role="button" download> Download File </a>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<button id="themeButton" type="button" class="btn btn-secondary">Change Themes</button>' . "\n";
                    echo '<button id="deleteButton" type="button" class="btn btn-danger">Delete Data</button>';
                }
                ?>
            </div>
        </div>

        <br/>

        <div class="row">
            <div class="col">

                <!-- Allow the Researcher to apply filters to the dataset. -->
                <form method="post" action="admin.php" class="form-inline">
                    <!-- Input Device filter. -->
                    <div class="form-group mr-2">
                        <select name="inputSelect" id="inputSelect" class="form-control">
                            <option value="">Input Device</option>
                            <option value="mouse">Mouse</option>
                            <option value="touch">Touch Screen</option>
                            <option value="trackPad">Track Pad</option>
                        </select>
                    </div>

                    <!-- Age filter. -->
                    <div class="form-group mr-2">
                        <select name="ageSelect" id="ageSelect" class="form-control">
                            <option value="">Age Group</option>
                            <option value="age1">13-18</option>
                            <option value="age2">19-27</option>
                            <option value="age3">28-35</option>
                            <option value="age4">36-45</option>
                        </select>
                    </div>

                    <!-- Skill filter. -->
                    <div class="form-group mr-2">
                        <select name="skillSelect" id="skillSelect" class="form-control">
                            <option value="">Skill Level</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="experienced">Experienced</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>

                    <!-- Theme filter. -->
                    <div class="form-group mr-2">
                        <select name="themeSelect" id="themeSelect" class="form-control">
                            <option value="">Theme Used</option>
                            <option value="Space">Space</option>
                            <option value="Carnival">Carnival</option>
                        </select>
                    </div>

                    <!-- Apply or Reset the filters and reload the page. -->
                    <button id="filterSubmit" type="submit" class="btn btn-secondary mr-2">Apply Filter</button>
                    <button id="filterClear" type="submit" class="btn btn-danger">Clear Filters</button>
                </form>
            </div>
        </div>
    </div>

    <br/>

    <div class="row">
        <div class="col ml-4">
            <!-- Display the number of active rows in the Database. -->
            <h3> Currently Active Records: <?php echo getUserCount(); ?> </h3>
        </div>
    </div>
    <!-- End Data Controls -->

    <br/>

    <!-- Data Table Bit -->
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3><i class="fa fa-database"></i> Target Hunter Data</h3>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Input</th>
                            <th>Age</th>
                            <th>Skill</th>
                            <th>Score</th>
                            <th>Theme</th>
                            <th>Targets Hit</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php

                        // Create the connection to the DB
                        $conn = ConnectToDB();

                        // Initial Query to the DB
                        $query = 'SELECT * FROM  `test_data` ';

                        // Check for post data from the filters
                        $inputFilter = isset($_POST["inputSelect"]) && $_POST["inputSelect"] != "";
                        $ageFilter = isset($_POST["ageSelect"]) && $_POST["ageSelect"] != "";
                        $skillFilter = isset($_POST["skillSelect"]) && $_POST["skillSelect"] != "";
                        $themeFilter = isset($_POST["themeSelect"]) && $_POST["themeSelect"] != "";

                        // If there are any filters, then add the elements to the query
                        if ($inputFilter || $ageFilter || $skillFilter || $themeFilter) {
                            $query .= "WHERE ";

                            // Apply the input filter
                            if ($inputFilter) {
                                $query .= "`input` = '" . $_POST["inputSelect"] . "' ";

                                if ($ageFilter || $skillFilter)
                                    $query .= " AND ";
                            }

                            // Apply the age filter
                            if ($ageFilter) {
                                $query .= "`age` = '" . $_POST["ageSelect"] . "' ";

                                if ($skillFilter)
                                    $query .= " AND ";
                            }

                            // Apply the theme filter
                            if ($skillFilter) {
                                $query .= "`skill` = '" . $_POST["skillSelect"] . "' ";

                                if ($themeFilter)
                                    $query .= " AND ";
                            }

                            // Apply the theme filter
                            if ($themeFilter) {
                                $query .= "`theme` = '" . $_POST["themeSelect"] . "' ";
                            }
                        }

                        // Debug Query
                        //echo $query;

                        // Query the DB
                        $result = $conn->query($query);
                        //$numFields = $result->field_count;

                        // Create and open the file for the data
                        $myFile = "dataFile.txt";
                        $fo = fopen($myFile, 'w') or die("can't open file");

                        // Create an Array of the col names from the DB
                        $columns = array("ID", "input", "age", "skill", "score", "theme", "Targets");

                        // Create an Array to hold all the rows from the DB
                        $rows = array();

                        // Create the table rows from the DB Data
                        while ($row = $result->fetch_assoc()) {
                            $rows[] = $row;    // Add the row to rows

                            // Print the table to HTML
                            echo '<tr>';
                            for ($i = 0; $i < sizeof($columns); $i++) {
                                echo '<td>';
                                echo $row[$columns[$i]];
                                echo '</td>';
                            }
                            echo '</tr>';

                        }

                        // Write the rows to the dataFile
                        fwrite($fo, json_encode($rows, JSON_PRETTY_PRINT));
                        fclose($fo);
                        ?>

                        </tbody>

                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Input</th>
                            <th>Age</th>
                            <th>Skill</th>
                            <th>Score</th>
                            <th>Theme</th>
                            <th>Targets Hit</th>
                        </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Data Table -->


    <!-- Admin Login Modal -->
    <!-- When a user clicks "Login" this modal is displayed to allow them to enter credentials. -->
    <div class="modal fade" id="adminLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Admin Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="../db_connect/databaseController.php" onsubmit="return admin_login();">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="adminUsername">Username</label>
                            <input type="text" class="form-control" id="adminUsername" placeholder="Username">
                        </div>

                        <div class="form-group">
                            <label for="adminPassword">Password</label>
                            <input type="password" class="form-control" id="adminPassword" placeholder="Password">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="loginSubmit" type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Theme Selector Modal -->
    <!-- When the Admin clicks "Change Theme" this modal is displayed to allow them to select a theme. -->
    <div class="modal fade" id="themeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Active Game Themes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="../db_connect/databaseController.php" onsubmit="return change_theme();">

                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="themeSelect" id="bothThemes" value="Both" checked>
                                <label class="form-check-label" for="bothThemes"> Both Themes </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="themeSelect" id="carnivalTheme" value="Carnival">
                                <label class="form-check-label" for="carnivalTheme"> Carnival Theme </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="themeSelect" id="spaceTheme" value="Space">
                                <label class="form-check-label" for="spaceTheme"> Space Theme </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="themeSubmit" type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Delete Button Modal -->
    <!-- When the Admin clicks the "Delete" button, this modal is displayed to warn them. -->
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Warning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Are you sure?</h4>
                    <p>You are about to delete all data. <b>This action cannot be undone!</b></p>
                </div>

                <div class="modal-footer">
                    <form method="post" action="../db_connect/databaseController.php" onsubmit="return delete_data();">
                        <button type="submit" class="btn btn-success">Yes, Delete Data</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap Core -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>

<script>
    // Executed when the document is ready
    $(document).ready(function () {
        // Initialize the data table
        $('#dataTable').DataTable();

        // Set all the filters to the currently displayed values
        $("#inputSelect").val("<?php if (isset($_POST["inputSelect"])) {
            echo $_POST["inputSelect"];
        } ?>");
        $("#ageSelect").val("<?php if (isset($_POST["ageSelect"])) {
            echo $_POST["ageSelect"];
        } ?>");
        $("#skillSelect").val("<?php if (isset($_POST["skillSelect"])) {
            echo $_POST["skillSelect"];
        } ?>");
        $("#themeSelect").val("<?php if (isset($_POST["themeSelect"])) {
            echo $_POST["themeSelect"];
        } ?>");
    });

    // Display Login Panel
    $('#loginButton').click(function () {
        $('#adminLogin').modal('show');
    });

    // Display data delete warning
    $('#deleteButton').click(function () {
        $('#deleteModal').modal('show');
    });

    // Display data delete warning
    $('#themeButton').click(function () {
        $('#themeModal').modal('show');
    });

    // When the filter clear button is clicked, reset all selects
    $("#filterClear").click(function () {
        $("#inputSelect").val("");
        $("#ageSelect").val("");
        $("#skillSelect").val("");
        $("#themeSelect").val("");
    });

    // Handle login ajax call
    function admin_login() {
        // Get the form values
        var user = $("#adminUsername").val();
        var pass = $("#adminPassword").val();

        // Make an AJAX call to the login script
        if (user != "" && pass != "") {
            $.ajax({
                type: 'post',
                url: '../db_connect/databaseController.php',
                data: {whatToDo: "adminLogin", do_login: "do_login", username: user, password: pass},

                success: function (response) {
                    // The user logged in, reload the page
                    if (response == "success") {
                        window.location.href = "admin.php";
                    }
                    // The user failed to login, display a warning
                    else {
                        alert("Invalid Login Credentials.");
                    }
                }
            });

        }
        else {
            alert("Please Fill All The Details");
        }

        return false;
    }

    // Handle delete data ajax (Hidden is not logged in)
    <?php
    // Ignore this unless it's an admin
    if (isset($_SESSION["username"])) {
        echo '
		function delete_data() {
			$.ajax({
				type:"post",
				url:"../db_connect/databaseController.php",
				data:{ whatToDo: "deleteData" },
				
				success:	function(response) {
								if(response=="success") {
									window.location.href="admin.php";
								}
							}
			});
			
			return false;			
		}
				';

    }
    ?>


    // handle change theme ajax (Hidden is not logged in)

    <?php
    // Ignore that unless it's an admin
    if (isset($_SESSION["username"])) {
        echo '
		function change_theme() {
			var selectedTheme = "Both";
			
			if( $("#carnivalTheme").is(":checked") ){
				selectedTheme = "Carnival";
			}
			if( $("#spaceTheme").is(":checked") ){
				selectedTheme = "Space";
			}
			
			$.ajax({
				type: "post",
				url:"../db_connect/databaseController.php",
				data:{ whatToDo: "setTheme", theme: selectedTheme },
				
				success:	function(response) {
								$("#themeModal").modal("hide");
							}
			});
			
			return false;			
				}
				';
    }
    ?>

    // Initialize the theme selector to what is currently set in the DB
    $(document).ready(function () {
        <?php
        // Read from the DB what the current theme is
        echo 'var theme = "' . getTheme() . '";';
        ?>

        // Set the currect option
        if (theme == "Carnival") {
            $("#carnivalTheme").prop("checked", true);
        }
        else if (theme == "Space") {
            $("#spaceTheme").prop("checked", true);
        }
        else {
            $("#bothThemes").prop("checked", true);
        }
    });

</script>

</body>
</html>
