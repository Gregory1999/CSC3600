<?php
//this file is to set up the db 
	//if no database file- create the db
	if (!file_exists('site.db')){
		$db = new SQLite3('site.db');
						
		//this table will store the root directory information-  may later be able to extend code to have multiple selectable roots
		$db->exec('CREATE TABLE root_directory
				(path STRING PRIMARY Key,
				 last_scan DATETIME)'); 
				
		//this table holds all the file data			
		$db->exec('CREATE TABLE photo_file
				(photo_path STRING PRIMARY Key,
				 date_created DATETIME,
				 photo_name STRING,
				 photo_type STRING,
				 date_modified DATETIME,
				 size INTEGER,
				 deleted BOOLEAN)');
				 
		//table for holding photo description	
		$db->exec('CREATE TABLE photo_description
				(photo_path STRING PRIMARY Key,
				 title STRING,
				 comments STRING,
				 subject STRING,
				 rating STRING,
				 tags STRING)'); 
		
		//table for holding photo thumbnail 	
		$db->exec('CREATE TABLE photo_thumbnail
				(photo_path STRING PRIMARY Key,
				 photo_thumbnail BLOB)'); 
				 
		//table for holding photo origin description	
		$db->exec('CREATE TABLE photo_origin
				(photo_path STRING PRIMARY Key,
				 authors STRING,
				 date_taken DATETIME,
				 copyright STRING)'); 
				 
		//table for holding photo image description	
		$db->exec('CREATE TABLE photo_image
				(photo_path STRING PRIMARY Key,
				 width INTEGER,
				 height INTEGER,
				 compression  STRING)'); 
				 
		//table for holding photo camera  description	
		$db->exec('CREATE TABLE photo_camera 
				(photo_path STRING PRIMARY Key,
				 camera_maker STRING,
				 camera_model STRING)');
				 
		//table for holding photo photo_advanced  description	
		$db->exec('CREATE TABLE photo_advanced 
				(photo_path STRING PRIMARY Key,
				 camera_serial_number STRING)');

		
	}
	//if file exists point to database
	else {
		$db = new SQLite3('site.db');
	}				

?>