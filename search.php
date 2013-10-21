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
		<a href="index.php"><img class="back" src="_img/back.png" alt="back"></a>
        
		<div id="title">
            SEARCH
        </div>
		

        <div id="search">
            <form action="" method="post">
                <input id="search_query" type="text" placeholder="Enter search term">
				<input id="searchBtn" value="Go" type="submit" />
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