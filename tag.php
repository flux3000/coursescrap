<!DOCTYPE html>
<html lang="en">
<head>
    <title>course (s)crap - add course</title>

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
        <img class="back" src="_img/back.png" alt="back">
        <div id="titlebar">
            <div id="title">
                course (s)crap
            </div>
            <div id="navbar">
                <div id="navbar-add">
                    <a id="addCourse" href="add_course.html">[add new course]</a>
                </div>
                <div id="navbar-select">
                    <form action="tag.php" method="GET">
                    <label for="select-course">Choose a course:</label><select id="select-course">
                    <?php
                        $course_result = mysql_query("SELECT * FROM course ORDER BY course_name", $dblink);
                        while ($course_row = mysql_fetch_array($course_result)) {
                            echo "<option value=" . $course_row["course_id"] . ">" . $course_row["course_resource_id"] . " - " . $course_row["course_name"] . "</option>";
                        }                    
                    ?>
                    </select>
                    <input type="submit" name="submit-course" value="Go">
                    </form>
                </div>                
            </div>
        </div>

        <div id="content">

			<div id="course-listings">
            <!--Ashley-->
            <?php

            $selected_course_id = $_GET["select-course"];
            if ($selected_course_id <> "") {
                $this_query = "SELECT * FROM course WHERE course_id=$selected_course_id";
            } else {
                $this_query = "SELECT * FROM course";
            }

				$course = mysql_query("SELECT * FROM course", $dblink);
				while ($course_row = mysql_fetch_array($course)) {
					$course_instructor_id = mysql_fetch_array(mysql_query("SELECT `course_instructor_instructor_id` FROM `course_instructor` WHERE `course_instructor_course_id`='".$course_row["course_id"]."'", $dblink));
					
                    echo "<div class='course-listing'>";


                    echo "<div class='course-listing-header'>";

                        echo '<div class="course-title">'.$course_row["course_name"].'</div>';
                    echo '</div>';

                    echo '<div class="course-listing-content"">';

    					echo "<div class='left-col'>";
    						$instructor = mysql_fetch_array(mysql_query("SELECT * FROM `instructor` WHERE `instructor_id`='".$course_instructor_id["course_instructor_instructor_id"]."'", $dblink));						
    						echo "<br>Instructor: ".$instructor["instructor_firstname"]." ".$instructor["instructor_lastname"]."<br><br>".$course_row["course_description"]."<br><br>";					
    					echo "</div>";
    					
    					echo "<div class='right-col'>";
                            echo '<div class="header">Tags</div>';
        						$course_tag_result = mysql_query("SELECT `course_tag_tag_id`, `course_tag_count` FROM `course_tag` WHERE `course_tag_course_id`='".$course_row["course_id"]."' ORDER BY `course_tag_count` DESC", $dblink);
        						while ($course_tag_row = mysql_fetch_array($course_tag_result)) {
        							$testresult = mysql_query("SELECT * FROM tag WHERE `tag_id`='".$course_tag_row["course_tag_tag_id"]."'", $dblink);
        							while ($testrow = mysql_fetch_array($testresult)) {
                                        if ($course_tag_row["course_tag_count"] > 0) {
        								    echo $testrow["tag_name"]." (".$course_tag_row["course_tag_count"].") <a class='updn' href='_php/tag_update.php?action=course_tag&courseid=".$course_row["course_id"]."&tagid=".$testrow["tag_id"]."&dir=up'>&#43</a>   <a class='updn' href='_php/tag_update.php?action=course_tag&courseid=".$course_row["course_id"]."&tagid=".$testrow["tag_id"]."&dir=dn'>&#45</a>"."<br><br>";
                                        }
        							}
        						}
        						
        					?>
    						
    						<div id="addTag">
    							<form class="add-tag-form" action="" method="post">
    								<input class="courseId" type="hidden" value=<?php echo $course_row["course_id"];?> />
    								<input class="addTagText" type="text" placeholder="Select a Tag" />
    								<input class="addTagBtn" value="Add Tag" type="submit" />
    								<div id="resultsFeedback"></div>
    							</form>
    						</div>
    						<div id="menu-container" style="position:absolute; width: 500px;"></div>
    					<?php
    					echo "</div>"; // end right-col
                        echo "</div>"; // end course-listing-content
                    echo "</div>";
                }
            ?>
            </div>
		<!------>
        </div>
    </div>

</body>
</html>