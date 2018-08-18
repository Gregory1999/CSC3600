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
		<div class="jumbotron" id="photos">
			<div class="preload">
				
				<div class="loader-frame">
				</div>
			
			</div>
		
		</div>
		</div>
	</div>

	<script>




		
	var photos= document.getElementById('photos');
	var script= "get_images.php";	
	var script2= "get_directory.php";			
 	var xmlhr1 = new XMLHttpRequest();

		
	// This function will be used if the director is not set	-currently just loads photos from hard coded location
	function loadDirectory() {
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				
				var response = this.response;
				var output = "";
				
				
				
				for (var i = 0; i < response.imageArray.length; i++)  {
					output += '<img src = "' + response.imageArray[i] + '"  class="img-thumbnail" /> \n';
					
				}
				
				photos.innerHTML= output;
			}
		};
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}
			
		
		
	//This will load all of the photos from the chosen directory
	xmlhr1.onreadystatechange = function() {
		if ((this.readyState == 4) && (this.status == 200)) {

			
			var response = this.response;
			
			//call function to load directory
			
			if (response.root == 'NULL' ) {
					
					loadDirectory();
					 
			}	
			else {
				
				var output = "";
				
				
				
				for (var i = 0; i < response.imageArray.length; i++)  {
					output += '<img src = "' + response.imageArray[i] + '" class="img-thumbnail" /> \n';
					
				}
				
				photos.innerHTML= output;
			}
				
		}
	};
		
	xmlhr1.open("GET", script, true);
	xmlhr1.responseType = "json";
	xmlhr1.send();
 		 		
 	</script>	
 	
	</body>
</html>