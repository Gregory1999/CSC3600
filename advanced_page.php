<?php
// initialise database
$db = new SQLite3('site.db');

// camera manufacturer query
$query1 = "SELECT DISTINCT camera_maker FROM photo_camera WHERE camera_maker IS NOT NULL AND camera_maker <> ''";
$result1 = $db->query($query1);

// camera model query
$query2 = "SELECT DISTINCT camera_model FROM photo_camera WHERE camera_model IS NOT NULL AND camera_model <> ''";
$result2 = $db->query($query2);

// file type query
$query3 = "SELECT DISTINCT photo_type FROM photo_file WHERE photo_type IS NOT NULL AND photo_type <> ''";
$result3 = $db->query($query3);
?>
<!DOCTYPE html> <!-- HTML 5 delcaration so browser knows what to expect -->
<html lang="en">
	<head>
  		<title>Web-based Image Organiser</title>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="css/bootstrap.min.css">
  		<link rel="stylesheet" href="css/stylesheet1.css">
		<script src="js/jquery.min.js"></script>
  		<script src="js/bootstrap.min.js"></script>
		<script src="js/site.js"></script>
	</head>
	<body>
		<header>
			<div class="container-fluid">
				<div class="col-sm-4" style="background-color:white;"><img src="images/Mark_IV_logo.png" alt="Company Logo" style="width:180px;height:80px;"></div>
				<div class="col-sm-8" style="background-color:white;"><h1 class ="navbar-project">Web-based Image Organiser</h1></div>
			</div>
		</header>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<ul class="nav navbar-nav">
					<li><a href="index_v0.html">Home</a></li>
					<li><a href="upload_page.html">Scan Images</a></li>
					<li><a href="browse_page.html">Browse Images</a></li>
					<li><a href="metadata_page.html">Edit Metadata</a></li>
					<li><a href="nerd_page.html">Nerdy Stuff</a></li>
					<li class="active"><a href="advanced_page.html">Advanced Search</a></li>
				</ul>
				<!-- <form class="navbar-form navbar-right" action="/action_page.php"> -->
	    <div class="search">
	    	<input id="simple_search_input" class="userInput" type="text" placeholder="Search.." name="search">
      		<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm"> Search</button>
	    </div>
			</div>
				<!-- </form> -->
		</nav>
		<div class="container">
			<div class="well"> <!-- Place to put information for each page -->
				<h2>Welcome</h2>
				<p>Site instructions go here</p>
			</div>
		</div>
			<div class="container">
				<div class="jumbotron" id="photos">
				<h2>Advanced Search</h2>
				<p>Use the fields below to search for photos based on metadata.</p>
				<br>
				<!-- Backend code should allow for fields to be updated dynamically -->
				<div class="advsearch">
					<form name="advsearchform" action="" method="POST">
						<div class="form-group">
						<label for="tags">Tags:</label>
	  				<input type="text" class="form-control" id="tags">
						<br>
						<label for="comments">Description:</label>
	  				<input type="text" class="form-control" id="comments">
						<br>
  					<label for="sel1">Camera Manufacturer:</label>
							<?php
								echo '<select class="form-control" id="camera_maker">';
								echo '<option>Any</option>';
								while ($row1 = $result1->fetchArray(SQLITE3_ASSOC)) {
    						echo '<option value="'.$row1['camera_maker'].'">'.$row1['camera_maker'].'</option>';
							}
							echo "</select>";
							?>
  						</select>
							<br>
							<label for="sel1">Camera Model:</label>
							<?php
								echo '<select class="form-control" id="camera_model">';
								echo '<option>Any</option>';
								while ($row2 = $result2->fetchArray(SQLITE3_ASSOC)) {
    						echo '<option value="'.$row2['camera_model'].'">'.$row2['camera_model'].'</option>';
							}
							echo "</select>";
							?>
							<br>
							<label for="sel1">File Type:</label>
							<?php
								echo '<select class="form-control" id="photo_type">';
								echo '<option>Any</option>';
								while ($row3 = $result3->fetchArray(SQLITE3_ASSOC)) {
    						echo '<option value="'.$row3['photo_type'].'">'.$row3['photo_type'].'</option>';
							}
							echo "</select>";
							?>
							</div>
							<button type="submit" class="btn btn-primary btn-md">Search</button>
						</form>
						</div>
				</div>
			</div>
</html>
