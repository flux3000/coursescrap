<?php
	/* This script performs 2 tasks based on the parameters sent via the POST request:
		1. If the tag to be associated with a course is already assoicated with that course then the tag count will be updated for that course, else the tag will be inserted into the appropriate intersection table in order to assoicate it to that coourse.
		2. If the tag that is searched for does not exist in the database, an appropriate message will be sent to the user.*/
	
	require("auth.db.init.php"); // db initilization variables
	require("library.php"); // php function library
	
	// initialize database connection variables
	global $authDBHost;
	global $authDBName;
	global $authDBUser;
	global $authDBPasswd;

	// connect to the database server and select the appropriate database for use
	$dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
	mysql_select_db($authDBName);
	
	$DEBUG = 0; // change to 1 if we want to see a bunch of debugging comments.

	//Ashley: If the 'tagName' and 'courseId' parameters are set then search the 'tag' table. 
	if(isset($_POST["tagName"],$_POST["courseId"])){
		$tagName = $_POST["tagName"];
		$courseId = $_POST["courseId"];
		//Ashley: Get the 'tag id' based on the 'tag name' sent to the php file.
		$tag_search_query = "SELECT `tag_id` FROM tag WHERE `tag_name` = '".mysql_real_escape_string($tagName)."'";
		$tag_search_query_result = mysql_query($tag_search_query);
		
		$tagId = mysql_fetch_array($tag_search_query_result);
		//Ashley: Check if the tag exists is the database. If the tag does not exist in the database, return an appropriate message to the user.
		if(mysql_num_rows($tag_search_query_result) == 0){
			echo "That tag does not exist. Please select one from the available list.";
		}else{
			//Ashley: If the tag does exist, query the 'course_tag' table with the 'courseId' sent in the POST request and the 'tagId' returned from the query on the 'tag' table.
			$tag_course_search_query = "SELECT * FROM `course_tag` WHERE `course_tag_course_id` = '".mysql_real_escape_string($courseId)."' AND `course_tag_tag_id` = '".$tagId["tag_id"]."'";
			
			if($tag_course_search_query_result = mysql_query($tag_course_search_query)) {
				$query_run_num_rows = mysql_num_rows($tag_course_search_query_result);
				//Ashley: If the tag has been assoicated with the course, update the tag count and send an appropriate message to the user, else insert the tag in the 'course_tag' table to associate it with the course.
				if($query_run_num_rows == 1) {
					$upd_query = "UPDATE course_tag SET course_tag_count=course_tag_count+1 WHERE course_tag_course_id=".$courseId." AND course_tag_tag_id=".$tagId["tag_id"].";";
					if ($upd = mysql_query($upd_query)) {
						echo "Tag updated successfully.";
					}
				} else {
					$insert_query = "INSERT INTO `course_tag` VALUES('','".mysql_real_escape_string($courseId)."','".$tagId["tag_id"]."','1')";
					 if($insert_query_run=mysql_query($insert_query)) {
						 echo "Tag inserted successfully.";
					 } else {
						 echo "Tag could not be inserted at this time. Please try again later.";
					 }
				}
			}
		}		
	}
	
	//Ashley: If the 'query' parameter is set then search the 'tag' table. If the tag does or does not exist in the database, return an appropriate message to the user.
	if(isset($_POST["query"])){
		$query = $_POST["query"];
		//Ashley: Get the 'tag id' based on the 'tag name' sent to the php file.
		$tag_search_query = "SELECT `tag_id` FROM tag WHERE `tag_name` = '".mysql_real_escape_string($query)."'";
		$tag_search_query_result = mysql_query($tag_search_query);
		
		if(mysql_num_rows($tag_search_query_result) == 0){
			echo "That tag does not exist. Please select one from the available list.";
		}else{
			echo "OK to proceed";
		}
	}
?>