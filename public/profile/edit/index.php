<?php
if (session_id() == '') {
    session_start();
}
if (!isset($_SESSION["username"])) {
    header("Location: /login/");
    exit();
}
$pagetitle = "Editing your profile";
$usedefaultsidebar = "false";
include __DIR__ . ("/../../../src/Ephew-internals/unifiedheader.php");
?>
<button class="openbtn" onclick="openNav()">☰</button>
<div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    <?php
    bottombarlink('/home/', '<img loading="lazy" src="/img/favicon.png" width="20px"> Ephew</a>');
    bottombarlink('/profile/edit/#profilepicture', 'Edit profile picture');
    bottombarlink('/profile/edit/#bio', 'Edit bio');
    bottombarlink('/feedback/', '❕Feedback');
    ?>
</div>
<div class="content">
    <form action="/profile/edit/uploadpfp.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Set profile image" name="submit">
    </form>
</div>
<?php
include __DIR__ . ("/../../../src/Ephew-internals/unifiedfooter.php");
?>