
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
		var script= "get_images.php";
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
		var imageRelPath = this.id;
		loadSpinner();
		
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
			var response = this.response;
			var output = '<img src = "' + response['photo_path'] + '" class = "img-fluid" id = "' + response['photo_path'] + '" /> <div id = "metadata"></div> <button id = "home_button" type="button" >Show all Photos </button>' 
			photos.innerHTML= output;
			var home = document.getElementById('home_button');
			home.addEventListener("click", allPhotos);	
			var metadata = document.getElementById('metadata');
				
			//outputs JSON object (metadata) currently only displays limited data
			var output ="";
			for (var key in response) {
				//display the path as hidden element
			    if (response.hasOwnProperty(key)) {
					if ( key === 'photo_path' ){
						output += "<input type='hidden' id = '" + key + "' value = '" + response[key]  + "'  /> ";
					}
					//display all other editable data as placeholders in input elements
					else {
						if ( key === 'rating' ){
							//add code for star ratti
						}
						else{
						output += "<div> <label>" + key + ": <input type='text' id = '" + key + "' value= '" + response[key]  + "' name='" + key + "'  size='40'/></label> </div> \n";
						}
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
			metaArray["subject"] = document.getElementById('subject').value;
			//metaArray["rating"] = document.getElementById('rating').value;
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
			loadSpinner();
			//xhr request that will update and reload the metadata
			var xmlhr1 = new XMLHttpRequest();
			xmlhr1.addEventListener("load", displayMeta);
			xmlhr1.open("GET",script);
			xmlhr1.responseType = "json";
			xmlhr1.send();
	}

	
	//used to display all photos in the root directory
	function allPhotos() {
		var xmlhr1 = new XMLHttpRequest();
		var script= "get_images.php";
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
		var script= "get_images.php";
		
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
