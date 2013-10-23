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

        $selected_course_id = $_GET["whichCourse"];
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
                TAG
            </div>
            <div id="sitetitle">
                course (s)crap
            </div>    
            <div id="description">
                Use this page to find classes you've taken, and describe them by tag. Either vote up or down an existing tag, or enter one using the text box.
            </div>            
            <div id="navbar">

                <div id="navbar-select">
                    <form action="tag.php" method="GET">
                    <label for="whichCourse">Choose a course:</label><select id="whichCourse" name="whichCourse">
                    <option value="">All Courses</option>
                    <?php
                        $course_result = mysql_query("SELECT * FROM course ORDER BY course_resource_id", $dblink);
                        while ($course_row = mysql_fetch_array($course_result)) {
                            echo "<option value=" . $course_row["course_id"];
                            if ($course_row["course_id"] == $selected_course_id) {
                                echo ' selected';
                            }
                            echo ">" . $course_row["course_resource_id"] . " - " . $course_row["course_name"] . "</option>";
                        }                    
                    ?>
                    </select>
                    <input type="submit" value="Find Course">
                    </form>
                </div> <!--navbar-select-->
                <div id="navbar-add">
                    <a id="addCourse" href="add_course.html">[add new course]</a>
                </div>           
            </div> <!--navbar-->
        </div> <!--titlebar-->

        <div id="content">

			<div id="course-listings">
            <?php
                
                if ($selected_course_id <> "") {
                    $this_query = "SELECT * FROM course WHERE course_id=$selected_course_id ORDER BY course.course_resource_id";
                } else {
                    $this_query = "SELECT * FROM course ORDER BY course.course_resource_id";
                }
				$course = mysql_query($this_query, $dblink);
				while ($course_row = mysql_fetch_array($course)) {
					$course_instructor_id = mysql_fetch_array(mysql_query("SELECT `course_instructor_instructor_id` FROM `course_instructor` WHERE `course_instructor_course_id`='".$course_row["course_id"]."'", $dblink));
					
                    echo "<div class='course-listing'>";
                        echo "<div class='course-listing-header'>";
                            echo '<div class="course-title"><a class="course" href="http://www.ischool.berkeley.edu/courses/'.$course_row["course_resource_id"].'" target="_new">'.$course_row["course_resource_id"].' - '.$course_row["course_name"].'</a></div>';
                        echo '</div>'; // end course-listing-header
                        echo '<div class="course-listing-content">';
        					echo "<div class='left-col'>";
        						$instructor = mysql_fetch_array(mysql_query("SELECT * FROM `instructor` WHERE `instructor_id`='".$course_instructor_id["course_instructor_instructor_id"]."'", $dblink));						
        						echo "Instructor: ".$instructor["instructor_firstname"]." ".$instructor["instructor_lastname"]."<br><br>".$course_row["course_description"]."<br><br>";					
        					echo "</div>"; // end left-col
        					
        					echo "<div class='tag-box'>";
                                echo '<div class="header">Tags</div>';
            						$course_tag_result = mysql_query("SELECT `course_tag_tag_id`, `course_tag_count` FROM `course_tag` WHERE `course_tag_course_id`='".$course_row["course_id"]."' ORDER BY `course_tag_count` DESC", $dblink);
            						
                                    while ($course_tag_row = mysql_fetch_array($course_tag_result)) {
            							$testresult = mysql_query("SELECT * FROM tag WHERE `tag_id`='".$course_tag_row["course_tag_tag_id"]."'", $dblink);
            							while ($testrow = mysql_fetch_array($testresult)) {
                                            $thistagcount = 0;
                                            if ($course_tag_row["course_tag_count"] > 0) {

                                                echo "<div class='tagrow";
                                                if ($thistagcount == 0) {
                                                    echo " first";
                                                }
                                                echo "'>";
                                                echo "<div class='tagrow_name'>".$testrow["tag_name"]." (".$course_tag_row["course_tag_count"].")</div>"; // end tagrow_name
                                                echo "<div class='tagrow_inc'>";
                                                echo "<a class='updn' href='_php/tag_update.php?action=course_tag";
                                                if ($selected_course_id <> "") {
                                                    echo "&whichCourse=" . $selected_course_id;
                                                }
                                                echo "&courseid=".$course_row["course_id"]."&tagid=".$testrow["tag_id"]."&dir=up'>[+]</a>&nbsp;";
                                                echo "<a class='updn' href='_php/tag_update.php?action=course_tag";
                                                if ($selected_course_id <> "") {
                                                    echo "&whichCourse=" . $selected_course_id;
                                                }
                                                echo "&courseid=".$course_row["course_id"]."&tagid=".$testrow["tag_id"]."&dir=dn'>[&ndash;]</a>";
                                                echo "</div>"; // end tagrow_inc
                                                echo '</div>'; // end tagrow
                                            }
            							}
            						}
            						
            					?>
        						
        						<div class="tagrow_add">
        							<form class="add-tag-form" action="" method="post">
        								<input class="courseId" type="hidden" value=<?php echo $course_row["course_id"];?> />
        								<input class="addTagText" type="text" placeholder="Enter a Tag" />
                                        <button type="button" class="addTagBtn">Apply Tag</button>
        								<div id="resultsFeedback"></div>
        							</form>
        						</div> <!--end tagrow_add-->
        						<div id="menu-container" style="position:absolute; width: 410px;"></div>
        					   <?php
        					   echo "</div>"; // end tag-box
                            echo "</div>"; // end course-listing-content
                        echo "</div>"; // end course-listing
                        }
                        ?>
            </div> <!--end course-listings-->
        </div> <!--end content-->
    </div> <!--end container-->
</body>
</html>