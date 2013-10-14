<?php
require ("auth.db.init.php");     // Database connection libraries and settings
global $authDBHost;
global $authDBName;
global $authDBUser;
global $authDBPasswd;

// connect to the database server and select the appropriate database for use
$dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
mysql_select_db($authDBName);

$DEBUG = 0; // change to 1 if we want to see a bunch of debugging comments.

if ($_POST['add_course'] == "true") {

	$new_course_name = $_POST['new_course_name'];
	$new_course_ccn = $_POST['new_course_ccn'];
	$new_course_instructor_id = $_POST['new_course_instructor_id'];
	$new_course_resource_id = $_POST['new_course_resource_id'];
	$new_course_units = $_POST['new_course_units'];
	$new_course_time = $_POST['new_course_time'];
	$new_course_location = $_POST['new_course_location'];
	$new_course_description = addslashes($_POST['new_course_description']);

	// Validation against existing database
    /*
    $course_exists_query = "SELECT * FROM course WHERE course_name='$new_course_name' OR course_resource_id='$new_course_resource_id';";
    $course_exists_result = mysql_db_query($authDBName, $course_exists_query);   
    if ($course_exists_result) {   /// Already have this course in the database (based on name or resource id)
            $insert_error = "course_exists";
    }
    */
    // add any other validation steps here.

    if ($insert_error == "") { // there have been no errors

        $insertQuery_str = "INSERT INTO course (course_name, course_ccn, course_resource_id, course_units, course_time, course_location, course_description) VALUES ('$new_course_name', '$new_course_ccn', '$new_course_resource_id', '$new_course_units', '$new_course_time', '$new_course_location', '$new_course_description');";

        $insertresult = mysql_db_query($authDBName, $insertQuery_str);
        $new_course_id = mysql_insert_id();

        // insert the instructor/course relationship

        $insertQuery_str2 = "INSERT INTO course_instructor (course_instructor_course_id, course_instructor_instructor_id) VALUES ($new_course_id, $new_course_instructor_id);";

        $insertresult2 = mysql_db_query($authDBName, $insertQuery_str2);
        
        //echo 'insert query is ' . $insertQuery_str . '<br>';
        //echo 'insert query 2 is ' . $insertQuery_str2 . '<br>';

    }

    if ($insertresult) {
        $my_url = "add_course.html?insert_successful=true";

    } else {
        $my_url = "add_course.html?insert_error=" . $insert_error;
    }

}

header("Location: " . $my_url);

?>