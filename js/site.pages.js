
			function enable_simple_search(){
					//calls the search function when the search button is clicked
					var simple_search_btn = document.getElementById('simple_search_btn');
					var input = document.getElementById("simple_search_input");
					simple_search_btn.addEventListener("click", sendSearch);
					
					//function to allow the pressing of enter to map to search button
					input.addEventListener("keyup", function(event) { 
						event.preventDefault();
						if (event.keyCode === 13) { //13 is enter key
							document.getElementById("simple_search_btn").click();
						}
					});
				}
			//This function will display the home page
			function home_page(){
				navbar.innerHTML= 				'<li class="active"><a href="#" onclick="home_page()">Home</a></li>\
												<li><a href="#" onclick="scan_page()" >Scan Images</a></li>\
												<li><a href="#" onclick="browse_page()">Browse Images</a></li>\
												<li><a href="#" onclick="nerd_page()">Nerdy Stuff</a></li>   \
												<li><a href="#" onclick="advanced_search_page()">Advanced Search</a></li> ';
				upper_container.innerHTML=		'<div class="well"> \
													<h1 class="center">Welcome to Web-based Image Organiser v1.0</h1>\
													<h4  class="center"><b>This program was created by Mark IV Tech and for all support related questions please direct them <a href="mailto:support@markivtech.com.au">here</a>.</b></h4>\
													<hr>\
													<h2 class="center">Site Layout</h2>\
													<h3><a href="#" onclick="scan_page()">Scan Images</a></h3>\
													<p>Use this page to select a folder from your computer to scan for photos that can then have their metadata edited.</p>\
													<h3><a href="#" onclick="browse_page()">Browse Images</a></h3>\
													<p>Use this page to browse through the current images that are stored on the database.</p>\
													<h3><a href="#" onclick="nerd_page()">Nerdy Stuff</a></h3>\
													<p>Use this page to view database stats and other site specific information. Also you can delete the current database if you run into errors.</p>\
													<h3><a href="#" onclick="advanced_search_page()">Advanced Search</a></h3>\
													<p>Use this page to perform an advanced metadata search on the current library of images in the database.</p>\
												</div>';
												
				lower_container.innerHTML=		'';
				
				simple_search.innerHTML=		'<input id="simple_search_input" class="userInput" type="text" placeholder="Search.." name="search">\
												<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm"> Search</button>';
				enable_simple_search();
	
			}
			
			function nerd_page(){
				navbar.innerHTML= 				'<li><a href="#" onclick="home_page()">Home</a></li>\
												<li><a href="#" onclick="scan_page()" >Scan Images</a></li>\
												<li><a href="#" onclick="browse_page()">Browse Images</a></li>\
												<li class="active"><a href="#" onclick="nerd_page()">Nerdy Stuff</a></li>   \
												<li><a href="#" onclick="advanced_search_page()">Advanced Search</a></li> ';
				upper_container.innerHTML=		'<div class="well"> \
													<h2>Nerdy Stuff</h2>\
													<p>This page will allow you to delete the current database and also view database stats.</p>\
													<hr>\
													<button id ="deleteDB" type="button">Delete DB</button>\
												</div>';
												
				lower_container.innerHTML=		'<div class="jumbotron" id="photos">\
												</div>';
				
				simple_search.innerHTML=		'<input id="simple_search_input" class="userInput" type="text" placeholder="Search.." name="search">\
												<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm"> Search</button>';
				enable_simple_search();
				//assign variables to objects on page for ease of editing
				var photos= document.getElementById('photos');
				var simple_search_btn = document.getElementById('simple_search_btn');
				var deleteDB = document.getElementById('deleteDB');
				var input = document.getElementById("simple_search_input");
				
				//function to allow the pressing of enter to map to search button
				input.addEventListener("keyup", function(event) { 
					event.preventDefault();
					if (event.keyCode === 13) { //13 is enter key
						document.getElementById("simple_search_btn").click();
					}
				});		
				
				//calls the search function when the search button is clicked
				simple_search_btn.addEventListener("click", sendSearch);

				//resets the database
				deleteDB.addEventListener("click", deleteDatabase);
				
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
						output += "\n<p><label>Root Path: </label> " + root_path + " </p>";
						}
					}
					output += "\n<p><label>Number of Images in Database: </label> " + numImage + "</p>";
					output += "\n<p><label>Size of Database:</label> " + dbSize + " bytes</p>";
					photos.innerHTML= output;
				}
				get_db_stats();
	
			}
			
			//This function will display the browse page
			function browse_page(){
				navbar.innerHTML= '<li><a href="#" onclick="home_page()">Home</a></li>\
									<li><a href="#" onclick="scan_page()">Scan Images</a></li>\
									<li class="active"><a href="#"  onclick="browse_page()">Browse Images</a></li>\
									<li><a href="#" onclick="nerd_page()">Nerdy Stuff</a></li>   \
									<li><a href="#" onclick="advanced_search_page()">Advanced Search</a></li> ';
				simple_search.innerHTML=		'<input id="simple_search_input" class="userInput" type="text" placeholder="Search.." name="search">\
												<button id= "simple_search_btn"type="button" class="btn btn-default btn-sm"> Search</button>';
				upper_container.innerHTML=		'<div class="well"> \
													<h2>Browse Library</h2>\
													<p>On this page all of you photos (.jpg) that are currently in the root folder you have selected appear here.</p>\
													<div class="dropdown">\
														<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Sort Images\
															<span class="caret"></span></button>\
														<ul class="dropdown-menu">\
															<li><a href="#" onclick="allPhotos()">Newest to Oldest</a></li>\
															<li><a href="#" onclick="sortOldFirst()">Oldest to Newest</a></li>\
														</ul>\
													</div> \
												</div>';
				lower_container.innerHTML=		'<div class="jumbotron" id="photos">\
												</div>';
				enable_simple_search();
				//uses ajax to load all photos
				var photos= document.getElementById('photos');
				allPhotos();		
			}
			
			//This function will display the browse page
			function scan_page(){
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
				//uses ajax to load all photos
				var photos= document.getElementById('photos');
				loadDirectory();

				
			}
			
			
			//This function will display the advanced search page
			function advanced_search_page(){
			navbar.innerHTML= 			'<li><a href="#" onclick="home_page()">Home</a></li>\
										<li><a href="#" onclick="scan_page()" >Scan Images</a></li>\
										<li><a href="#"  onclick="browse_page()">Browse Images</a></li>\
										<li><a href="#" onclick="nerd_page()">Nerdy Stuff</a></li>   \
										<li class="active"><a href="#" onclick="advanced_search_page()">Advanced Search</a></li> ';
			simple_search.innerHTML= "";
			lower_container.innerHTML="";
			upper_container.innerHTML=	'<div class="well">\
											<h2>Advanced Search</h2>\
											<p>Use the fields below to search for photos based on metadata.</p>\
											<div class="">\
												<div id="puthere" class="left">\
												</div>\
												<div class="right">	\
												</div>\
												<div class="container searchContainer" id="advancedSearchForm">\
													<div class="jumbotron" id="photos">\
													<form id="advancedSearch" name="advsearchform" action="" onsubmit="return false" method="POST">\
													<div class="form-group">\
													<fieldset>\
													<legend class="lHeader">File Data</legend>\
														<label for="created">Date Created:</label>\
														<input type="text" class="form-control" id="created">\
														<label for="fileName">File Name:</label>\
														<input type="text" class="form-control" id="fileName">\
														<label for="photoType">Photo Type (JPG):</label>\
														<input type="text" class="form-control" id="photoType">\
														<label for="dateModified">Date Modified:</label>\
														<input type="text" class="form-control" id="dateModified">\
														<label for="fileSize">File Size:</label>\
														<input type="text" class="form-control" id="fileSize">\
													</fieldset>\
														<fieldset>\
													<legend class="lHeader">Description</legend>\
														<label for="photoTitle">Photo Title:</label>\
														<input type="text" class="form-control" id="photoTitle">\
														<label for="photoComments">Photo Comments:</label>\
														<input type="text" class="form-control" id="photoComments">\
														<label for="photoSubject">Photo Subject (JPG):</label>\
														<input type="text" class="form-control" id="photoSubject">\
														<label for="photoRating">Photo Rating:</label>\
														<input type="text" class="form-control" id="photoRating">\
														<label for="photoTags">Photo Tags:</label>\
														<input type="text" class="form-control" id="photoTags">\
													</fieldset>\
													<fieldset>\
													<legend class="lHeader">Origin Data</legend>\
														<label for="author">Author:</label>\
														<input type="text" class="form-control" id="author">\
														<label for="dateTaken">Date Taken:</label>\
														<input type="text" class="form-control" id="dateTaken">\
														<label for="copyright">Copyright:</label>\
														<input type="text" class="form-control" id="copyright">\
													</fieldset>\
													<fieldset>\
													<legend class="lHeader">Image Data</legend>\
														<label for="photoWidth">Photo Width:</label>\
														<input type="text" class="form-control" id="photoWidth">\
														<label for="photoHeight">Photo Height</label>\
														<input type="text" class="form-control" id="photoHeight">\
														<label for="photoCompression">Photo Compression</label>\
														<input type="text" class="form-control" id="photoCompression">\
													</fieldset>\
													<fieldset>\
													<legend class="lHeader">Camera Data</legend>\
														<label for="sel1">Camera Maker:</label>\
														<select name="camera_maker" class="form-control" id="cameraMaker"></select>\
														<label for="sel1">Camera Model:</label>\
														<select name="camera_model" class="form-control" id="cameraModel"></select>\
														<br>\
														<button onclick="reset_selectors()" class="btn btn-primary btn-md"> Reset Dropdowns </button>\
													</fieldset>\
													<fieldset>\
													<legend class="lHeader">Advanced Data</legend>\
														<label for="cameraSerialNumber">Camera Serial Number:</label>\
														<input type="text" class="form-control" id="cameraSerialNumber">\
													</fieldset>\
													</div>\
													<button id="advanced_search_btn" type="submit" class="btn btn-primary btn-md" onclick="sendAdvancedSearch()">Search</button>\
													<a href="#" class="btn btn-primary btn-md " onclick="home_page()">Close</a>\
												</form>\
													</div>	\
												</div>\
											</div>\
										</div>';
										
				var photos= document.getElementById('photos');
				var maker = document.getElementById('cameraMaker');	
				var model = document.getElementById('cameraModel');
					
				//sets up placeholders
				newOption(maker, "", "Select a Camera Maker");
				newOption(model, "", "Select a Camera Model");
					
				maker.addEventListener("change", getOptions);
				model.addEventListener("change", getOptions);
					
				//gets initial options
				getOptions();
			}