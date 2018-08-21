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
 	
 	//calls the search function when the search button is clicked
 	simple_search_btn.addEventListener("click", sendSearch);
	
	allPhotos();
	
	//This function is called when the search button is pressed
	//The function will display all images that match the search string
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

	// This function will be used if the root directory is not set
	function loadDirectory() {
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var xmlhr1 = new XMLHttpRequest();
				var response = this.response;
				
				//should be a better way to get the path- this is just temporary, so that I can build the back-end
				var output = '<div><label>Full Directory Path: <input type="text" id = "root" value= "Test_Images" name="root_path" required="required" size="40"/></label> <button id = "root_button" type="button" >Load Root Directory </button></div>';				
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
	
	
	//This will load all of the photos from the chosen directory
	//The function will retrieve the images paths and set event listeners to each image
	//if the root has not been it will call the function to set the root path
	function loadPhotos() {
		if ((this.readyState == 4) && (this.status == 200)) {
			var response = this.response;
			
			//if the root directory has not been loaded then call function to load directory
			if (response.root == 'NULL' ) {			
					loadDirectory();			 
			}
			//insert all photos and add event listeners to call function when image is clicked	
			else {			
				var output = "<div class= 'row display-flex' >";
				for (var i = 0; i < response.imageArray.length; i++)  {
					var imagePath =  response.imageArray[i];
					output += '<div class="col-xs-4 thumbnail">  <img src = "' + imagePath + '" class = "img-fluid" id = "' + imagePath + '" />  </div>\n';
				}
				output += "</ div>";
				
				//output the images
				photos.innerHTML= output;
				
				//set up the event listeners
				for (var i = 0; i < response.imageArray.length; i++)  {
					var imagePath=  response.imageArray[i]	;	
					var imageId = document.getElementById(imagePath);
					imageId.addEventListener("click", imageClicked);
					//may add another event listener for hover events
				}				
				
						
			}
				
		}
	}
	
	//this function is called when an image is clicked
	//the function will retrieve all metadata then display the image with its metadata
	function imageClicked() {
		var script = "scripts/get_meta.php";
		var imagePath = this.src;
		var imageRelPath = this.id;
		var output = '<img src = "' + imagePath + '" class = "img-fluid" id = "' + imagePath + '" /> <div id = "metadata"></div> <button id = "home_button" type="button" >Show all Photos </button>' 
		photos.innerHTML= output ;
		// show all photos is button is pressed
		var home = document.getElementById('home_button');
		home.addEventListener("click", allPhotos);
		
		//retrieve and add metadata
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var metadata = document.getElementById('metadata');
				
				//currently only displays date taken- will extend
				var response = this.response.Date_Taken;
				
				//format the metadata
				//output the metadata
				var output = "<label>Date Taken:  </label>" + response;				
				metadata.innerHTML= output;
			}
		};
		xmlhr1.open("GET", script+'?path='+imageRelPath, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
		
	}
	
	
	//used to display all photos in the root directory
	function allPhotos() {
		xmlhr1.addEventListener("load", loadPhotos);	
		loadSpinner(); 
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}
	
	//this function will send the directory root
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
 		photos.innerHTML = "<div class='preload'> <div class='loader-frame'> </div></div> ";
 	}
 		 		
 	</script>	
 	
	</body>
</html>