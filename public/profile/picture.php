<?php
if(!(isset($_GET['for']))) {exit;}
$profilephotoindexing_file = "./profilephotos/index/index_";
$profilephotoindexing_file .= md5($_GET['for']);
$profilephotoindexing_file .= ".txt";
if (file_exists($profilephotoindexing_file)) {
$f = fopen($profilephotoindexing_file, 'r');
$raw_image_url = fgets($f);
fclose($f);
$image_url= "./profilephotos/$raw_image_url";
} ELSE { $image_url = __DIR__ . "/../IMG/defaultuserprofile.png"; }
header('Content-type: image/jpeg');
if (file_exists($image_url)) {
readfile($image_url);
}
exit;
?>