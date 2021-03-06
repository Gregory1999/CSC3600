<!DOCTYPE html>
<html>
	<head>
  		<title>Web-based Image Organiser</title>
		<link rel="icon" href="images/markivicon.png">
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  		<link rel="stylesheet" href="css/stylesheet.css">
  		<script src="js/jquery.min.js"></script>
  		<script src="js/bootstrap.min.js"></script>
		<script src="js/site.js"></script>
	</head>
	<body>
	<header>
	<div>
		<img src="images/Mark_IV_logo.png" alt="" style="width:180px;height:80px;">
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
				
		</div>
		<div class="right">
	
		</div>	
		
		
		<div class="container">
		<div class="jumbotron" id="photos">
			
		
		</div>
		</div>
	</div>

	<script>
		
	var photos= document.getElementById('photos');
	var simple_search_btn = document.getElementById('simple_search_btn');
 	
 	//calls the search function when the search button is clicked
 	simple_search_btn.addEventListener("click", sendSearch);
	
	//uses ajax to load all photos
	allPhotos();
 		 		
 	</script>	
 	
	</body>
</html>