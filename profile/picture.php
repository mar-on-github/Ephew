<?php
if(!(isset($_GET['for']))) {exit;}
$profilephotoindexing_file = "./profilephotos/index/index_";
$profilephotoindexing_file .= md5($_GET['for']);
$profilephotoindexing_file .= ".txt";
$f = fopen($profilephotoindexing_file, 'r');
$raw_image_url = fgets($f);
fclose($f);
$image_url= "./profilephotos/$raw_image_url";
header('Content-type: image/jpeg');
readfile($image_url);
// echo $image_url;
exit;
?>