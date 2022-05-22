<!--
Starting this project today.
- Mar Bloeiman
2022-4-26
-->
<?php
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/./functions.php";
$themetype = LocateStyleSheet();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="manifest" href="/ephew.webmanifest">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#04AA6D" />
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/favicon-512x512.png" />
    <link rel="stylesheet" href="/styles/ephew-base.css" content-type="text/css" charset="utf-8" />
    <?php
    echo "<link rel=\"stylesheet\" href=\"/styles/colors/";
    if (isset($_SESSION["themetype"])) {
        $themetype = ($_SESSION["themetype"]);
    } else {
        $themetype = 'light';
    }
    echo $themetype
        . ".css\" content-type=\"text/css\" charset=\"utf-8\"/>";
    echo "<!-- current theme loaded is:"
        . $themetype
        . "-->";
    if (isset($pagetitle)) {
        echo "<title>Ephew - "
            . $pagetitle
            . "</title>";
    }
    ?>
</head>

<body>
    <button id="install_button" hidden class="ephew-buttons ephew-button-big">Install Ephew as a web app!</button>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="module" src="/scripts/sw.js"></script>
    <script src="/scripts/nicm.js"></script>
    <?php
    if (isset($usedefaultsidebar)) {
        if ($usedefaultsidebar === 'true') {
    ?>
            <button class="openbtn" onclick="openNav()">☰</button>
            <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <?php
                bottombarlink('/home/', '<img loading="lazy" src="/img/favicon.png" width="20px"> Ephew</a>');
                bottombarlink('/create/', '➕ New post');
                bottombarlink('/about/', '❔Info');
                bottombarlink('/feeback/', '❕Feedback');
                ?>
            </div>
            <div class="content">
        <?php }
    } ?>