<?php
$pagetitle = "Editing your profile";
$usedefaultsidebar = "true";
include __DIR__ . ("/../../internals/unifiedheader.php");
?>
<form action="/profile/edit/uploadpfp.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Set profile image" name="submit">
</form>
<?php
include("../../internals/unifiedfooter.php");
?>