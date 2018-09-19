<?php
//retrieves the path of the folder to display
//returns paths to all directories to that folder

	//if no path supplied use the document root.
	if ($_GET['directory'] == ""){
		//retrieve root from document root.
		//$directory=$_SERVER["DOCUMENT_ROOT"];
		//improve this later
		
		//$directory = "C:\\";
		//$parentDir = "C:\\";		
	}
	else{
		$directory = $_GET['directory'];
		$parentDir = dirname($directory);
		
		chdir("$directory"); 
	
		//find all directories in this folder.
		$directoryArray = glob( '*', GLOB_ONLYDIR|GLOB_NOSORT);

		$json = '{ "parentDirectory" : '. json_encode($parentDir) .', "currentDirectory" : '. json_encode($directory) .', "directoryArray" : [';

		//output each subdirectory
		foreach($directoryArray as $subDir){
			$json .= json_encode(realpath($subDir)) . ', ';

		}
		$json = rtrim($json,", ") . "]}";

		//add json header and send the data
		header("Content_type: text/json");
		print $json;

	}
	

?>