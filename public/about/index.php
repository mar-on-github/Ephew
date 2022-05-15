<?php
$pagetitle = "About";
$usedefaultsidebar = "true";
include __DIR__ . ("/../internals/unifiedheader.php");
?><CENTER>
    <?php
    $abouttext = file_get_contents(__DIR__ . '/../../readme.md');


    require_once __DIR__ . "/../vendor/autoload.php";
    $Parsedown = new Parsedown();

    echo $Parsedown->text($abouttext);
    ?></CENTER>
<?php
include __DIR__ . ("/../internals/unifiedfooter.php");
?>