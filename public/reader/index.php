<?php
unifiedheader();
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
?>

<button class="openbtn" onclick="openNav()">☰</button>
<div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="/home/">Ephew</a>
  <a href="/create/">➕ New post</a>
  <a href="/about/">❔Info</a>
  <a href="/feedback/">❕Feedback</a>
</div>
<div class="content">
  <?php
  if (compilepost($_GET["postid"], 'posttype') === 'article') {

  ?>
    <div class="postview postview-article">
      <?php
    echo "<div class=\"postedbyuserheader\">An article by <img loading=\"lazy\" src=\"/profile/picture.php?for="
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
    echo $Parsedown->text(compilepost($_GET["postid"],'postcontent'));
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
?>