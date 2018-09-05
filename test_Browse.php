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
		
		
		<div class="picker"><input type="file" id="picker" name="fileList" webkitdirectory multiple>
		<ul id="listing"></ul>
			<?php
	
			?>
		
		</div>
		</div>
	</div>
	</body>
</html>
<script type="text/javascript" >
let picker = document.getElementById('picker');
let listing = document.getElementById('listing');

picker.addEventListener('change', e => {
  for (let file of Array.from(e.target.files)) {
    let item = document.createElement('li');
    item.textContent = file.webkitRelativePath;
    listing.appendChild(item);
  };
});
</script>
