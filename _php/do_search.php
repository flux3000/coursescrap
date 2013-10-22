<?php
// initialize vars
require("auth.db.init.php"); // db initilization variables
require("library.php"); // php function library
global $authDBHost;
global $authDBName;
global $authDBUser;
global $authDBPasswd;

$DEBUG = 0; // set to 1 to be shown debug output

$search_array['search'] = array();
$search_array['related'] = array();
$search_array['results'] = array();


// connect to the database
$dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
mysql_select_db($authDBName);

$search_query = $_POST["query"];

/*Ashley*/

$search_array['search']['name'] = $search_query;
$search_terms = explode(',', $search_query);
$or_search_term = "";
foreach($search_terms as $term){
	$or_search_term .= "'".$term."',";
}
$or_search_term = substr($or_search_term, 0, -1);
/*------*/

// assemble the main database query. This will return course and professor details.

$q = "SELECT DISTINCT course.*, instructor.*, tag.*, course_tag.course_tag_count FROM tag, course, course_tag, instructor, course_instructor WHERE tag.tag_name IN (".$or_search_term.") AND course_tag.course_tag_tag_id=tag.tag_id AND course.course_id=course_tag.course_tag_course_id AND course.course_id=course_instructor.course_instructor_course_id AND instructor.instructor_id=course_instructor.course_instructor_instructor_id GROUP BY course.course_name HAVING COUNT(DISTINCT course_tag.course_tag_id) = ".count($search_terms)." ORDER BY course_tag.course_tag_count DESC;";

if ($DEBUG)	{echo "q: " . $q . "<br><br>";}

$q_result = mysql_db_query($authDBName, $q);
while($q_row = mysql_fetch_array($q_result)) {
	// Go through each course in the results. Construct the primary results string, "string_results_content". Then, get the full list of tags for each one and read each into the string_results_content variable with votes or "score". Also, create a master tag array ('related_tag_array') that contains all tags in all results, and the number of times the tag appears in the course list, or "count".

	$this_course_id = $q_row["course_id"];
	$this_course_name = $q_row["course_name"];
	$this_course_ccn = $q_row["course_ccn"];
	$this_course_resource_id = $q_row["course_resource_id"];
	$this_course_description = htmlspecialchars(stripslashes($q_row["course_description"]));
	$this_course_mgmt_req = $q_row["course_mgmt_req"];
	$this_course_tech_req = $q_row["course_tech_req"];
	$this_instructor_firstname = stripslashes($q_row["instructor_firstname"]);
	$this_instructor_lastname = stripslashes($q_row["instructor_lastname"]);
	$this_instructor_email = $q_row["instructor_email"];

	$search_result_array = array(); // initialize sub-array to contain details on the "results" of this course info.
	$search_result_tag_array = array(); // initialize sub-array to contain tags for this course.
	
	$search_result_array["name"] = $this_course_name; 
	$search_result_array["instructor"] = $this_instructor_firstname . ' ' . $this_instructor_lastname; 
	$search_result_array["description"] = $this_course_description; 
	$search_result_array["ccn"] = $this_course_ccn;
	$search_result_array["resource_id"] = $this_course_resource_id; 
	$search_result_array["mgmt_req"] = $this_course_mgmt_req; 
	$search_result_array["tech_req"] = $this_course_tech_req; 

	// This query will get all the tags for each course in these results. This will allow us to populate the the $search_array['related'] subarray, and also will give us the "tags" array for the "string_results" subarray.
	$q2 = "SELECT tag.*, course_tag.course_tag_count FROM tag, course_tag WHERE course_tag.course_tag_course_id=$this_course_id AND tag.tag_id=course_tag.course_tag_tag_id ORDER BY course_tag.course_tag_count DESC;";

	$q2_result = mysql_db_query($authDBName, $q2);
	while($q2_row = mysql_fetch_array($q2_result)) { // go through each tag in this particular course result.
		$this_tag_id = $q2_row["tag_id"];
		$this_tag_name = $q2_row["tag_name"];
		$this_course_tag_count = $q2_row["course_tag_count"];

		$search_result_this_tag_array = array(); // initialize sub-array to contain tag name and count for this tag.

		$search_result_this_tag_array["name"] = $this_tag_name;
		$search_result_this_tag_array["count"] = $this_course_tag_count;
        
		array_push($search_result_tag_array, $search_result_this_tag_array);

		/*if ($DEBUG) {
	        echo '<pre><br>course name: ' . $this_course_name . '<br>';
	        print_r($search_result_this_tag_array);
	        print_r($search_result_tag_array);
			echo "</pre>";
		}*/	

        $exists = 0;
        foreach ($search_array['related'] as $key => $value) {
            if ($key == $this_tag_name){ // this tag already exists in our master related tag array. Increment the count.
                $exists = 1;
            }
        }	
		if ($exists) {
			$search_array['related'][$this_tag_name] += 1;
		} else {
			$search_array['related'][$this_tag_name] = 1;
		}		
	}


	$search_result_array["tags"] = $search_result_tag_array;
	
	array_push($search_array['results'], $search_result_array);

	if($q2_result === FALSE) {
    	die(mysql_error()); 
	}
}

if($q_result === FALSE) {
    die(mysql_error()); 
}

arsort($search_array['related']);

if ($DEBUG) {
	echo "<pre>";
	echo "Search Term Array:<br>---<br>";
 	print_r ($search_array['search']);
 	echo '<br><br>';

	echo "Result Details Array:<br>---<br>";
 	print_r ($search_array['results']);
 	echo '<br><br>';

	echo "Related Tags Array:<br>---<br>";
 	print_r ($search_array['related']);
 	echo '<br>';
 	echo '<br><br>-----------<br><br>';
 	echo "</pre>";
 	echo '<br><br><br>';
}

// encode the data as json and send it back to the page.

echo json_encode($search_array);

/*
	The structure is
	search:
		[name]
	related:
		[name,count]
	results:
		[name,instructor,description,tags[name,score]]
	//A useful tool for parsing json can be found here: http://json.parser.online.fr/

SAMPLE OBJECT:

{
"search":[{"name":"info"},{"name":"design"}],

"related":[{"name":"java", "count":4},{"name":"objective C", "count":2}, {"name":"computer", "count":1}, {"name":"law", "count":1}, {"name":"science","count":1}, {"name":"prototyping", "count":1}],

"results":[
	{
	"name":"Information Law and Policy", 
	"instructor":"Dierdre Mulligan", 
	"description": "Description Text", 
	"tags":[{"name":"info", "score":3}, {"name":"design", "score":1}]
	},

	{
	"name":"Intellectual Property Law for the Information Industries", 
	"instructor":"Brian Carver", 
	"description": "Three hours of lecture per week. This course will provide an overview of the intellectual property laws with which information managers need to be familiar. It will start with a consideration of trade secrecy law that information technology and other firms routinely use to protect commercially valuable information. It will then consider the role that copyright law plays in the legal protection of information products and services. Although patents for many years rarely were available to protect information innovations, patents on such innovations are becoming increasingly common. As a consequence, it is necessary to consider standards of patentability and the scope of protection that patent affords to innovators. Trademark law allows firms to protect words or symbols used to identify their goods or services and to distinguish them from the goods and services of other producers. It offers significant protection to producers of information products and services. Because so many firms license intellectual property rights, some coverage of licensing issues is also important. Much of the course will concern the legal protection of computer software and databases, but it will also explore some intellectual property issues arising in cyberspace.", 
	"tags":[{"name":"info", "score":3}, {"name":"design", "score":1, "law":1}]
	}
]

}
*/
?>
