<?php
$httproot = '../';
function comcompost($postid){
    composepost($postid, compilepost($postid, 'posttype'), compilepost($postid, 'postcontent'), compilepost($postid, 'post_timestamp'), compilepost($postid, 'postauthor'), compilepost($postid, 'post_alttext'));
}
function compilepost($postid,$typeoutput){
    $SQL_comm_USER = GetSQLCreds('username');
    $SQL_comm_PASS = GetSQLCreds('password');
    $conn = new mysqli("localhost","$SQL_comm_USER", "$SQL_comm_PASS", "ephew");

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
    while ($output = $sqlq -> fetch_array()) {
        $posttype = $output['posttype'];
        $postcontent = $output['postcontent'];
        $postauthor = $output['postauthor'];
        $post_timestamp = $output['post_timestamp'];
        $post_alttext = $output['post_alt_text'];
        $post_privacy = $output['post_privacy'];
        return $$typeoutput;
    }
}
function composepost($postid,$posttype,$postcontent,$post_timestamp,$postauthor,$post_alttext){
    echo "<div>";
    echo "<div class=\"postedbyuserheader\"><img src=\"/profile/picture.php?for="
    .$postauthor
    ."\"/>"
    ."<a href=\"/profile?for="
    .$postauthor
    ."\"/>"
    .$postauthor
    ."</a>"
    ."</div>";
    echo "<span class=\"post-timestamp\" id=\"post_timedate_"
    . $postid
    . "\"></span>"
    ."<script>"
    ."var timestamp ="
    .$post_timestamp
    ."\n"
    . "var date = new Date(timestamp);\n\n"
    . "var postedondate = (\"Date: \" + date.getDate() +\n"
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

        echo "<p>"
            . $post_alttext
            . "</p><a href=\"/reader/?postid="
            . $postid
            . "\">Read more...</a>";
    }
    if ($posttype == 'media') {

        echo "wip";

    }
    echo "</div>";
}
function ephewloggesthis($logme){
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
    $pathToFile = '..//..//ephew.log';

    //Log the data to your file using file_put_contents.
    file_put_contents($pathToFile, $data, FILE_APPEND);
}
function bottombarlink($gotohref, $linktitle){
    if ($_SERVER['REQUEST_URI'] === $gotohref) {
        echo "<a href=\"" . $gotohref . "\" class=\"active\">" . $linktitle . "</a>\n";
    } else {
        echo "<a href=\"" . $gotohref . "\" >" . $linktitle . "</a>\n";
    }
}
function encodeValue(string $s){
    return htmlentities($s, ENT_COMPAT | ENT_QUOTES, 'ISO-8859-1', true);
}
function LocateStyleSheet(){
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
    if (!isset($themetype)) {if (isset($_SESSION["themetype"])) {
        $themetype = $_SESSION["themetype"];
    } else {
        $themetype = 'light';
    }
    }
    return $themetype;
}
//require_once __DIR__ . ('/../../hiddenphp/sqlpassword.php');
function GetSQLCreds(string $output = 'username' | 'password' | 'address' | 'database'): string {
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