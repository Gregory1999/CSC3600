<?php
//this file is to set up the db 
	//if no database file- create the db
	if (!file_exists('site.db')){
		$db = new SQLite3('site.db');
						
		#this table will store the root directory information-  may later be able to extend code to have multiple selectable roots
		$db->exec('CREATE TABLE root_directory
				(path STRING PRIMARY Key,
				 last_scan DATETIME)'); # may change this data type
				
		//this will grow- just added the minimum to allow testing			
		$db->exec('CREATE TABLE photo
				(photo_path STRING PRIMARY Key,
				 date_created DATETIME,
				 deleted BOOLEAN)');
				 
		//table for holding photo description	
		$db->exec('CREATE TABLE photo_description
				(photo_path STRING PRIMARY Key,
				 title STRING,
				 subject STRING,
				 rating INTEGER
				 tags STRING
				 comments STRING)');


	}
	//if file exists point to database
	else {
		$db = new SQLite3('site.db');
	}				

?>