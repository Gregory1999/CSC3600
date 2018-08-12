<?php
# this file is to set up the initial db when the program is initially run
	$db = new SQLite3('site.db');
	
	#this table will store the directory information
	$db->exec('CREATE TABLE Directory
				(path STRING PRIMARY Key,
				 last_scan INTEGER)'); # may change this data type
				
				
				

?>