<?php
  include("auth_session.php");
 ?>
<?php
include __DIR__ . ("/../src/Ephew-internals/unifiedheader.php");
function writetotimeline($IDToWrite){
    $filename = "./timelinebyid.txt";
    $fileContent = file_get_contents($filename);
    file_put_contents($filename, $IDToWrite . "\n" . $fileContent);
}

?>

 <title> Ephew - Processing post...</title>
 <!-- content starts -->
    <div class="content">
        <?php
        $SQL_comm_USER = GetSQLCreds('username');
        $SQL_comm_PASS = GetSQLCreds('password');
        $conn = mysqli_connect("localhost","$SQL_comm_USER", "$SQL_comm_PASS", "ephew");

        // Check connection
        if($conn === false){
            echo "Error! Couldn't post that!<br></br>Internal error.";
            die;
        }

        $phewcontent =  encodeValue($_POST['phewcontent']);
        $phewtype = $_POST['phewtype'];
        $phewposter =  $_SESSION['userid'];
        $phewalttext = encodeValue($_POST['alttext']);
        $post_priv = $_POST['privacy'];
        $postid1 = rand(10000,99999999);
        $postid = md5($postid1);
        date_default_timezone_set('Europe/Amsterdam');
        $current_date = date('Y-m-d H:i:s');
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $current_date);
        $posttimestamp = $date->getTimestamp();
        $c70error = false;
        if (!isset($phewcontent)) {
            echo "<br><h1>Error! Couldn't post that!<br></br>Not enough data. (C70-1)</h1>";
            $c70error = true;
        }
        if (!isset($phewtype)) {
            echo "<br><h1>Error! Couldn't post that!<br></br>Not enough data. (C70-2)</h1>";
            $c70error = true;
        }
        if (!isset($phewposter)) {
            echo "<br><h1>Error! Couldn't post that!<br></br>Not enough data. (C70-3)</h1>";
            $c70error = true;
        }
        if (!isset($phewalttext)) {
            echo "<br><h1>Error! Couldn't post that!<br></br>Not enough data. (C70-4)</h1>";
            $c70error = true;
        }
        if (!isset($posttimestamp)) {
            echo "<br><h1>Error! Couldn't post that!<br></br>Not enough data. (C70-5)</h1>";
            $c70error = true;
        }
        if($c70error === true) {
            echo "<br><br><a href=\"/\">go back home</a>";
        }
        if (!isset($post_priv)) {
            $post_priv = 'public';
        }
        $sql = "INSERT into `ephew`.`posts` (postid, postcontent, posttype, postauthor, post_timestamp, post_alt_text, post_privacy)
        VALUES ('$postid', '$phewcontent', '$phewtype', '$phewposter', '$posttimestamp', '$phewalttext', '$post_priv')";
        if(mysqli_query($conn, $sql)){
            writetotimeline($postid);
            echo "<h3>Post processed!</h3>";
            echo nl2br("<p>You should now be taken to /$postid/</p>
            <meta http-equiv=\"Refresh\" content=\"5; url='/reader?postid=$postid'\" />");
            header("Location: /reader?postid=$postid");
        } else{
            echo "Error! Couldn't post that!<br></br>"
                . mysqli_error($conn);
            die;
        }

        // Close connection
        mysqli_close($conn);
        ?>
    </div>
<?php
unifiedfooter();
 ?>
