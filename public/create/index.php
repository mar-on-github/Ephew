<?php
if (!isset($_REQUEST['posttype'])) {
    goto choosetype;
}
if ($_REQUEST['posttype'] == 'article') {
    include './article.php';
    exit;
}
if ($_REQUEST['posttype'] == 'media') {
    include './media.php';
    exit;
}
if ($_REQUEST['posttype'] == 'link') {
    include './link.php';
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
    <a href="?posttype=post"><button class="ephew-buttons ephew-button-big">Plain post</button></a>
    <a href="?posttype=media"><button class="ephew-buttons ephew-button-big">Photo/video post</button></a>
    <a href="?posttype=article"><button class="ephew-buttons ephew-button-big">Article post</button></a>
    <a href="?posttype=link"><button class="ephew-buttons ephew-button-big">Link post</button></a>
</div>


<?php
include __DIR__ . ("/../../src/Ephew-internals/unifiedfooter.php");
?>