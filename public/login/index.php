<?php
if (session_id() == '') {
    session_start();
}
    if(isset($_SESSION["username"])) {
      header("Location: /home");
    }
?>
<?php
$pagetitle = "Logging in";
$usedefaultsidebar = "false";
include __DIR__ . ("/../../src/Ephew-internals/unifiedheader.php");
?>
<!-- content starts -->
<?php
require_once ('../../src/Ephew-internals/functions.php');
$SQL_comm_USER = GetSQLCreds('username');
$SQL_comm_PASS = GetSQLCreds('password');
$con = mysqli_connect("localhost", "$SQL_comm_USER", "$SQL_comm_PASS", "ephew");
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
if (session_id() == '') {
    session_start();
}
?>
<?php
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
      <input type="text" class="login-input" name="username" placeholder="Username" autofocus="true"/>
      <input type="password" class="login-input" name="password" placeholder="Password"/>
      <input type="submit" value="Login" name="submit" class="ephew-buttons ephew-button-big"/>
</form>
</div>
<div class="logincontent">
  <h1>Woo!</h1>
  <h2>Welcome back!</h2>
  <p>New to Ephew? Read <a href="/about">here</a> to learn more, or <a href="/signup/">sign up</a>!</p>
</div>

<?php
    }
?>
<?php
include __DIR__ . ("/../../src/Ephew-internals/unifiedfooter.php");
?>
