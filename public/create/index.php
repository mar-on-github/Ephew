<?php
if (!isset($_REQUEST['posttype'])) {
    goto choosetype;
}
if ($_REQUEST['posttype'] == 'article') {
    include './writearticle.php';
    exit;
}
choosetype:
include __DIR__ . ("/../auth_session.php");
?>
<?php
$pagetitle = "New post - Choose a post type";
$usedefaultsidebar = "true";
include __DIR__ . ("/../../src/Ephew-internals/unifiedheader.php");
?>
        <h1>Choose post type</h1>
        <div class="ephew-form">
            <a href="?posttype=post"><button class="ephew-buttons">Plain post</button></a>
            <a href="?posttype=media"><button class="ephew-buttons">Photo/video post</button></a>
            <a href="/create/writearticle.php"><button class="ephew-buttons">Article post</button></a>
            <a href="?posttype=link"><button class="ephew-buttons">Link post</button></a>
        </div>


<?php
include __DIR__ . ("/../../src/Ephew-internals/unifiedfooter.php");
?>