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
	var script= "get_images.php";				
 	var xmlhr1 = new XMLHttpRequest();
 	
 	//calls the search function when the search button is clicked
 	simple_search_btn.addEventListener("click", sendSearch);
	
	//uses ajax to load all photos
	allPhotos();
	
	//This function is called when the search button is pressed
	//The function will display all images that match the search string
	function sendSearch() {
			var simple_search_txt = document.getElementById('simple_search_input').value;
			var xmlhr1 = new XMLHttpRequest();
			var script1 = "scripts/search.php";
			xmlhr1.addEventListener("load", loadPhotos);

			xmlhr1.open("GET",script1+'?simple='+ simple_search_txt);
			xmlhr1.responseType = "json";
			loadSpinner();
			xmlhr1.send();
	}

	// This function will be used if the root directory is not set
	// It retrieves the root directory from the user
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
	
	//The is a xhr callback function
	//It will load all of the photos from the chosen directory
	//It will receive a JSON formated string containing the root directory and all the image paths
	//The function will display the images and set event listeners to each image
	//If the root has not been it will call the function to set the root path
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
					output += '<div class="col-xs-4 thumbnail">  <img src = "scripts/thumbnail.php?path=' + imagePath + '" class = "img-fluid" id = "' + imagePath + '" />  </div>\n';
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
		
		//retrieve and add metadata and then display image
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.addEventListener("load", displayMeta);
		xmlhr1.open("GET", script+'?path='+imageRelPath, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
		
	}
	
	// This is a xhr callback function
	// It is used to format the returned editable metadata allowing the user to update it
	// It will receive a JSON formatted string of metadata field-value pairs.
	function displayMeta() {
		if (this.readyState == 4 && this.status == 200) {
			var metadata = document.getElementById('metadata');
				
			//outputs JSON object (metadata) currently only displays limited data
			var output ="";
			var response = this.response;
			for (var key in response) {
				//display the path as hidden element
			    if (response.hasOwnProperty(key)) {
					if ( key === 'photo_path' ){
						output += "<input type='hidden' id = '" + key + "' value = '" + response[key]  + "'  /> ";
					}
					//display all other editable data as placeholders in input elements
					else {
						output += "<div> <label>" + key + ": <input type='text' id = '" + key + "' placeholder= '" + response[key]  + "' name='" + key + "'  size='40'/></label> </div> \n";
					}
				}
			}
				
			output += "<div> <button id ='meta_edit_btn' type='button' >Save Metadata </button> </div> \n";
			metadata.innerHTML = output;
			//add event listner to the metadata save button
			var save_btn = document.getElementById('meta_edit_btn');
			save_btn.addEventListener("click", saveMeta);
		}
	}
		
		
	//This function is used to save new metadata to the selected file
	//it sets up a xhr funtion to upload and then display the new data
	function saveMeta(){
		
			//store the value for each metadata field---------------------add more when db complete
			var metaArray = [];
			metaArray["title"] = document.getElementById('title').value;
			metaArray["comments"] = document.getElementById('comments').value;
			metaArray["tags"] = document.getElementById('tags').value;
			//store the image path
			var imagePath = document.getElementById('photo_path').value;
			//script that will update metadata in image file 
			
			var script = 'scripts/edit_meta.php?path='+ imagePath ;
			//add name-value pairs for all altered metadata to querry string
			for (var key in metaArray) {
				//if value is not empty add it to the querry string
				if (metaArray[key] != '') {
					script += '&' + key + '=' + metaArray[key];
				}
			}
			
			//xhr request that will update and reload the metadata
			//								<--- may improve this to confirm with user, and provide feedback
			var xmlhr1 = new XMLHttpRequest();
			xmlhr1.addEventListener("load", displayMeta);
			xmlhr1.open("GET",script);
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