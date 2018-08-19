<?php
//this script will respond to simple and complex searches
//the script will send the path to all images that satisfy the search criteria
//reply will be formatted in JSON
//search criteria to be passed to script using Get
	$json = "{";
	$simple_search= $_GET['simple'];
	$json .=  '"imageArray" : ["Test_Images/ute.jpeg"]';

	$json .=  "}";
	//add json header and send the data
 	header("Content_type: text/json");
 	print $json;


?>


