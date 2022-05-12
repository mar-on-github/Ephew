<?php
$pagetitle = "About";
$usedefaultsidebar = "true";
include("../internals/unifiedheader.php");
?><CENTER>
    <?php
    $abouttext = file_get_contents('../readme.md');


    require_once __DIR__ . "/../vendor/autoload.php";
    $Parsedown = new Parsedown();

    echo $Parsedown->text($abouttext);
    ?></CENTER>
<?php
include("../internals/unifiedfooter.php");
?>