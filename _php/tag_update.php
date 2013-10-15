<?php
require ("auth.db.init.php"); // database connection settings
require ("library.php"); // php function library

global $authDBHost;
global $authDBName;
global $authDBUser;
global $authDBPasswd;

// connect to the database server and select the appropriate database for use
$dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
mysql_select_db($authDBName);

$courseid = $_GET['courseid'];
$tagid = $_GET['tagid'];
$dir = $_GET['dir'];
$action = $_GET['action'];

$DEBUG = 0; // change to 1 if we want to see a bunch of debugging comments.

if ($action == "course_tag") {		
	/*
	if ($DEBUG) {echo "Value of text box is " . $tagtext . '<br>';}
	
	if ($tagtext <> "") { // user entered the name of a tag into the text box.		
		// convert to lowercase and trim. check against existing tags. if it exists, increment. if not, add new tag.
		$tagtext = trim(strtolower($tagtext));
		$chkq = "SELECT * FROM tag WHERE tag_name='$tagtext';";		
		if ($DEBUG) {echo "chkq is " . $chkq . '<br>';}
		$tagresult = mysql_query($chkq);			
		if (mysql_num_rows($tagresult) > 0) { // tag exists. check for relationship in course_tag - increment/add if necessary.
			while ($tagrow = mysql_fetch_array($tagresult)) {
				$tagid = $tagrow["tag_id"];					
				if ($DEBUG) {echo "FOUND. tagid is " . $tagid . '<br>';}
				$relquery = "SELECT * FROM course_tag WHERE course_tag_course_id='$course_id' AND course_tag_tag_id='$tagid';";
				$relresult = mysql_query($relquery);
				if (mysql_num_rows($relresult) > 0) { // relationship already exists. increment by one.							
					$upd_query = "UPDATE course_tag SET course_tag_count=course_tag_count+1 WHERE course_tag_course_id='$courseid' AND course_tag_tag_id='$tagid';";
					$upd = mysql_query($upd_query);
				} else { // relationship doesn't exist yet. add new relationship
					$ins_query = "INSERT INTO course_tag (course_tag_course_id, course_tag_tag_id) VALUES ('$courseid', '$tagid');";
					$ins = mysql_query($ins_query);				
				}
			}
		}					
		// this function should be activated if we want users to be able to add new tags to the database.
		} else { // add new tag and relationship.
			if ($DEBUG) {echo "NOT FOUND. adding tag to database<br>";}
			$ins_query = "INSERT INTO tag (tag_name) VALUES ('$tagtext');";
			if ($DEBUG) {echo "ins_query is" . $ins_query . '<br>';}
			$ins = mysql_query($ins_query);
			$tagid = mysql_insert_id();
			$ins_query2 = "INSERT INTO course_tag (course_tag_course_id, course_tag_tag_id) VALUES ('$courseid', '$tagid');";
			if ($DEBUG) {echo "ins_query2 is" . $ins_query2 . '<br>';}
			$ins2 = mysql_query($ins_query2);				
		}	
	} else { // user clicked on an increment/decrement link on an existing tag relationship.
	*/
	if ($dir == "up") {
		$upd_query = "UPDATE course_tag SET course_tag_count=course_tag_count+1 WHERE course_tag_course_id=".$courseid." AND course_tag_tag_id=".$tagid.";";
		$upd = mysql_query($upd_query);
	} elseif ($dir == "dn") {
		$upd_query = "UPDATE course_tag SET course_tag_count=course_tag_count-1 WHERE course_tag_course_id=".$courseid." AND course_tag_tag_id=".$tagid.";";
		$upd = mysql_query($upd_query);			
	}
	//}

	$my_url = "../add.php?tagupdate=true";

} else {

	$my_url = "../add.php?tagerror=true";
	
}

header("Location: " . $my_url);
?>