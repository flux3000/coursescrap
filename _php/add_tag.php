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
					echo "Tag already exists.";
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