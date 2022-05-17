<?php
$httproot = '../';
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
    echo "<img src=\"/profile/picture.php?for="
        . $postauthor
        . "\"/>"
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
        echo "<div class=\"link-embed\">";
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
//require_once __DIR__ . ('/../../hiddenphp/sqlpassword.php');
function GetSQLCreds(string $output = 'username' | 'password' | 'address' | 'database'): string
{
    require_once realpath(__DIR__ . '/../../vendor/autoload.php');

    // Looing for .env at the root directory
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
            <img src="<?php echo $metaData->image; ?>" class="card-img-top" alt="..."></a>
        <div class="link-card">
            <h4 class="card-title"><?php echo $metaData->title; ?></h4>
            <p class="card-text"><?php echo $metaData->description; ?></p><br>
            <a href="<?php echo $metaData->url; ?>" class="ephew-buttons ephew-button-small" target="_blank">Visit site</a><br></br>
        </div>
<?php }
}
