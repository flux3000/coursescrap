<!DOCTYPE html>
<html lang="en">
<head>
    <title>course (s)crap - search</title>

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
                SEARCH
            </div>
            <div id="sitetitle">
                course (s)crap
            </div>    
            <div id="description">
                Use this page to find I School classes based on topics or tags that interest you. Refine your search by clicking on related tags.<br><br>
                <span style="font-size:10pt;color:gray;">Example searches: "Data Mining", "Law and Policy"</span><br>
            </div>
        </div>
        <div id="search">
            <br><br><br><br>
            <form action="" method="post">
                <input id="search_query" type="text" placeholder="Enter a topic or tag">
                <input id="searchBtn" value="Go" type="submit" />
                <div id="searchTagFeedback"></div>
            </form>
        </div>
        <div id="content">
            <div class="results-related">
                <h3>Searched Tags</h3>
                <ul id="searched-tags">
                </ul>
                <h3>Related Tags</h3>
                <ul id="related-tags">
                </ul>               
            </div>
            <div class="results-main">
                <ul id="course-results">
                </ul>
            </div>
		</div>	
    </div>
</body>
</html>