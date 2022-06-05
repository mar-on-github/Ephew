<?php
require_once (__DIR__ . '/../vendor/autoload.php');
// Check if maintenance mode is enabled
if (file_exists(__DIR__ . '/maintenance')) {
    echo "<h1>This server is in maintenance mode!</h1>";
    echo file_get_contents(__DIR__ . '/maintenance');
    die;
}

// Functions that used to be separate files
function filetimeline() {
    echo "<ul class=\"timeline\">";
    $timelinedottxt=__DIR__ . "/timelinebyid.txt";
    $postsbyid = file($timelinedottxt);
    foreach ($postsbyid as $idofpost) {
        $clean_id = preg_replace(
            "/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/",
            "",
            $idofpost
        );
        if (compilepost($clean_id, 'posttype') === 'article') {
            echo "<li class=\"postview article\">";
        } else {
            echo "<li class=\"postview\">";
        }
        comcompost($clean_id);
        echo "</li>\n<br>\n";
    }
    echo "</ul>";
}

function unifiedheader() {
    ?>
    <!--
    Starting this project today.
    - Mar Bloeiman
    2022-4-26
    -->
    <?php
    $themetype = LocateStyleSheet();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <link rel="manifest" href="/ephew.webmanifest">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#04AA6D" />
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="/img/favicon-512x512.png" />
        <link rel="stylesheet" href="/styles/ephew-base.css" content-type="text/css" charset="utf-8" />
        <?php
        echo "<link rel=\"stylesheet\" href=\"/styles/colors/";
        if (isset($_SESSION["themetype"])) {
            $themetype = ($_SESSION["themetype"]);
        } else {
            $themetype = 'light';
        }
        echo $themetype
            . ".css\" content-type=\"text/css\" charset=\"utf-8\"/>";
        echo "<!-- current theme loaded is:"
            . $themetype
            . "-->";
        if (isset($pagetitle)) {
            echo "<title>Ephew - "
                . $pagetitle
                . "</title>";
        }
        ?>
    </head>

    <body>
        <button id="install_button" hidden class="ephew-buttons ephew-button-big">Install Ephew as a web app!</button>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script type="module" src="/scripts/sw.js"></script>
        <script src="/scripts/nicm.js"></script>
        <?php
        if (isset($usedefaultsidebar)) {
            if ($usedefaultsidebar === 'true') {
        ?>
                <button class="openbtn" onclick="openNav()">☰</button>
                <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                    <?php
                    bottombarlink('/home/', '<img loading="lazy" src="/img/favicon.png" width="20px"> Ephew</a>');
                    bottombarlink('/create/', '➕ New post');
                    bottombarlink('/about/', '❔Info');
                    bottombarlink('/feeback/', '❕Feedback');
                    ?>
                </div>
                <div class="content">
            <?php }
        }
}

function unifiedfooter() {
    if (isset($usedefaultsidebar) and $usedefaultsidebar === 'true') {
    if (!isset($autoendcontentdiv) or !($autoendcontentdiv === 'false')) {
        echo "</div>";
    }
    }
    ?>
    <div class="bottombar" id="mybottombar">
    <a href="javascript:void(0);" class="icon" onclick="unrollbottombar()">&#9776;</a>
    <?php
    bottombarlink("/home/", "Home");
    if (!isset($_SESSION["username"])) {
        bottombarlink("/login/", "Log in");
        bottombarlink("/signup/", "Sign up");
    } else {
        bottombarlink("/logout/", "Log out");
    }
    //bottombarlink("/feedback/", "Feedback");
    bottombarlink("/blog/", "Blog");
    include(__DIR__ . '/../src/Ephew-internals/styleswitcher.php')
    ?>
    </div>

    </body>

    <script src="/scripts/responsivemenus.js"></script>

    </html>
    <?php

}

// Ephew functions
function comcompost($postid)
{
    composepost($postid, compilepost($postid, 'posttype'), compilepost($postid, 'postcontent'), compilepost($postid, 'post_timestamp'), compilepost($postid, 'postauthor'), compilepost($postid, 'post_alttext'));
}
function compilepost($postid, $typeoutput)
{
    $SQL_comm_USER = GetSQLCreds('username');
    $SQL_comm_PASS = GetSQLCreds('password');
    $conn = new mysqli("localhost", "$SQL_comm_USER", "$SQL_comm_PASS", "ephew");

    // Check connection
    if ($conn === false) {
        die("ERROR: Could not connect to database. "
            . mysqli_connect_error());
    }
    $sql = "SELECT * FROM `ephew`.`posts` WHERE postid='$postid'";
    $sqlq = $conn->query($sql);
    // if ($sqlq === false) {
    //     die("ERROR: Could not connect to database. "
    //     . mysqli_connect_error());
    // }
    while ($output = $sqlq->fetch_array()) {
        $posttype = $output['posttype'];
        $postcontent = $output['postcontent'];
        $postauthor = ReturnUsernameOrOnFailID($output['postauthor']);
        $post_timestamp = $output['post_timestamp'];
        $post_alttext = $output['post_alt_text'];
        $post_privacy = $output['post_privacy'];
        return $$typeoutput;
    }
}
function composepost($postid, $posttype, $postcontent, $post_timestamp, $postauthor, $post_alttext){
    echo "<div>\n<div class=\"postedbyuserheader\">";
    if ((TestIfUsernameExists($postauthor)) == true) {
    echo "<img loading=\"lazy\" src=\"/profile/picture.php?for="
        . $postauthor
        . "\" class=\"lazy\"/>"
        . "by&nbsp;<a href=\"/profile?for="
        . $postauthor
        . "\"/>"
        . $postauthor
        . "</a>,";
    } else {
        echo "By a deleted user";
    }
        echo "&nbsp;<span class=\"post-timestamp\" id=\"post_timedate_"
        . $postid
        . "\"></span>\n"
        . "</div>"
        . "<script>\n"
        . "var timestamp = ("
        . $post_timestamp
        . " * 1000)\n"
        . "var date = new Date(timestamp);\n\n"
        . "var postedondate = (\"posted on date: \" + date.getDate() +\n"
        . "\"/\" + (date.getMonth() + 1) +\n"
        . "\"/\" + date.getFullYear() +\n"
        . "\" \" + date.getHours() +\n"
        . "\":\" + date.getMinutes() +\n"
        . "\":\" + date.getSeconds());\n"
        . "document.getElementById(\"post_timedate_"
        . $postid
        . "\").innerHTML = postedondate;"
        . "</script>\n";
    if ($posttype == 'post') {

        echo "<p>"
            . $postcontent
            . "</p>";
    }
    if ($posttype == 'article') {
        echo "<div class=\"link-embed\"><h2>"
            . $post_alttext
            . "</h2><h4>Article</h4><a href=\"/reader/?postid="
            . $postid
            . "\" class=\"ephew-buttons ephew-button-small\">Read article...</a></div>";
    }
    if ($posttype == 'link') {
        echo "<p>" . $post_alttext . "</p>"
        . "<div class=\"link-embed\">";
        CreateEmbedForURL($postcontent);
        echo "</div>";
    }
    if ($posttype == 'media') {

        echo "wip";
    }
    echo "</div>";
}
function ephewloggesthis($logme)
{
    $dataToLog = array(
        date("Y-m-d H:i:s"),
        $_SERVER['REMOTE_ADDR'],
        $logme
    );

    //Turn array into a delimited string using
    //the implode function
    $data = implode(" - ", $dataToLog);

    //Add a newline onto the end.
    $data .= PHP_EOL;

    //The name of your log file.
    //Modify this and add a full path if you want to log it in 
    //a specific directory.
    $pathToFile = __DIR__ . '/../../var/log/ephew.log';

    //Log the data to your file using file_put_contents.
    file_put_contents($pathToFile, $data, FILE_APPEND);
}
function bottombarlink($gotohref, $linktitle)
{
    if ($_SERVER['REQUEST_URI'] === $gotohref) {
        echo "<a href=\"" . $gotohref . "\" class=\"active\">" . $linktitle . "</a>\n";
    } else {
        echo "<a href=\"" . $gotohref . "\" >" . $linktitle . "</a>\n";
    }
}
function encodeValue(string $s)
{
    return htmlentities($s, ENT_COMPAT | ENT_QUOTES, 'ISO-8859-1', true);
}
function LocateStyleSheet()
{
    if (session_id() == '') {
        session_start();
        if (isset($_COOKIE["themetype"])) {
            $_SESSION["themetype"] = $_COOKIE["themetype"];
            $themetype = $_COOKIE["themetype"];
        }
    }
    if (isset($_POST["settheme"])) {
        if (!($_POST["settheme"] === $themetype)) {
            $_SESSION["themetype"] = stripslashes($_POST['settheme']);
            echo "<!-- Settheme request found! Contains:"
                . $_POST["settheme"]
                . "-->";
            $settheme = stripslashes($_POST['settheme']);
            $_SESSION["themetype"] = $settheme;
            setcookie("themetype", ($_SESSION["themetype"]), 99999999, "/");
            header("Location: #$settheme");
        }
    }
    if (!isset($themetype)) {
        if (isset($_SESSION["themetype"])) {
            $themetype = $_SESSION["themetype"];
        } else {
            $themetype = 'light';
        }
    }
    return $themetype;
}
function GetSQLCreds(string $output = 'username' | 'password' | 'address' | 'database'): string {
    // Looking for .env at the root directory
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../", '.env.local');
    $dotenv->load();
    // Retrive env variable
    $username = $_ENV['DB_USER'];
    $database = $_ENV['DB_NAME'];
    $password = $_ENV['DB_PASS'];
    $address = $_ENV['DB_ADDR'];
    return $$output;
}

function GetUserID($username)
{
    $SQL_comm_USER = GetSQLCreds('username');
    $SQL_comm_PASS = GetSQLCreds('password');
    // Check connection
    if (mysqli_connect_errno()) {
        ephewloggesthis("function GetUserID: Failed to connect to MySQL: " . mysqli_connect_error());
    }
    $conn = mysqli_connect("localhost", "$SQL_comm_USER", "$SQL_comm_PASS", "ephew");
    $sql = "SELECT `id` FROM `ephew`.`users` WHERE username='$username'";
    $sqlq = $conn->query($sql);
    $userid = NULL;
    while ($output = $sqlq->fetch_array()) {
        $userid = $output['id'];
    }
    return $userid;
}
function GetUserNameFromID($userid)
{
    $SQL_comm_USER = GetSQLCreds('username');
    $SQL_comm_PASS = GetSQLCreds('password');
    if (mysqli_connect_errno()) {
        ephewloggesthis("function GetUserNameFromID: Failed to connect to MySQL: " . mysqli_connect_error());
    }
    $conn = mysqli_connect("localhost", "$SQL_comm_USER", "$SQL_comm_PASS", "ephew");
    $sql = "SELECT `username` FROM `ephew`.`users` WHERE id='$userid'";
    $sqlq = $conn->query($sql);
    $username = NULL;
    while ($output = $sqlq->fetch_array()) {
        $username = $output['username'];
    }
    return $username;
}
function TestIfUsernameExists($username)
{
    if (!(GetUserID($username) === null)) {
        return true;
    } else {
        return false;
    }
}
function ReturnUsernameOrOnFailID($userid)
{
    $RUOFID = GetUserNameFromID($userid);
    if ($RUOFID == NULL) {
        $RUOFID = $userid;
    }
    return $RUOFID;
}
function CreateEmbedForURL($url)
{
    require_once(__DIR__ . "/../Assets/Metadatalib.php");
    try {
        // Initialize URL meta class 
        $urlMeta = new UrlMeta($url);

        // Get meta info from URL 
        $metaDataJson = $urlMeta->getWebsiteData();

        // Decode JSON data in array 
        $metaData = json_decode($metaDataJson);
    } catch (Exception $e) {
        $statusMsg = $e->getMessage();
    }
?>
    <?php if (!empty($metaData)) { ?>
        <a href="<?php echo $metaData->url; ?>" class="btn btn-primary" target="_blank">
            <img loading="lazy" src="<?php echo $metaData->image; ?>" class="card-img-top" alt="..."></a>
        <div class="link-card">
            <h4 class="card-title"><?php echo $metaData->title; ?></h4>
            <p class="card-text"><?php echo $metaData->description; ?></p><br>
            <a href="<?php echo $metaData->url; ?>" class="ephew-buttons ephew-button-small" target="_blank">Visit site</a><br></br>
        </div>
<?php }
}



// Serve pages

if (($_SERVER['REQUEST_URI']) === '/') {
    header("Location: /home");
    die;
}
if (($_SERVER['REQUEST_URI']) === '/home') {
    include ('home.php');
    die;
}
if (($_SERVER['REQUEST_URI']) === '/write2') {
    echo "The Ephew Write Engine will soon get a huge update... But wait for it!";
    die;
}
if (($_SERVER['REQUEST_URI']) === '/write.php') {
    include (__DIR__ . "/write.php");
    die;
}
if (($_SERVER['REQUEST_URI']) === '/timeline.rss') {
    header('Content-type: application/xml');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
    <rss version=\"2.0\"
	xmlns:content=\"http://purl.org/rss/1.0/modules/content/\"
	xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\"
	xmlns:dc=\"http://purl.org/dc/elements/1.1/\"
	xmlns:atom=\"http://www.w3.org/2005/Atom\"
	xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\"
	xmlns:slash=\"http://purl.org/rss/1.0/modules/slash/\"
	
	xmlns:georss=\"http://www.georss.org/georss\"
	xmlns:geo=\"http://www.w3.org/2003/01/geo/wgs84_pos#\">
    <channel>\n";
    ?>
    <title>Ephew</title>
    <atom:link href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/timeline.rss" rel="self" type="application/rss+xml" />
    <link>https://<?php echo $_SERVER['HTTP_HOST']; ?></link>
    <description>a new aproach to social media.</description>
    <?php
    date_default_timezone_set('Europe/Amsterdam');
    $current_date = date('Y-m-d H:i:s')
    ?>
    <lastBuildDate><?php echo $current_date; ?> +0000</lastBuildDate>
    <language>en-US</language>
    <sy:updatePeriod>
        hourly </sy:updatePeriod>
    <sy:updateFrequency>
        1 </sy:updateFrequency>

    </channel>
    </rss>
<?php
}
if (($_SERVER['REQUEST_URI']) === '/login') {
    
    die;
}