<?php

/* This script will perform the autocomplete for the main search box. When a user begins typing a search query, this script will be called to check the emerging query term against the database, and will return a JSON object containing any matching course names or tag names */

require ("auth.db.init.php"); // database connection settings
require ("library.php"); // php function library

// initialize database connection variables
global $authDBHost;
global $authDBName;
global $authDBUser;
global $authDBPasswd;

// if the 'term' variable is not sent with the request, exit
if (!isset($_REQUEST['term'])) {
	exit;
}

// connect to the database server and select the appropriate database for use
$dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
mysql_select_db($authDBName);
 
// query the database for course names that match 'term'
$rs = mysql_query('SELECT course_name FROM course WHERE course_name LIKE "%'. mysql_real_escape_string($_REQUEST['term']) .'%" limit 0,5');

// query the database for tag names that match 'term'
$rs2 = mysql_query('SELECT tag_name FROM tag WHERE tag_name LIKE "%' . mysql_real_escape_string($_REQUEST['term']) .'%" limit 0,5');

// loop through each value returned and format the response for jQuery
$data = array();
if ( $rs && mysql_num_rows($rs) )
{
	while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
	{
		$data[] = array(
			'label' => $row['course_name'] ,
			'value' => $row['course_name']
		);
	}
}
if ( $rs2 && mysql_num_rows($rs2) )
{
	while( $row = mysql_fetch_array($rs2, MYSQL_ASSOC) )
	{
		$data[] = array(
			'label' => $row['tag_name'] ,
			'value' => $row['tag_name']
		);
	}
}

// jQuery wants JSON data
//debug_to_console($data);
echo json_encode($data);
flush();
?>