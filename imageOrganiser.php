<!DOCTYPE html>
<html>
	<head>
  		<title>Web -based Image Organiser</title>
  		<meta charset="utf-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
  		<!--link rel="stylesheet" href="css/bootstrap.min.css">-->


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
	    		<input id="simple_search_input" class="userInput" type="text" placeholder="Search.." name="search">
      		<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm">
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
			
		
		</div>
		</div>
	</div>

	<script>
		
	var photos= document.getElementById('photos');
	var simple_search_btn = document.getElementById('simple_search_btn');
	var script= "get_images.php";				
 	var xmlhr1 = new XMLHttpRequest();
 	
 	simple_search_btn.addEventListener("click", sendSearch);
	
	//display the spinner while the photos load
	loadSpinner(); 
	

	function sendSearch() {
		
			var simple_search_txt = document.getElementById('simple_search_input').value;
			var xmlhr1 = new XMLHttpRequest();
			var script1 = "scripts/search.php";

			xmlhr1.onreadystatechange = function() {			
				if ((this.readyState == 4) && (this.status == 200)) {
					var response = this.response;
					var output = "";
					for (var i = 0; i < response.imageArray.length; i++)  {
						output += '<div class = "thumbnail"> <img src = "' + response.imageArray[i] + '" /> </div> \n';

					photos.innerHTML= output;
					}
				}
			}
			xmlhr1.open("GET",script1+'?simple='+ simple_search_txt);
			xmlhr1.responseType = "json";
			loadSpinner();
			xmlhr1.send();
	
	}

	// This function will be used if the root directory is not set	-currently just loads photos from hard coded location
	function loadDirectory() {
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var xmlhr1 = new XMLHttpRequest();
				var response = this.response;
				
				//should be a better way to get the path- this is just temporary, so that I can build the back-end
				var output = '<div><label>Full Directory Path: <input type="text" id = "root" value= "Test_Images" name="root_path" required="required" size="40"/></label> <button id = "root_button" type="button" >Load Root Directory </button></fdiv>';				
				photos.innerHTML= output;
				// when button is pressed, send the directory
				var root_button = document.getElementById('root_button');
				root_button.addEventListener("click", sendRoot);
			}
		};
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}	
	xmlhr1.addEventListener("load", loadPhotos);	

	xmlhr1.open("GET", script, true);
	xmlhr1.responseType = "json";
	xmlhr1.send();
	
		//This will load all of the photos from the chosen directory
	function loadPhotos() {
		if ((this.readyState == 4) && (this.status == 200)) {
			var response = this.response;
			
			//call function to load directory
			
			if (response.root == 'NULL' ) {			
					loadDirectory();			 
			}	
			else {			
				var output = "<div class= 'row' >";
				for (var i = 0; i < response.imageArray.length; i++)  {
					output += '<div class="col-xs-4"> <div class = "thumbnail"> <img src = "' + response.imageArray[i] + '" /> </div>  </div>\n';
				}
				output += "</ div>";
				photos.innerHTML= output;
			}
				
		}
	}
	

	// sends the directory root
	function sendRoot() {		
		var xmlhr1 = new XMLHttpRequest();
		var root =  document.getElementById('root').value;
		
		xmlhr1.addEventListener("load", loadPhotos);	
    	xmlhr1.open("GET",script+'?root='+ root);
   	xmlhr1.responseType = "json";
   	loadSpinner();
   	xmlhr1.send();
	}

	
	// this function will display the loading spinner 	
 	function loadSpinner() {
 		photos.innerHTML = "<div class='preload'> <div class='loader-frame'> </div></div>";
 	}
 		 		
 	</script>	
 	
	</body>
</html>