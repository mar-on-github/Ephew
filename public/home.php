<?
unifiedheader();
?>
<?php
if (session_id() == '') {
  session_start();
}
if (!isset($_SESSION["username"])) {
?>
  <title>Ephew - Home</title>
  <button class="openbtn" onclick="openNav()">☰</button>
  <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    <a class="active" href="#">Welcome to Ephew</a>
    <a href="/login/">Login</a>
    <a href="/signup/">Register</a>
    <a href="/about/">❔Info</a>
    <a href="/feedback/">❕Feedback</a>
  </div>
  <div class="content">
    <h1>Oh hi there! Good <span id="wishes">day</span>.</h1>
    <p>I don't recognize you, are you new here?</p>
    <?php filetimeline(); ?>
  </div>
<?php
} else {
?>
  <title>Ephew - Home for <?php echo $_SESSION['username']; ?>!</title>

  <body>


    <button class="openbtn" onclick="openNav()">☰</button>
    <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
      <a class="active" href="/home/"><img loading="lazy" src="/img/favicon.png" width="20px"> Ephew</a>
      <a href="/create/">➕ New post</a>
      <a href="/about/">❔Info</a>
      <a href="/feedback/">❕Feedback</a>
    </div>
    <div class="content" align="center">

      <h1>Good <span id="wishes">day</span>, <b><?php echo $_SESSION['username']; ?></b>!</h1>
      <p>Welcome to Ephew.</p>
      <button onClick="window.location.reload(true)" class="ephew-buttons ephew-button-big">Reload timeline!</button><br></br>
      <?php include('timeline.php'); ?>
    </div>
  <?php
}

  ?>
  <script type="text/javascript">
    var today = new Date()
    var curHr = today.getHours()
    var wishes = null;

    if (curHr < 12) {
      var wishes = "Morning";
    } else if (curHr < 18) {
      var wishes = "Afternoon";
    } else {
      var wishes = "Evening";
    }

    document.getElementById("wishes").innerHTML = wishes;
  </script>
  <?php
  unifiedfooter();
  ?>