<?php
if (session_id() == '') {
  session_start();
}
    if(isset($_SESSION["username"])) {
      header("Location: /home");
    }
?>
<?php
include("../internals/unifiedheader.php");
?>

<title>Ephew - Creating an account :)</title>
<?php
    require_once('..//internals//functions.php');
    $SQL_comm_USER = GetSQLCreds('username');
    $SQL_comm_PASS = GetSQLCreds('password');
    $con = mysqli_connect("localhost", "$SQL_comm_USER", "$SQL_comm_PASS", "LoginSystem");
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
  <form class="ephew-form" action="" method="post">
      <h1 class="login-title">Registration</h1>
      <input type="text" class="login-input" name="username" placeholder="Username" required />
      <input type="text" class="login-input" name="email" placeholder="Email Adress">
      <input type="password" class="login-input" name="password" placeholder="Password">
      <input type="submit" name="submit" value="Register" class="ephew-buttons">
      <p class="link">Already have an account? <a href="/login/">Log in!</a></p>
  </form>
</div>
<div class="logincontent">
  👀 Hello there.
  <h1>Are you ready to sign up?</h1>





</div>
<?php
    }
?>
<?php
include("../internals/unifiedfooter.php");
?>
