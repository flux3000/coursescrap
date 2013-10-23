<?php
	require("auth.db.init.php"); // db initilization variables
	require("library.php"); // php function library
	global $authDBHost;
	global $authDBName;
	global $authDBUser;
	global $authDBPasswd;

	// connect to the database server and select the appropriate database for use
	$dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
	mysql_select_db($authDBName);
	
	$DEBUG = 0; // change to 1 if we want to see a bunch of debugging comments.
	
	//If the 'tagName' and 'courseId' parameters are set then search the 'tag' table. If the tag does or does not exist in the database, return an appropriate message to the user.
	//If the tag does exist, query the 'course_tag' table with the 'courseId' sent in the POST request and the 'tagId' returned from the query on the 'tag' table.
	//If the tag hs been assoicated with the course, then send an appropriate message to the user, else insert the tag in the 'course_tag' table and associate it with the course.
	if(isset($_POST["tagName"],$_POST["courseId"])){
		$tagName = $_POST["tagName"];
		$courseId = $_POST["courseId"];
		$tag_search_query = "SELECT `tag_id` FROM tag WHERE `tag_name` = '".mysql_real_escape_string($tagName)."'";
		$tag_search_query_result = mysql_query($tag_search_query);
		
		$tagId = mysql_fetch_array($tag_search_query_result);
		if(mysql_num_rows($tag_search_query_result) == 0){
			echo "Sorry! That tag does not Exist. Please select one from the available list.";
		}else{
			$tag_course_search_query = "SELECT * FROM `course_tag` WHERE `course_tag_course_id` = '".mysql_real_escape_string($courseId)."' AND `course_tag_tag_id` = '".$tagId["tag_id"]."'";
			
			if($tag_course_search_query_result = mysql_query($tag_course_search_query)) {
				$query_run_num_rows = mysql_num_rows($tag_course_search_query_result);
				if($query_run_num_rows == 1) {
					//echo "Tag already exists.";
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
	
	//If the 'query' parameter is set then search the 'tag' table. If the tag does or does not exist in the database, return an appropriate message to the user.
	if(isset($_POST["query"])){
		$query = $_POST["query"];
		$tag_search_query = "SELECT `tag_id` FROM tag WHERE `tag_name` = '".mysql_real_escape_string($query)."'";
		$tag_search_query_result = mysql_query($tag_search_query);
		
		if(mysql_num_rows($tag_search_query_result) == 0){
			echo "Sorry! That tag does not Exist. Please select one from the available list.";
		}else{
			echo "OK to proceed";
		}
	}
?>