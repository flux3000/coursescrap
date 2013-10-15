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
            SEARCH
        </div>

        <div id="search">
            <form action="index.html">
                <input id="search_box" type="text" placeholder="Enter search term">
            </form>
        </div>

        <div id="content">
			<div class="usedTags"></div>
			<div class="TagCloud"></div>
			<div class="courseList"></div>
		</div>
    </div>

</body>
</html>