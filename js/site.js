	//var count = 0;
	//This function is called when the search button is pressed
	//The function will display all images that match the search string
	function sendAdvancedSearch(){
			//Hide form 
			document.getElementById("advancedSearch").style.display = "none";
			
			var advanced_search_txt= [];
			
			advanced_search_txt["date_created"] = document.getElementById('created').value;
			advanced_search_txt["photo_name"] = document.getElementById('fileName').value;
			advanced_search_txt["photo_type"] = document.getElementById('photoType').value;
			advanced_search_txt["date_modified"] = document.getElementById('dateModified').value;
			advanced_search_txt["size"] = document.getElementById('fileSize').value;
			advanced_search_txt["title"] = document.getElementById('photoTitle').value;
			advanced_search_txt["comments"] = document.getElementById('photoComments').value;
			advanced_search_txt["subject"] = document.getElementById('photoSubject').value;
			advanced_search_txt["rating"] = document.getElementById('photoRating').value;
			advanced_search_txt["tags"] = document.getElementById('photoTags').value;
			advanced_search_txt["authors"] = document.getElementById('author').value;
			advanced_search_txt["date_taken"] = document.getElementById('dateTaken').value;
			advanced_search_txt["copyright"] = document.getElementById('copyright').value;
			advanced_search_txt["width"] = document.getElementById('photoWidth').value;
			advanced_search_txt["height"] = document.getElementById('photoHeight').value;
			advanced_search_txt["compression"] = document.getElementById('photoCompression').value;
			advanced_search_txt["camera_maker"] = document.getElementById('cameraMaker').value;
			advanced_search_txt["camera_model"] = document.getElementById('cameraModel').value;
			advanced_search_txt["camera_serial_number"] = document.getElementById('cameraSerialNumber').value;

			var xmlhr1 = new XMLHttpRequest();
			var script1 = 'scripts/adv_search.php?';
			xmlhr1.addEventListener("load", loadPhotos);
		
			var count = 0;
			
			for(var key in advanced_search_txt){
				if(count > 0)
					script1 += '&';
				// if value is empty pass empty string
				//else pass the string
				if(advanced_search_txt[key] == ""){
					script1 += key + '=' + '';
				}else{
					script1 += key + '=' + advanced_search_txt[key];
				}
				count++;
			}
			xmlhr1.open("GET",script1);
			xmlhr1.responseType = "json";			

			//Dynamically back New Search button
			var addButton= $('<a href="advanced_page.html" class="btn btn-primary btn-md">New Search</a>');
			$("#advancedSearchForm").append(addButton);
			
			//loadSpinner();
			xmlhr1.send();		
	}
	
	function deleteDatabase(event) {
		event.preventDefault(); 
		var xmlhr1 = new XMLHttpRequest();
		var script= "scripts/delete_db.php";
		xmlhr1.onreadystatechange = function() {
			//document.getElementById('rootDirectory').style.display = "inline-block";
			//document.getElementById('lblrootDirectory').style.display = "inline-block";
			xmlhr1.addEventListener("load", loadPhotos);
		}
		xmlhr1.open("GET",script);
				
		xmlhr1.responseType = "json";
		loadSpinner();
		xmlhr1.send();
	}

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
				//var output = '<div><label>Full Directory Path: <input type="text" id = "root" value= "Test_Images" name="root_path" required="required" size="40"/></label> <button id = "root_button" type="button" >Load Root Directory </button></div>';				
				var output = '<div id="folder_list" > <label for="rootDirectory" id="lblrootDirectory">Enter Drive or root directory</label><input id="rootDirectory" class="userInput" type="text"> <button id = "browse" type="button" class="btn btn-primary btn-md" >Select Photo Library Drive</button> </div>'
				photos.innerHTML= output;
				// when button is pressed, send the directory
				
				var browse_btn = document.getElementById('browse');
				var folder_list= document.getElementById('folder_list');
				browse_btn.addEventListener("click", findFolder);
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
			if (response.root == 'NULL') {			
					loadDirectory();					
			}
			//insert all photos and add event listeners to call function when image is clicked	
			else {
				// Hide Label and Textbox
				//document.getElementById('rootDirectory').style.display = "none";
				//document.getElementById('lblrootDirectory').style.display = "none";
				
				var output = "<div class= 'row display-flex' >";
				for (var i = 0; i < response.imageArray.length; i++)  {
					var imagePath =  response.imageArray[i];
					output += '<div class="col-sm-3 thumbnail">  <img src = "scripts/thumbnail.php?path=' + imagePath + '" style = "display:block; margin: auto; width: 90%; min-height: 40%; max-height: 90%;" id = "' + imagePath + '" />  </div>\n';
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
			var script1 = "scripts/full_Image.php";
			var response = this.response;
			var output = '<img src = "' + script1 + '?path=' + response['photo_path'] + '" class = "img-fluid" id = "' + response['photo_path'] + '" /> <div id = "metadata"></div> <button id = "home_button" class="btn btn-primary btn-md" type="button" >Show all Photos </button>' 
			photos.innerHTML= output;
			var home = document.getElementById('home_button');
			home.addEventListener("click", allPhotos);	
			var metadata = document.getElementById('metadata');
				
			//outputs JSON object (metadata) currently only displays limited data
			var output ='<br><div class="form-group"> <fieldset> <legend class="lHeader">Photo Metadata</legend>';
			for (var key in response) {
				//display the path as hidden element
			    if (response.hasOwnProperty(key)) {
					if ( key === 'photo_path' ){
						output += "<input type='hidden' id = '" + key + "' value = '" + response[key]  + "'  /> ";
					}
					//display all other editable data as placeholders in input elements
					else {
						if ( key === 'rating' ){
							//add code for star rating
						}
						//will improve this later- need to add formatting criteria to enforce correct date format.
						else if (key === 'date_taken'){
// <<<<<<< HEAD
							//Try get date working on firefox and chrome
							if(getBrowserName() == "Chrome")
							{
								var newDtString = response[key];
								response[key] = "";
								var count = 0;
								
								for(i=0; i < newDtString.length - 3; i++)
								{
									if(newDtString[i] == ':' && count <= 1)
									{
										response[key] += '-';
										count++;
									}
									else if(newDtString[i] == ' ')
									{
										response[key] += 'T';
									}
									else if(newDtString[i] != ' ')
									{
										response[key] += newDtString[i];
									}									
								}																
							}
							output += "<br><div> <label for='" + key + "'></label>" + key + ": <input class='form-control' type='datetime-local' id = '" + key + "' value= '" + response[key]  + "' name='" + key + "' /> </div> \n";
						}
						else{
							output += "<br><div> <label for='" + key + "'></label>" + key + ": <input type='text' class='form-control' id = '" + key + "' value= '" + response[key]  + "' name='" + key + "'  /> </div> \n";
							
/*							

=======
						output += "<br><div> <label for='" + key + "'></label>" + key + ": <input class='form-control' type='datetime-local' id = '" + key + "' value= '" + response[key]  + "' name='" + key + "' /> </div> \n";

						}
						else{
						output += "<br><div> <label for='" + key + "'></label>" + key + ": <input type='text' class='form-control' id = '" + key + "' value= '" + response[key]  + "' name='" + key + "'  /> </div> \n";
>>>>>>> 7e1577717b3302dea404f3d249e68a8665123f4d
*/
						}
					}
				}
			}
				
			output += "</fieldset> <div> <button id ='meta_edit_btn' type='button' class='btn btn-primary btn-md'>Save Metadata </button> </div> </div>\n";
			metadata.innerHTML = output;
			//add event listner to the metadata save button
			var save_btn = document.getElementById('meta_edit_btn');
			save_btn.addEventListener("click", saveMeta);
		}
	}
		
		
	//This function is used to save new metadata to the selected file
	//it sets up a xhr funtion to upload and then display the new data
	function saveMeta(){
			//store the value for each metadata field
			var metaArray = [];
			metaArray["title"] = document.getElementById('title').value;
			metaArray["comments"] = document.getElementById('comments').value;
			metaArray["tags"] = document.getElementById('tags').value;
			metaArray["subject"] = document.getElementById('subject').value;
			metaArray["authors"] = document.getElementById('authors').value;
			metaArray["date_taken"] = document.getElementById('date_taken').value;
			metaArray["copyright"] = document.getElementById('copyright').value;
			metaArray["camera_maker"] = document.getElementById('camera_maker').value;
			metaArray["camera_model"] = document.getElementById('camera_model').value;
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
		//var root =  document.getElementById('root').value;
		var root = document.getElementById("current");
		root = root.innerHTML;		
		var script= "get_images.php";
		
		xmlhr1.addEventListener("load", loadPhotos);	
    	xmlhr1.open("GET",script+'?root='+ root);
		xmlhr1.responseType = "json";
		loadSpinner();
		xmlhr1.send();
	}
		
	//this function will retrieve the user drive and then display and direct child dirs
	function findFolder() {
		var script = "scripts/browse.php";
		var testDir = document.getElementById("rootDirectory").value;
		var letters = 'abcdefghijklmnopqrstuvwxyz';
		//if no drive entered then alert the user
		if(testDir == ""){
			alert("You must enter the drive or rootdirectory of the image folder.\nTry Again");
		}
		
		else if(testDir.length == 1){
			testDir.toUpperCase();
			var index = letters.indexOf(testDir);

			if(index >= 0){
				testDir += ':';				
			}else{
				alert("You need to enter a letter for the drive");				
			}
		}
		//retrieve and add metadata and then display image
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.addEventListener("load", displayPath);
		xmlhr1.open("GET", script+'?directory=' + testDir, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}
	
	//this function will display the subfolders as hyper links
	function displayPath() {
		var script = "scripts/browse.php";
		var response = this.response;
		var output = '<div id="browsedirectory"> <div><button id = "drive" class="btn btn-primary btn-md" type="button" >Change Library Drive</button> </div> <H3>Current Directory- <strong id="current">' + response.currentDirectory + '</strong> </H3> \n <button id = "selectBtn" type="button" class="btn btn-primary btn-md">Select As Root Folder</button> \n ';
		
		if ( response.parentDirectory != response.currentDirectory ){
			output += '<button class="btn btn-primary btn-md" id = "backBtn" name="' + response.parentDirectory + '" type="button" >Back</button> ';
		}
		
		output += '<list> ';
		for (var i = 0; i < response.directoryArray.length; i++)  {
			var directoryPath =  response.directoryArray[i]; 					
			output += '<ul> <a id = "' + directoryPath + '" /> ' + directoryPath + ' </a> </ul> \n';		
		}
		output += "</list> </div>";
		//output the folders
		folder_list.innerHTML= output;
		
		var selectBtn = document.getElementById('drive');
		selectBtn.addEventListener("click", loadDirectory);
		
		var selectBtn = document.getElementById('selectBtn');
		selectBtn.addEventListener("click", sendRoot);
		
		if ( response.parentDirectory != response.currentDirectory ){
			var backBtn = document.getElementById('backBtn');
			backBtn.addEventListener("click", backBtnPress);
		}
		
		//set up the event listeners
		for (var i = 0; i < response.directoryArray.length; i++)  {
			var directoryPath =  response.directoryArray[i]	;	
			var linkId = document.getElementById(directoryPath);
			linkId.addEventListener("click", getSubDir);
		}
	}
	
	//this function will return the list of directories from the parrent folder of the current directory
	//this function is called when the back button is pressed
	function backBtnPress(){
		var script = "scripts/browse.php";
		var parentDir = document.getElementById("backBtn");
		var parentDir = parentDir.name;
		//retrieve the parent folders
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.addEventListener("load", displayPath);
		xmlhr1.open("GET", script+'?directory=' + parentDir, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}
	
	
	//this function will retrieve the full list of direct child directories	of the current directory
	function getSubDir(){
		var script = "scripts/browse.php";
		var linkId = this.id;
		//retrieve the subfolders
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.addEventListener("load", displayPath);
		xmlhr1.open("GET", script+'?directory=' + linkId, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}
	
	
	

	// this function will display the loading spinner 	
 	function loadSpinner() {
 		photos.innerHTML = "<div class='preload'> <div class='loader-frame'> </div></div> ";
 	}
	
	//Detecting browser
	function getBrowserName(){
var navAgent = navigator.userAgent;
var browserName;
if ((versionOffset=navAgent.indexOf("Opera"))!=-1) {
   browserName = "Opera";  
}
else if ((versionOffset=navAgent.indexOf("MSIE"))!=-1) {
   browserName = "Microsoft Internet Explorer";
}
else if ((versionOffset=navAgent.indexOf("Chrome"))!=-1) {
   browserName = "Chrome";
}
else if ((versionOffset=navAgent.indexOf("Safari"))!=-1) {
   browserName = "Safari";
}
else if ((versionOffset=navAgent.indexOf("Firefox"))!=-1) {
    browserName = "Firefox";
}
else if ( (nameOffset=navAgent.lastIndexOf(' ')+1) < (versionOffset=navAgent.lastIndexOf('/')) ) {
    browserName = navAgent.substring(nameOffset,versionOffset);
    if (browserName.toLowerCase()==browserName.toUpperCase()) {
       browserName = navigator.appName;
    }
}
return browserName;
}
