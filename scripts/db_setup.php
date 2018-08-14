<?php
# this file is to set up the initial db when the program is initially run
	echo "db_setup file";	
	
	
	$db = new SQLite3('site.db');
	
	#this table will store the root directory information-  may later be able to extend code to have multiple selectable roots
	$db->exec('CREATE TABLE root_directory
				(path STRING PRIMARY Key,
				 last_scan INTEGER)'); # may change this data type
				
				
				

?>