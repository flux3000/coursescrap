<?php
	/* This script will insert new course information entered by the user, into the database. In addition, we populate the intersection thereby establshing the course-tag relationship (by using the tags entered by the user when adding the course), and the instructor-course relationship (by adding the instructor entered by the user when adding the course).*/
	
	require ("auth.db.init.php"); // database connection settings
	require ("library.php"); // php function library

	// initialize database connection variables
	global $authDBHost;
	global $authDBName;
	global $authDBUser;
	global $authDBPasswd;

	// connect to the database server and select the appropriate database for use
	$dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
	mysql_select_db($authDBName);

	$DEBUG = 0; // change to 1 if we want to see a bunch of debugging comments.

	//Store the paramters sent via the POST request.
	if ($_POST['add_course'] == "true") {
		$new_course_name = $_POST['new_course_name'];
		$new_course_ccn = $_POST['new_course_ccn'];
		$new_course_instructor_id = $_POST['new_course_instructor_id'];
		$new_course_resource_id = $_POST['new_course_resource_id'];
		$new_course_units = $_POST['new_course_units'];
		$new_course_time = $_POST['new_course_time'];
		$new_course_tag = $_POST['new_course_tag'];
		$new_course_location = $_POST['new_course_location'];
		$new_course_description = mysql_real_escape_string($_POST['new_course_description']);

		//Insert the course information
		$insertQuery_str = "INSERT INTO course (course_name, course_ccn, course_resource_id, course_units, course_time, course_location, course_description) VALUES ('$new_course_name', '$new_course_ccn', '$new_course_resource_id', '$new_course_units', '$new_course_time', '$new_course_location', '$new_course_description');";
		$insertresult = mysql_db_query($authDBName, $insertQuery_str);
		$new_course_id = mysql_insert_id();

		//Insert the instructor/course relationship
		$insertQuery_str2 = "INSERT INTO course_instructor (course_instructor_course_id, course_instructor_instructor_id) VALUES ($new_course_id, $new_course_instructor_id);";
		$insertresult2 = mysql_db_query($authDBName, $insertQuery_str2);
		
		// now apply all tags that were chosen
		if (!empty($new_course_tag)) {
			foreach ($new_course_tag as $insert_tag_id) {
				$insert_q = "INSERT INTO course_tag (course_tag_course_id, course_tag_tag_id) VALUES ($new_course_id, $insert_tag_id);";
				$insert_result = mysql_db_query($authDBName, $insert_q);
				if ($insert_result) {
					$insert_successful = "true";
				}
			}
		} else {
			echo 'empty';
		}

		if ($insertresult) {
			$my_url = "../add.html?insert_successful=true";

		} else {
			$my_url = "../add.html?insert_error=" . $insert_error;
		}
	}

	header("Location: " . $my_url);
?>