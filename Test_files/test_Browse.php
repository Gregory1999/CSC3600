<!DOCTYPE html>
<html>
	<head>
  		<title>Web -based Image Organiser</title>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  		<link rel="stylesheet" href="css/stylesheet.css">
  		<script src="js/jquery.min.js"></script>
  		<script src="js/bootstrap.min.js"></script>

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
	    		<input id="simple_search_input" class="userInput" type="text" placeholder="Search.." name="search">
      		<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm">
          		<span class="glyphicon glyphicon-search"></span>Search
        		</button>
	    </div>
	  </div>
	</nav>
	</header>
	<div class="">
		<div class="left">
		<div class="row">
            <div class="column">
                <div id="selectSource">
				<form action="test_Browse.php" id="testing" method="post" enctype="multipart/form-data">
                    <input type="file" name="folderName" id="folderName" class="inputfile" webkitdirectory />
				</form>
				</div>
            </div>
        </div>
		</div>
		<div class="right">
		<form action="upload-script-url test_Browse.php" method="post" enctype="multipart/form-data">
			<input type="file" name="filePath">
			<input type="submit">
		</form>	
		</div>

			<?php

			?>		
		</div>
		</div>
	</div>
	</body>
</html>
<script type="text/javascript" >
$("#folderName").change(function() {
        var sourceVal = document.getElementById("folderName").files[0].path;
        $("#sourceDirPath").val(sourceVal);
    });
</script>
