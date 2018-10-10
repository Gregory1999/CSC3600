	
	//used to sort photos from oldest to newest
	function sortOldFirst() {
		var xmlhr1 = new XMLHttpRequest();
		var script= "get_images.php?reverse=true";
		xmlhr1.addEventListener("load", loadPhotos);	
		loadSpinner(); 
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}
	

	//this function will delete the selected root pathname from the db
	//it is called from the nerd page and will return the results to the nerd page
	function delete_folder(root_path){
		var root = root_path;
		var script = "scripts/db_stats.php?delete=" + root;
		
		//retrieve and db stats and display 
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.addEventListener("load", display_db_stats);
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		loadSpinner();
		xmlhr1.send();
	}
	
	//This function will retrieve the db stats and then call the function to display the data
	function get_db_stats(){
		var script = "scripts/db_stats.php";
		
		//retrieve and db stats and display 
		var xmlhr1 = new XMLHttpRequest();
		xmlhr1.addEventListener("load", display_db_stats);
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}

	//used to deturmine if the root directory is loaded
	//sets up XHR function that will check for root dir, 
	//if not loaded will prompt user and redirect them to the scan page
	function isScan(){
		// Check if initial scan has been carried out
		var xmlhr1 = new XMLHttpRequest();
		var script= "get_images.php";
		xmlhr1.addEventListener("load", findRoot);		
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		xmlhr1.send();
	}
	//XHR callback funtion that will display an alert and redirect user to scan page if no root selected
	function findRoot(){
		if ((this.readyState == 4) && (this.status == 200)) {
				var response = this.response;
				
				//if the root directory has not been loaded then call function to load directory
				if (response.root == 'NULL') {					
					
					alert("No Root folder selected. You will be redirected to the Scan Images page");
					scan_page();							
					
				}
		}
	}
	
	//This an XHR call back function that will display the returned db stats
	function display_db_stats(){
		var response = this.response;
		var numImage = response.image_count;
		var dbSize = response.db_size;
		var output="";
		
		if (response.root_path.length == 0){
			output += "\n<p><label>Root Path: </label> No Folders Selected</p>";
		}
		else{
			for (var root_path of response.root_path){
			output += '\n<p><label>Root Path: </label> ' + root_path + ' <button id ="' + root_path +'" type="button" class="btn btn-primary btn-md" onclick=\'delete_folder("' + encodeURI(root_path) +'")\' >Remove</button></p>';
			}
		}
		output += "\n<p><label>Number of Images in Database: </label> " + numImage + "</p>";
		output += "\n<p><label>Size of Database:</label> " + dbSize + " bytes</p>";
		photos.innerHTML= output;
	}
	
	
	//This function is called when the search button is pressed
	//The function will display all images that match the search string
	function sendAdvancedSearch(){
			//Hide form 
			document.getElementById("advancedSearch").style.display = "none";
			
			var advanced_search_txt= [];
			
			//advanced_search_txt["date_created"] = document.getElementById('created').value;
			advanced_search_txt["from_date_created"] = document.getElementById('createdFrom').value;
			advanced_search_txt["to_date_created"] = document.getElementById('createdTo').value;
			advanced_search_txt["photo_name"] = document.getElementById('fileName').value;
			advanced_search_txt["photo_type"] = document.getElementById('photoType').value;
			//advanced_search_txt["date_modified"] = document.getElementById('dateModified').value;
			advanced_search_txt["from_date_modified"] = document.getElementById('dateModifiedFrom').value;
			advanced_search_txt["to_date_modified"] = document.getElementById('dateModifiedTo').value;
			advanced_search_txt["size"] = document.getElementById('fileSize').value;
			advanced_search_txt["title"] = document.getElementById('photoTitle').value;
			advanced_search_txt["comments"] = document.getElementById('photoComments').value;
			advanced_search_txt["subject"] = document.getElementById('photoSubject').value;
			advanced_search_txt["rating"] = document.getElementById('photoRating').value;
			advanced_search_txt["tags"] = document.getElementById('photoTags').value;
			advanced_search_txt["authors"] = document.getElementById('author').value;
			// advanced_search_txt["date_taken"] = document.getElementById('dateTaken').value;
			advanced_search_txt["from_date_taken"] = document.getElementById('dateTakenFrom').value;
			advanced_search_txt["to_date_taken"] = document.getElementById('dateTakenTo').value;
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
			var addButton= $('<a href="#" class="btn btn-primary btn-md" onclick="advanced_search_page()">New Search</a>');
			$("#advancedSearchForm").append(addButton);
			loadSpinner();
			//loadSpinner();
			xmlhr1.send();		
	}
	// This function will delete the database and then promopt the user to enter new root directory
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
			lower_container.innerHTML= 			'<div class="jumbotron" id="photos">\
												</div>';
			xmlhr1.addEventListener("load", loadPhotos);

			xmlhr1.open("GET",script1+'?simple='+ simple_search_txt);
			xmlhr1.responseType = "json";
			loadSpinner();
			xmlhr1.send();
	}

	// This function will be used if the root directory is not set
	// It retrieves the root directory from the user
	function loadDirectory() {
		navbar.innerHTML= '<li><a href="#" onclick="home_page()">Home</a></li>\
									<li class="active"><a href="#" onclick="scan_page()">Scan Images</a></li>\
									<li><a href="#"  onclick="browse_page()">Browse Images</a></li>\
									<li><a href="#" onclick="nerd_page()">Nerdy Stuff</a></li>   \
									<li><a href="#" onclick="advanced_search_page()">Advanced Search</a></li> ';
		simple_search.innerHTML=		'<input id="simple_search_input" class="userInput" type="text" placeholder="Search.." name="search">\
												<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm"> Search</button>';
		upper_container.innerHTML=		'<div class="well"> \
													<h2>Image Library</h2>\
													<p>Select the directory you wish to scan images from, and select loaded images to add metadata to them.</p>\
												</div>';
		lower_container.innerHTML=		'<div class="jumbotron" id="photos">\
												</div>';
		enable_simple_search();
		var xmlhr1 = new XMLHttpRequest();
		var script= "get_images.php";
		xmlhr1.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var xmlhr1 = new XMLHttpRequest();
				var response = this.response;
				
				var output = '<div id="folder_list" > <label for="rootDirectory" id="lblrootDirectory">Enter Drive or root directory &nbsp;</label><input id="rootDirectory" class="userInput" type="text"> <button id = "browse" type="button" class="btn btn-primary btn-md" >Select Photo Library Drive</button> </div>'
				photos.innerHTML= output;
				// when button is pressed, send the directory
				
				var browse_btn = document.getElementById('browse');
				var folder_list= document.getElementById('folder_list');
				browse_btn.addEventListener("click", findFolder);
			}
		};
		xmlhr1.open("GET", script, true);
		xmlhr1.responseType = "json";
		loadSpinner();
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
				//If there are no results on a search Message: Try Again
				if(response.imageArray.length < 1){
					alert("There were no search results. Try Again!");
				}
				
				// Hide Label and Textbox
				//document.getElementById('rootDirectory').style.display = "none";
				//document.getElementById('lblrootDirectory').style.display = "none";
				var previousImageYear = 1970;
				var output = "<div class= 'row display-flex' >";
				for (var i = 0; i < response.imageArray.length; i++)  {
					var imagePath =  response.imageArray[i].path;
					var imageDate =  response.imageArray[i].date;
					//get the year 
					var imageYear = imageDate.substr(0, 4);
					//if new year add a year heading to output
					if (previousImageYear != imageYear){
						//if no year info provided, output 'no data heading'
						if(imageYear == ""){
							output += "<div class='col-sm-12' style ='width: 100%'><h2> No Date Metadata </h2> </div> \n";
						}
						else{
							output += "<div class='col-sm-12' style ='width: 100%' ><h2 > " + imageYear + " </h2> </div>\n";
						}
						previousImageYear= imageYear;
					}
					
					output += '<div class="col-sm-3 thumbnail">  <img src = "scripts/thumbnail.php?path=' + imagePath + '" style = "display:block; margin: auto; width: 90%; min-height: 40%; max-height: 90%;" id = "' + imagePath + '" />  </div>\n';
				}
				output += "</ div>";
				
				//output the images
				photos.innerHTML= output;

				//set up the event listeners
				for (var i = 0; i < response.imageArray.length; i++)  {
					var imagePath=  response.imageArray[i].path	;	
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
		edit_page();
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
			var output = '<button id = "home_button" class="btn btn-primary btn-md" type="button">Show all Photos</button> <br><br><img src = "' + script1 + '?path=' + response['photo_path'] + '" class = "img-fluid" id = "' + response['photo_path'] + '" /> <div id = "metadata"></div><button id = "home_button1" class="btn btn-primary btn-md" type="button" >Show all Photos </button>' 
			photos.innerHTML= output;
			var home = document.getElementById('home_button');
			home.addEventListener("click", browse_page);
			var home = document.getElementById('home_button1');
			home.addEventListener("click", browse_page);				
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
							if(getBrowserName() == "Firefox"){
								var time_taken = response[key].substr(11, 9);
								var formatDate = response[key].replace(new RegExp(':', 'g'), "-");
								var date_taken = formatDate.substr(0,10) ;
								
								output += "<br><div> <label for='date_taken'></label> Date Taken: <input class='form-control' type='date' id = 'date_taken' value= '" + date_taken + "' name='" + key + "' /> </div> \n\
										   <br><div><label for='time_taken'></label>Time Taken: <input class='form-control' type='time' id = 'time_taken' value= '" + time_taken + "' name='" + key + "' /> </div> \n ";
							}
							else{
								output += "<br><div> <label for='" + key + "'></label>" + key + ": <input class='form-control' type='datetime-local' id = '" + key + "' value= '" + response[key]  + "' name='" + key + "' /> </div> \n";
							}
						}
						else{
							output += "<br><div> <label for='" + key + "'></label>" + key + ": <input type='text' class='form-control' id = '" + key + "' value= '" + response[key]  + "' name='" + key + "'  /> </div> \n";
							

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
					if(key == 'date_taken' ){
						var formatedDate = metaArray[key].replace(new RegExp('-', 'g'), ":");
						if(getBrowserName() == "Firefox"){
							var time_taken = document.getElementById('time_taken').value;
							formatedDate += " " + time_taken;
							script += '&' + key + '=' + formatedDate;
						}
						else{
							script += '&' + key + '=' + metaArray[key];
						}
						
					}
					else{
						script += '&' + key + '=' + metaArray[key];
					}
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
		
	//this function will retrieve the user drive and then display and direct child dirs
	function findFolder() {
		var script = "scripts/browse.php";
		var testDir = document.getElementById("rootDirectory").value;
		var letters = 'abcdefghijklmnopqrstuvwxyz';
		//if no drive entered then alert the user
		if(testDir == ""){
			alert("You must enter the drive or root directory of the image folder. (eg. 'c')\nTry Again");
		}
		
		else if(testDir.length == 1){
			testDir.toUpperCase();
			var index = letters.indexOf(testDir);

			if(index >= 0){
				testDir += ':';				
			}else{
				alert("You need to enter a letter for the drive (eg. 'c')");				
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
	//used to retrieve the users root directory of the image library
	function displayPath() {
		var script = "scripts/browse.php";
		var response = this.response;
		var output = '<div id="browsedirectory"> <div><button id = "drive" class="btn btn-primary btn-md" type="button" >Change Library Drive</button></div> \
		<H3>Current Directory- <strong id="current">' + response.currentDirectory + '</strong> </H3> \
		<button id = "selectBtn" type="button" class="btn btn-primary btn-md">Select As Root Folder</button> \n ';
		
		if ( response.parentDirectory != response.currentDirectory ){
			output += '&nbsp;&nbsp;<button class="btn btn-primary btn-md" id = "backBtn" name="' + response.parentDirectory + '" type="button" >Back</button> ';
		}
		//adds the directories to the list
		output += '<list>';
		for (var i = 0; i < response.directoryArray.length; i++)  {
			var directoryPath =  response.directoryArray[i]; 					
			output += '<ul> &#8627; <a href="#" id = "' + directoryPath + '" /> ' + directoryPath + ' </a> </ul> \n';		
		}
		output += "</list> </div>";
		//output the folders
		folder_list.innerHTML= output;
		//prevents user from selecting a drive
		if(response.currentDirectory.length <=2){
			document.getElementById("selectBtn").style.display = "none";
		}
		
		var selectDriveBtn = document.getElementById('drive');
		selectDriveBtn.addEventListener("click", loadDirectory);
		
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
	
	//this function will send the directory root
	//the return images will then be displayed using loadphotos()
	function sendRoot() {		
		var xmlhr1 = new XMLHttpRequest();
		//var root =  document.getElementById('root').value;
		var root = document.getElementById("current");
		root = root.innerHTML;		
		var script= "get_images.php";
		
		//xmlhr1.addEventListener("load", loadPhotos);	
		xmlhr1.addEventListener("load", confirmDirLoad);	

    	xmlhr1.open("GET",script+'?root='+ root);
		xmlhr1.responseType = "json";
		loadSpinner();
		xmlhr1.send();
	}
	
	//
	function confirmDirLoad(){
/*
		lower_container.innerHTML=		'<div class="jumbotron" id="photos">\
											<h3> Folder Succefully Loaded</h3>\
											<button onclick="loadDirectory()">Scan Another Folder</button>\
												</div>';
*/
		alert("Folded succefully loaded. Select the browse button to view images");
		scan_page();
	}
	
	//this function will return the list of directories from the parent folder of the current directory
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
	
	//this function will recursively delete of options in a select statement except for the first option
	function deleteOptions(selectElement){
			var firstOption = selectElement.firstChild;
			if( firstOption ) {
				var nextOption = firstOption.nextSibling;
				if( nextOption ) {
					selectElement.removeChild(nextOption);
					deleteOptions(selectElement);
				}
			}
		}
				

	//this function adds options to a select statement
	function newOption(selectElement, value, textValue) { 
		var newOption=document.createElement("option");
		var textNode=document.createTextNode(textValue);

		newOption.setAttribute("value",value);
		newOption.appendChild(textNode);
		selectElement.appendChild(newOption);
	}
		
	//this is an XHR callback function that will retreive the valid options and then calls newOption() to add the options into the select statement
	function addOptions(){
		if( this.status != 200 ) {
			return;
		}
		var maker = document.getElementById('cameraMaker');	
		var model = document.getElementById('cameraModel');
		//remove previous child options 
		deleteOptions(maker);
		deleteOptions(model);
		//deleteOptions(type);
			//add new options
		var makerArray = this.response.camera_maker;
		var modelArray = this.response.camera_model;
		//var typeArray = this.response.photo_type;
		//get the valid models
		if (modelArray.length != 0){
			for (var key in modelArray){
				newOption(model, modelArray[key], modelArray[key]);
			}
			//if only two child options then delete the placeholder
			if (model.childElementCount == 2){
				model.removeChild(model.firstChild);
			}
		}
		
		//get the valid makers
		if (makerArray.length != 0){
			for (var key in makerArray){
				newOption(maker, makerArray[key], makerArray[key]);
			}
			//if only two child options then delete the placeholder
			if (maker.childElementCount == 2){
				maker.removeChild(maker.firstChild);
			}
		}
	}
	
	//used to dynamically add options to selectors in the search form
	//this function sets up the XHR and sends the selector GET data to the php script
	//the XHR function will then populate the returned options in the selectors
	function getOptions(){
		var maker = document.getElementById('cameraMaker');	
		var model = document.getElementById('cameraModel');
		var script = "scripts/advanced_fields.php";
		var camera_maker = maker.value;
		var camera_model = model.value;
		var xhr= new XMLHttpRequest();
		xhr.addEventListener("load", addOptions);
		xhr.open("GET", script + '?camera_model=' + camera_model + '&photo_type=&camera_maker='  + camera_maker);
		xhr.responseType = "json";
		xhr.send();
	}
	
	//function is called when reset selector button is pressed
	//funtion will reset selectors to initial settings
	function reset_selectors(){
		var maker = document.getElementById('cameraMaker');	
		var model = document.getElementById('cameraModel');
		maker.innerHTML = '';
		model.innerHTML = '';
		var script = "scripts/advanced_fields.php";
		newOption(maker, "", "Select a Camera Maker");
		newOption(model, "", "Select a Camera Model");
		var xhr= new XMLHttpRequest();
		xhr.addEventListener("load", addOptions);
		
		xhr.open("GET", script + '?camera_model=&photo_type=&camera_maker=');
		xhr.responseType = "json";
		xhr.send();
	}

	// this function will display the loading spinner 	
 	function loadSpinner() {
 		photos.innerHTML = "<div class='preload'> <div class='loader-frame'> </div></div> ";
 	}
	
	//this function is used to Detect the users browser
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
