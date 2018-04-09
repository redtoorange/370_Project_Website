<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Researcher Data</title>
  

  <!-- Font Awesome -->
  <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Bootstrap Core Styling -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- DataTables Bootstrap Style -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/>
  
  <!-- Custom Styling -->
  <link href="css/admin.css" rel="stylesheet">
  <?php
	session_start();
	
	if(isset($_POST['logout'])) {
		unset($_SESSION['username']);
	}
  ?>

  
</head>
<body class="fixed-nav bg-dark" id="page-top">

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand mr-auto" href="index.html">Data Administration</a>
	<?php			
		if( isset($_SESSION['username']) ){
			echo '
			<form method="post">
				<button id="logoutButton" type="submit" name="logout" value="logout" class="btn btn-outline-danger my-2 my-sm-0">Logout</button>
			</form>';
		}
		else{
			echo '<button id="loginButton" class="btn btn-outline-success my-2 my-sm-0">Admin Login</button>';
		}
	?>
  </nav>

  
  
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
				<a class="btn btn-secondary" href="/admin/testFile.txt" role="button"  download> Download File </a>
				
				<?php
					if( isset($_SESSION['username']) ){
						echo '<button id="themeButton" type="button" class="btn btn-secondary">Change Themes</button>'."\n";
						echo '<button id="deleteButton" type="button" class="btn btn-danger">Delete Data</button>';
					}
				?>
				
			</div>
		</div> 
	</div>
	<!-- End Data Controls -->
  
	</br>

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
				  <th>Targets Hit</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Input</th>
                  <th>Age</th>
                  <th>Skill</th>
                  <th>Score</th>
				  <th>Targets Hit</th>
                </tr>
              </tfoot>
              <tbody>
				<?php
					require "../db_connect/info.php";
					
					//function to create a table
					$conn = ConnectToDB();

					// Query Data from the DB
					$query = 'SELECT * FROM  `test_data` ';
					$result = $conn->query($query);
					$numFields = $result->field_count;
					
					$myFile = "testFile.txt";
					$fo = fopen($myFile, 'w') or die("can't open file");

					// Create an Array of the col names from the DB
					$columns = array("ID", "input", "age", "skill", "score", "Targets");
					
					$rows = array();
					// Create the table rows from the DB Data
					while($row = $result->fetch_assoc()){
						$rows[] = $row;
						echo '<tr>';
						
						// For each Col in the DB data, create a col in the table
						for ($i = 0; $i < sizeof($columns); $i++){
							// Print the DB Data into the table data
							echo '<td>';
							echo $row[$columns[$i]];
							echo '</td>';
						}
						
						echo '</tr>';
					}
					
					fwrite($fo, json_encode( $rows, JSON_PRETTY_PRINT ) );
					fclose($fo);
				?>
              </tbody>
            </table>
          </div>
        </div>
		
		
        <div class="card-footer small text-muted">
			Updated yesterday at 11:59 PM
		</div>
      </div>
    </div>
	<!-- End Data Table -->
	

	<!-- Admin Login Modal -->
	<div class="modal fade" id="adminLogin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Admin Login</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  
		  <form method="post" action="../db_connect/admin_login.php" onsubmit="return admin_login();">
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
				<button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
				<button id="loginSubmit" type="sumit" class="btn btn-primary">Login</button>
			  </div>
		  </form>
		  
		</div>
	  </div>
	</div>
	
	<!-- Theme Selector Modal -->
	<div class="modal fade" id="themeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Select Active Game Themes</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  
		  <form method="post" action="../db_connect/setTheme.php" onsubmit="return change_theme();">
		  
			  <div class="modal-body">
				<div class="form-group">
					<div class="form-check">
						<input class="form-check-input" type="radio" name="themeSelect" id="bothThemes" value="both" checked>
						<label class="form-check-label" for="bothThemes">
							Both Themes
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="themeSelect" id="carnivalTheme" value="carnival">
						<label class="form-check-label" for="carnivalTheme">
							Carnival Theme
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="themeSelect" id="spaceTheme" value="space">
						<label class="form-check-label" for="spaceTheme">
							Space Theme
						</label>
					</div>
				</div>
			  </div>
			  
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
				<button id="themeSubmit" type="submit" class="btn btn-primary">Submit</button>
			  </div>
			  
		  </form>
		</div>
	  </div>
	</div>
	
	
	
	<!-- Delete Button Modal -->
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
			<p>You are about to delete all data.  <b>This action cannot be undone!</b></p>
		  </div>
		  
		  <div class="modal-footer">
			<form method="post" action="../db_connect/deleteData.php" onsubmit="return delete_data();">
				<button type="submit" class="btn btn-success">Yes, Delete Data</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</form>
		  </div>
		</div>
	  </div>
	</div>
	
	
    <!-- Bootstrap Core -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
	
	<script type="text/javascript">
		// Load the table into jQueryDataTables
		$(document).ready(function() {
		  $('#dataTable').DataTable();
		});
		
		// Display Login Panel
		$('#loginButton').click(function(){
			$('#adminLogin').modal('show');
		});
		
		// Display data delete warning
		$('#deleteButton').click(function(){
			$('#deleteModal').modal('show');
		});
		
		// Display data delete warning
		$('#themeButton').click(function(){
			$('#themeModal').modal('show');
		});
	</script>
	
<script type="text/javascript">
	function admin_login() {
		var user = $("#adminUsername").val();
		var pass  = $("#adminPassword").val();
		
		if(user != "" && pass != "") {
			$.ajax({
				type:'post',
				url:'../db_connect/admin_login.php',
				data:{ do_login: "do_login", username: user, password: pass },
				
				success:	function(response) {
								if(response=="success") {
									window.location.href="admin.php";
								}
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
	
	
	function delete_data() {
		$.ajax({
			type:'post',
			url:'../db_connect/deleteData.php',
			data:{ },
			
			success:	function(response) {
							if(response=="success") {
								window.location.href="admin.php";
							}
						}
		});
		
		return false;			
	}
	
	function change_theme() {
		var selectedTheme = "both";
		
		if( $("#carnivalTheme").is(":checked") ){
			selectedTheme = "carnival";
		}
		if( $("#spaceTheme").is(":checked") ){
			selectedTheme = "space";
		}
		
		$.ajax({
			type:'post',
			url:'../db_connect/setTheme.php',
			data:{ theme: selectedTheme },
			
			success:	function(response) {
							$('#themeModal').modal('hide');
						}
		});
		
		return false;			
	}

		
</script>
	
  </div>
</body>
</html>
