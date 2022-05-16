<?php
include __DIR__ . ("/../auth_session.php");
$user = $_GET['for'] ?? $_SESSION['username'];
$for = $_GET['for'] ?? "''";
require_once ('../../src/Ephew-internals/functions.php');
$SQL_comm_USER = GetSQLCreds('username');
$SQL_comm_PASS = GetSQLCreds('password');
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
<?php
if ((($user === $for)) and (!($user === $_SESSION['username']))) {
    $pagetitle = $user . "'s profile";
    $usedefaultsidebar = "false";
    include __DIR__ . ("/../../src/Ephew-internals/unifiedheader.php");
?>
    <button class="openbtn" onclick="openNav()">☰</button>
    <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <?php
        bottombarlink('/home/', '<img src="/img/favicon.png" width="20px"> Ephew</a>');
        bottombarlink('/profile/edit/#profilepicture', 'Edit profile picture');
        bottombarlink('/profile/edit/#bio', 'Edit bio');
        bottombarlink('/feedback/', '❕Feedback');
        ?>
    </div>
    <div class="content">
        <?php echo $user; ?>
    <?php
}
if ((($user === $_SESSION['username'])) and (!($user === $for))) {
    $pagetitle = "Your profile";
    $usedefaultsidebar = "false";
    include __DIR__ . ("/../../src/Ephew-internals/unifiedheader.php");
    ?>
        <button class="openbtn" onclick="openNav()">☰</button>
        <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <?php
            bottombarlink('/home/', '<img src="/img/favicon.png" width="20px"> Ephew</a>');
            bottombarlink('/profile/edit/#profilepicture', 'Edit profile picture');
            bottombarlink('/profile/edit/#bio', 'Edit bio');
            bottombarlink('/feedback/', '❕Feedback');
            ?>
        </div>
        <div class="content">
            <p>This should be your page! A work in progress.</p>
        <?php
    }

        $conn = mysqli_connect("localhost", "$SQL_comm_USER", "$SQL_comm_PASS", "ephew");
        $sql = "SELECT * FROM `ephew`.`posts` WHERE postauthor='$user'";
        $sqlq = $conn->query($sql);
        // if ($sqlq === false) {
        //     die("ERROR: Could not connect to database. "
        //     . mysqli_connect_error());
        // }

echo "<ul class=\"timeline\">";
        if (mysqli_query($conn, $sql)) {
    while ($output = $sqlq->fetch_array()) {
        $postid = $output['postid'];
        if (compilepost($postid, 'posttype') === 'article') {
            echo "<li class=\"postview article\">";
        } else {
            echo "<li class=\"postview\">";
        }
        comcompost($postid);
        echo "</li>\n<br>\n";
    }
} else {
    echo "Couldn't find any posts..<br></br>"
    . mysqli_error($conn);
    die;
}
echo "</ul>";

        ?>

        </div>
        <?php
        include __DIR__ . ("/../../src/Ephew-internals/unifiedfooter.php");
        ?>