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

$whichCourse = $_GET['whichCourse'];
$courseid = $_GET['courseid'];
$tagid = $_GET['tagid'];
$dir = $_GET['dir'];
$action = $_GET['action'];

if ($action == "course_tag") {		
	if ($dir == "up") {
		$upd_query = "UPDATE course_tag SET course_tag_count=course_tag_count+1 WHERE course_tag_course_id=".$courseid." AND course_tag_tag_id=".$tagid.";";
		$upd = mysql_query($upd_query);
	} elseif ($dir == "dn") {
		$upd_query = "UPDATE course_tag SET course_tag_count=course_tag_count-1 WHERE course_tag_course_id=".$courseid." AND course_tag_tag_id=".$tagid.";";
		$upd = mysql_query($upd_query);			
	}

	$my_url = "../tag.php?tagupdate=true";
} else {
	$my_url = "../tag.php?tagerror=true";
}
if ($whichCourse <> "") {
    $my_url .= "&whichCourse=" . $whichCourse;
}
header("Location: " . $my_url);
?>