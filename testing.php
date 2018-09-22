<!DOCTYPE html> <!-- HTML 5 delcaration so browser knows what to expect -->
<html lang="en">
	<head>
  		<title>Web-based Image Organiser</title>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="css/bootstrap.min.css">
  		<link rel="stylesheet" href="css/stylesheet1.css">
  		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.min.js"></script>
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
      		<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm" onclick="sendMessage()"> Search</button>
	    </div>
			</div>
				<!-- </form> -->
		</nav>
		<div class="container">
				<h2>Welcome</h2>
				<p>Site instructions go here</p>
			</div>
		</div>
			<div class="container">
				<div class="jumbotron" id="photos">
				</div>
				<p id="test"></p>
			</div>
			
			<script>
			function sendMessage(){
				//document.getElementById("test").innerHTML = "hello";
				alert("ll");
			}
			</script>
</html>