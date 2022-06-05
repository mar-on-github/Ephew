<?php
if (session_id() == '') {
        session_start();
}
if (!isset($_SESSION["username"])) {
        header("Location: /login/");
        exit();
}
if (isset($_FILES["fileToUpload"])) {
$target_dir = "../profilephotos/";
$target_file = $target_dir . "profilephoto_" . $_SESSION['username'] . "_" . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if($check !== false) {
echo "File is an image - " . $check["mime"] . ".";
$uploadOk = 1;
} else {
echo "File is not of supported format.";
$uploadOk = 0;
}
}

// Check if file already exists
if (file_exists($target_file)) {
echo "Sorry, file already exists.";
$uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
echo "Sorry, your file is too large.";
$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

                echo "<meta http-equiv=\"Refresh\" content=\"5; url='/profile'\" />";
$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
echo "Sorry, your file was not uploaded.";

                echo "<meta http-equiv=\"Refresh\" content=\"5; url='/profile'\" />";
// if everything is ok, try to upload file
} else {
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        $profilephotoindexing_file = "../profilephotos/index/index_";
        $profilephotoindexing_file .= md5($_SESSION["username"]);
        $profilephotoindexing_file .= ".txt";
        file_put_contents($profilephotoindexing_file,$target_file);
        echo "<meta http-equiv=\"Refresh\" content=\"5; url='/profile'\" />";
} else {
echo "Sorry, there was an error uploading your file.";

                        echo "<meta http-equiv=\"Refresh\" content=\"5; url='/profile'\" />";
}
}
} ELSE {
        $profilephotoindexing_file = "../profilephotos/index/index_";
        $profilephotoindexing_file .= md5($_SESSION["username"]);
        $profilephotoindexing_file .= ".txt";
        $blanche_pfp = "../../img/defaultuserprofile.png";
        file_put_contents($profilephotoindexing_file, $blanche_pfp);
        echo "Reset your pfp to default.";
        echo "<meta http-equiv=\"Refresh\" content=\"5; url='/profile'\" />";
}