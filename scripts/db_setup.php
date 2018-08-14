<?php
# this file is to set up the initial db when the program is initially run
	echo "db_setup file";	
	
	
	$db = new SQLite3('site.db');
	
	#this table will store the root directory information-  may later be able to extend code to have multiple selectable roots
	$db->exec('CREATE TABLE root_directory
				(path STRING PRIMARY Key,
				 last_scan DATETIME)'); # may change this data type
				
	//this will grow- just added the minimum to allow testing			
	$db->exec('CREATE TABLE photo
				(photo_path STRING PRIMARY Key,
				 date_created DATETIME,
				 date_modified DATETIME)');
				 

				

?>