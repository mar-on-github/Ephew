<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . "/functions.php");
require_once(__DIR__ . "/snippets.php");
// Check if maintenance mode is enabled
if (file_exists(__DIR__ . '/maintenance')) {
    echo "<h1>This server is in maintenance mode!</h1>";
    echo file_get_contents(__DIR__ . '/maintenance');
    die;
}


// Serve pages

//      We will always need a trailing slash, or the script won't recognise requested sources
$url = $_SERVER['REQUEST_URI'];
$lastchar = substr($url, -1);
if ($lastchar != '/') :
    if (!$_SERVER['QUERY_STRING'] and !is_file($_SERVER['DOCUMENT_ROOT'] . $url) and !is_dir($_SERVER['DOCUMENT_ROOT'] . $url)) :
        header("Location: $url/");
    endif;
endif;

echo "<!-- You are here:" . $_SERVER['REQUEST_URI'] . ". -->";

//      Redirect home from doc root.
if (($_SERVER['REQUEST_URI']) === '/') {
    header("Location: /home/");
    die;
}
//      Home page
if (($_SERVER['REQUEST_URI']) === '/home/') {
    include(__DIR__ . "/home.php");
    die;
}
//      Ephew Write Engine v2
if (($_SERVER['REQUEST_URI']) === '/write2') {
    echo "The Ephew Write Engine will soon get a huge update... But wait for it!";
    die;
}
//      Ephew Write Engine v1 (legacy)
if (($_SERVER['REQUEST_URI']) === '/write.php') {
    include(__DIR__ . "/write.php");
    die;
}
//      Timeline RSS Feed
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
//      Login page
if (($_SERVER['REQUEST_URI']) === '/login/') {
    if (session_id() == '') {
        session_start();
    }
    if (isset($_SESSION["username"])) {
        header("Location: /home/");
    }
    unifiedheader(false, "Logging in");
    $SQL_comm_ADDR = GetSQLCreds('address');
    $SQL_comm_USER = GetSQLCreds('username');
    $SQL_comm_PASS = GetSQLCreds('password');
    $SQL_comm_DBNM = GetSQLCreds('database');
    $con = mysqli_connect("$SQL_comm_ADDR", "$SQL_comm_USER", "$SQL_comm_PASS", "$SQL_comm_DBNM");
    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if (session_id() == '') {
        session_start();
    }
    // When form submitted, check and create user session.
    if (isset($_POST['username'])) {
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($con, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        // Check user is exist in the database
        $query    = "SELECT * FROM `users` WHERE username='$username'
                        AND password='" . md5($password) . "'";
        $result = mysqli_query($con, $query) or die(mysqli_error($con));
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = GetUserID($username);
            ephewloggesthis("$username logged in.");
            header("Location: /home");
        } else {
            ephewloggesthis("Incorrect password used. $username - $password");
            echo "<div class='form'>
                    <h3>Incorrect Username/password.</h3><br/>
                    <p class='link'>Click here to <a href='/login/'>Login</a> again.</p>
                    </div>";
        }
    } else {
    ?>

        <div class="loginsidebar">
            <form class="ephew-form" method="post" name="login">
                <h1 class="login-title">Login</h1>
                <input type="text" class="login-input" name="username" placeholder="Username" autofocus="true" />
                <input type="password" class="login-input" name="password" placeholder="Password" />
                <input type="submit" value="Login" name="submit" class="ephew-buttons ephew-button-big" />
            </form>
        </div>
        <div class="logincontent">
            <h1>Woo!</h1>
            <h2>Welcome back!</h2>
            <p>New to Ephew? Read <a href="/about">here</a> to learn more, or <a href="/signup/">sign up</a>!</p>
        </div>

    <?php
    }
    unifiedfooter(false,false);
    die;
}
//      Register page
if (($_SERVER['REQUEST_URI']) === '/signup/' OR '/register/') {
    if (session_id() == '') {
        session_start();
    }
    if (isset($_SESSION["username"])) {
        header("Location: /home");
    }
    unifiedheader(false, "Creating an account :)");
    $SQL_comm_ADDR = GetSQLCreds('address');
    $SQL_comm_USER = GetSQLCreds('username');
    $SQL_comm_PASS = GetSQLCreds('password');
    $SQL_comm_DBNM = GetSQLCreds('database');
    $con = mysqli_connect("$SQL_comm_ADDR", "$SQL_comm_USER", "$SQL_comm_PASS", "$SQL_comm_DBNM");

    // Check connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if (session_id() == '') {
        session_start();
    }
    // When form submitted, insert values into the database.
    if (isset($_REQUEST['username'])) {
        // removes backslashes
        $username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
        $username = mysqli_real_escape_string($con, $username);
        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($con, $email);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        $create_datetime = date("Y-m-d H:i:s");
        $query    = "INSERT into `users` (username, password, email, create_datetime)
                        VALUES ('$username', '" . md5($password) . "', '$email', '$create_datetime')";
        $result   = mysqli_query($con, $query);
        if ($result) {
            echo "<div class='form'>
                    <h3>You are registered successfully.</h3><br/>
                    <p class='link'>Click here to <a href='/login/'>Login</a></p>
                    </div>";
            ephewloggesthis("New user registered. $username - $password");
        } else {
            echo "<div class='form'>
                    <h3>Required fields are missing.</h3><br/>
                    <p class='link'>Click here to <a href='signup'>registration</a> again.</p>
                    </div>";
        }
    } else {
    ?>
        <div class="loginsidebar">
            <form class="ephew-form" action="/signup/" method="post">
                <h1 class="login-title">Registration</h1>
                <input type="text" class="login-input" name="username" placeholder="Username" required />
                <input type="text" class="login-input" name="email" placeholder="Email Adress">
                <input type="password" class="login-input" name="password" placeholder="Password">
                <input type="submit" name="submit" value="Register" class="ephew-buttons ephew-button-big">
                <p class="link">Already have an account? <a href="/login/">Log in!</a></p>
            </form>
        </div>
        <div class="logincontent">
            ???? Hello there.
            <h1>Are you ready to sign up?</h1>
        </div>
    <?php
    }
    unifiedfooter(false,false);
    die;
}

//      About redirect to readme page
if (($_SERVER['REQUEST_URI']) === '/about/') {
    header("Location: /readme/");
}

//      Readme page
if (($_SERVER['REQUEST_URI']) === '/readme/') {
    $pagetitle = "About";
    $usedefaultsidebar = "true";
    unifiedheader($usedefaultsidebar, "$pagetitle");
    $abouttext = file_get_contents(__DIR__ . '/../readme.md');
    $Parsedown = new Parsedown();

    echo $Parsedown->text($abouttext);
    echo "\n<br>";
    unifiedfooter($usedefaultsidebar);
}
//      Logout
if (($_SERVER['REQUEST_URI']) === '/logout/') {
    session_start();
    if (session_destroy()) {
        header("Location: /login/");
    }
    die;
}
//      Feedback page
if (($_SERVER['REQUEST_URI']) === '/feedback/') {
    unifiedheader(true, "Feedback");
    echo "Feedback is always appreciated around here. But please <a href=\"https://github.com/mar-on-github/Ephew/issues/new/choose\">leave it as a GitHub Issue</a>.<br></br>Thank you for using Ephew!";
    unifiedfooter(true, true);
    die;
}
//      The Ephew Blog
if (($_SERVER['REQUEST_URI']) === '/blog/') {
    unifiedheader(true, "The Ephew Blog");
    ?>
    <ul class="timeline">
        <?php
        $postsbyid = file('blogpostslistedbymar.txt');
        foreach ($postsbyid as $idofpost) {
            $clean_id = preg_replace(
                "/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/",
                "",
                $idofpost
            );
            if (compilepost($clean_id, 'posttype') === 'article') {
                echo "<li class=\"postview\ article\">";
            } else {
                echo "<li class=\"postview\">";
            }
            comcompost($clean_id);
            echo "</li>\n<br>\n";
        }
        ?>
    </ul>
<?php
    unifiedfooter();
    die;
}

if (($_SERVER['REQUEST_URI']) === '/profile/picture/') {
    if (!(isset($_GET['for']))) {
        exit;
    }
    $profilephotoindexing_file = __DIR__ . "/profile/profilephotos/index/index_";
    $profilephotoindexing_file .= md5($_GET['for']);
    $profilephotoindexing_file .= ".txt";
    if (file_exists($profilephotoindexing_file)) {
        $f = fopen($profilephotoindexing_file, 'r');
        $raw_image_url = fgets($f);
        fclose($f);
        $image_url = __DIR__ . "/profile/profilephotos/$raw_image_url";
    } else {
        $image_url = __DIR__ . "/IMG/defaultuserprofile.png";
    }
    header('Content-type: image/jpeg');
    if (file_exists($image_url)) {
        readfile($image_url);
    }
    exit;
}
if (($_SERVER['REQUEST_URI']) === '/profile/') {


    if (session_id() == '') {
        session_start();
    }
    if (!isset($_SESSION["username"])) {
        header("Location: /login/");
        exit();
    }

    $user = $_GET['for'] ?? $_SESSION['username'];
    $for = $_GET['for'] ?? "''";
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
        unifiedheader($usedefaultsidebar, $pagetitle);
    ?>
        <button class="openbtn" onclick="openNav()">???</button>
        <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">??</a>
            <?php
            bottombarlink('/home/', '<img loading="lazy" src="/img/favicon.png" width="20px"> Ephew</a>');
            bottombarlink('/profile/edit/#profilepicture', 'Edit profile picture');
            bottombarlink('/profile/edit/#bio', 'Edit bio');
            bottombarlink('/feedback/', '???Feedback');
            ?>
        </div>
        <div class="content">
            <?php echo $user; ?>
        <?php
    }
    if ((($user === $_SESSION['username']))) {
        $pagetitle = "Your profile";
        $usedefaultsidebar = "false";
        unifiedheader($usedefaultsidebar, $pagetitle);
        ?>
            <button class="openbtn" onclick="openNav()">???</button>
            <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">??</a>
                <?php
                bottombarlink('/home/', '<img loading="lazy" src="/img/favicon.png" width="20px"> Ephew</a>');
                bottombarlink('/profile/edit/#profilepicture', 'Edit profile picture');
                bottombarlink('/profile/edit/#bio', 'Edit bio');
                bottombarlink('/feedback/', '???Feedback');
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
            echo "Couldn't find any posts by this user.";
            die;
        }
        echo "</ul>";

            ?>

            </div>
            <?php
            unifiedfooter();
            die;
}
if (($_SERVER['REQUEST_URI']) === '/create/') {
    include (__DIR__ . "/create.php");
    die;
}

        if (($_SERVER['REQUEST_URI']) === '/settings/') {
            echo "This site is really really new... <br> As such, this page is not available yet... Sorry.";
            die;
        }
        if (($_SERVER['REQUEST_URI']) === '/reader/') {

            unifiedheader($usedefaultsidebar, $pagetitle);
            if ((compilepost($_GET["postid"], 'posttype') == 'post')) {
                echo "<title>Ephew - Post by "
                    . compilepost($_GET["postid"], 'postauthor')
                    . ", saying '"
                    . compilepost($_GET["postid"], 'postcontent')
                    . "'</title>";
            }
            if ((compilepost($_GET["postid"], 'posttype') == 'media')) {
                echo "<title>Ephew - Media post by "
                    . compilepost($_GET["postid"], 'postauthor')
                    . "</title>";
            }
            if ((compilepost($_GET["postid"], 'posttype') == 'article')) {
                echo "<title>Ephew - Article by "
                    . compilepost($_GET["postid"], 'postauthor')
                    . ", about '"
                    . compilepost($_GET["postid"], 'post_alttext')
                    . "'</title>";
            }
            die;
?>

    <button class="openbtn" onclick="openNav()">???</button>
    <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">??</a>
        <a href="/home/">Ephew</a>
        <a href="/create/">??? New post</a>
        <a href="/about/">???Info</a>
        <a href="/feedback/">???Feedback</a>
    </div>
    <div class="content">
        <?php
            if (compilepost($_GET["postid"], 'posttype') === 'article') {

        ?>
            <div class="postview postview-article">
                <?php
                echo "<div class=\"postedbyuserheader\">An article by <img loading=\"lazy\" src=\"/profile/picture?for="
                    . compilepost($_GET["postid"], 'postauthor')
                    . "\" width=\"50px\" alt=\"P\"/ class=\"lazy\">"
                    . "<a href=\"/profile?for="
                    . compilepost($_GET["postid"], 'postauthor')
                    . "\"/>"
                    . compilepost($_GET["postid"], 'postauthor')
                    . "</a>"
                    . "&nbsp;<span class=\"post-timestamp\" id=\"post_timedate_"
                    . $_GET["postid"]
                    . "\"></span>\n"
                    . "<script>\n"
                    . "var timestamp = ("
                    . compilepost($_GET["postid"], 'post_timestamp')
                    . " * 1000)\n"
                    . "var date = new Date(timestamp);\n\n"
                    . "var postedondate = (\"posted on date: \" + date.getDate() +\n"
                    . "\"/\" + (date.getMonth() + 1) +\n"
                    . "\"/\" + date.getFullYear() +\n"
                    . "\" \" + date.getHours() +\n"
                    . "\":\" + date.getMinutes() +\n"
                    . "\":\" + date.getSeconds());\n"
                    . "document.getElementById(\"post_timedate_"
                    . $_GET["postid"]
                    . "\").innerHTML = postedondate;"
                    . "</script>\n"
                    . "</div>";
                $Parsedown = new Parsedown();
                echo "<h1>"
                    . compilepost($_GET["postid"], 'post_alttext')
                    . "</h1>";
                echo $Parsedown->text(compilepost($_GET["postid"], 'postcontent'));
                ?>

            </div>
        <?php
            } else {
        ?>
            <div class="postview">
                <?php
                comcompost($_GET["postid"]);
                ?>
            </div>
        <?php } ?>
    </div>
<?php
            unifiedfooter();
        }

        // Give users on wrong pages the Ephew-404[tm]
            unifiedheader(true, "uhhh...");
            echo "<p>Oops... Seems like that <CODE>". $_SERVER['REQUEST_URI'] . "</CODE> page could not be found...</p> <br><font style=\"size: small; color: #70561991; text-decoration:line-through;\">Keep digging Azrael, I need those smurfs!</font><br></br><p><a href=\"/home/\">Back home, then!</a></p>";
            unifiedfooter();
            die;
