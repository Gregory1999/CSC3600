<!DOCTYPE html>
<html>
	<head>
  		<title>Web -based Image Organiser</title>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<!--link rel="stylesheet" href="css/bootstrap.min.css">-->
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  		<link rel="stylesheet" href="css/stylesheet.css">
  		<script src="js/bootstrap.min.js"></script>
  		<script src="js/jquery.min.js"></script>
	</head>
	<body>
	<header>
	<div>
		<img src="images/Mark_IV_logo.png" alt="">
	</div>
	<nav class="navbar navbar-inverse">
  		<div class="container-fluid">
    	<div class="navbar-header">
      	<a class="navbar-brand project" href="#">Web-based Image Organiser</a>
    	</div>
   	 <ul class="nav navbar-nav"><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      	<li class="active"><a href="#">Home</a></li>
      	<li><a href="#">Advanced Search</a></li>      		
	    </ul>
	    <div class="search">
	    		<input class="userInput" type="text" placeholder="Search.." name="search">
      		<button type="button" class="btn btn-default btn-sm">
          		<span class="glyphicon glyphicon-search"></span>
        		</button>
	    </div>
	  </div>
	</nav>
	</header>
	<div class="">
		<div class="left">
				
		</div>
		<div class="right">
	
		</div>
	
		<div class="container">
	
			
			
		<?php
			# I have used php to call modules but in the final application JS Ajax async calls will be used to call modules and receive and display results- 
			
			
			#if db does not exist create the db
			if (!file_exists('site.db')){
				include_once "./scripts/db_setup.php";
			}
			else {
				$db = new SQLite3('site.db');
			}
				
			#check for for directory path, if one does not exist then ask for user to select
			$query = "SELECT path, last_scan FROM root_directory";
			$result= $db->query($query);
			$row = $result->fetchArray(SQLITE3_ASSOC);
			$root_path= $row["path"];
			$last_scan = $row["last_scan"];
			
			#get root directory
			if (!$root_path){
				#this will contain the code to set root path
			
				#initially sets the last scan time to force initial scan
				$date = new DateTime('1970-01-01');
							
				#hard code root directory for testing- to be deleted
				$query="INSERT INTO root_directory(path, last_scan) VALUES ( './Test_Images', '" . $date->format('d-m-Y H:i:s') . "' ) ";
				$db->query($query);				
			
			
			}
			
			#display sorted images
			else {
				
				# scan file system script
				include_once "./scripts/file_scanner.php";
			
			
				#display images
				
				$query = "SELECT photo_path FROM photo";
				$result= $db->query($query);
				while( $row = $result->fetchArray(SQLITE3_ASSOC)) {
					$photo_path= $row["photo_path"];
					echo "<img src='$photo_path' class='img-thumbnail' >";
				
				}
				
				
				
			}

			
			
		?>
		
		</div>
	</div>


	</body>
</html>