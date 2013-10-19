<!DOCTYPE html>
<html>
<head>
    <title>IO Lab - Project 2</title>

    <!-- PHP DB Initialization -->

    <?php
        require("_php/auth.db.init.php"); // db initilization variables
        require("_php/library.php"); // php function library
        global $authDBHost;
        global $authDBName;
        global $authDBUser;
        global $authDBPasswd;

        $dblink = mysql_connect($authDBHost, $authDBUser, $authDBPasswd) or die( mysql_error() );
        mysql_select_db($authDBName);
    ?>
    
    <!-- link to css files -->
    <link rel="stylesheet" type="text/css" href="_css/reset.css" />
    <link rel="stylesheet" type="text/css" href="_css/style.css" />
    <link href='http://fonts.googleapis.com/css?family=Raleway|Open+Sans' rel='stylesheet' type='text/css'>

    <!-- link to javascript files -->
    <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="_js/script.js"></script>

</head>
<body>
    
    <div id="container">

        <div id="title">
            I School Course Tracker
        </div>

        <div id="search">
            <form action="index.html">
                <input id="search_box" type="text" placeholder="Enter search term">
            </form>
        </div>

        <div id="content">
        <?php
		if(isset($_GET['insert_successful'])){
			if ($_GET['insert_successful'] == "true") { 
				echo '<span class="success">Course successfully added.</span><br/>';
			} elseif ($_GET['insert_error'] != "") { 
				echo '<span class="error">Error adding course: ' . $_GET['insert_error'] . '</span><br/>';
			}
			}
        ?>
        <a id="addCourse" href="add_course.html">[add new course]</a><br><br>
            <?php
				$course = mysql_query("SELECT * FROM course", $dblink);
				while ($course_row = mysql_fetch_array($course)) {
					$course_instructor_id = mysql_fetch_array(mysql_query("SELECT `course_instructor_instructor_id` FROM `course_instructor` WHERE `course_instructor_course_id`='".$course_row["course_id"]."'", $dblink));
					echo "<div class='course-listing'>";

						echo "<div class='left-col'>";
							$instructor = mysql_fetch_array(mysql_query("SELECT * FROM `instructor` WHERE `instructor_id`='".$course_instructor_id["course_instructor_instructor_id"]."'", $dblink));						
							echo '<div class="header">'.$course_row["course_name"]."</div><br>Instructor: ".$instructor["instructor_firstname"]." ".$instructor["instructor_lastname"]."<br><br>".$course_row["course_time"]."<br><br>".$course_row["course_description"]."<br><br>";					
						echo "</div>";
						
						echo "<div class='right-col'>";
                        echo '<div class="header">Tags</div>';
							$course_tag_result = mysql_query("SELECT `course_tag_tag_id`, `course_tag_count` FROM `course_tag` WHERE `course_tag_course_id`='".$course_row["course_id"]."'", $dblink);
							while ($course_tag_row = mysql_fetch_array($course_tag_result)) {
								$testresult = mysql_query("SELECT * FROM tag WHERE `tag_id`='".$course_tag_row["course_tag_tag_id"]."'", $dblink);
								while ($testrow = mysql_fetch_array($testresult)) {
									echo $testrow["tag_name"]." (".$course_tag_row["course_tag_count"].") <a class='updn' href='_php/tag_update.php?action=course_tag&courseid=".$course_row["course_id"]."&tagid=".$testrow["tag_id"]."&dir=up'>&#43</a>   <a class='updn' href='_php/tag_update.php?action=course_tag&courseid=".$course_row["course_id"]."&tagid=".$testrow["tag_id"]."&dir=dn'>&#45</a>"."<br><br>";
								}
							}
						echo "</div>";
					echo "</div>";
				}
            ?>    
        </div>
    </div>

</body>
</html>