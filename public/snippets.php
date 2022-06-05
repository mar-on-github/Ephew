<?php
// Functions that used to be separate files
function filestyleswitcher() {
    if (isset($_SESSION["themetype"])) {
        $themetype = $_SESSION["themetype"];
        echo "<!-- \$themetype is currently: '"
            . ($_SESSION["themetype"])
            . "'. -->";
        if ($_SESSION["themetype"] === 'light') {
?>
            <form method="post" id="themeswitchform"><input type="hidden" name="settheme" value="sepia">
                <a class="themeswitcher" href="#" onclick="document.getElementById('themeswitchform').submit()">📜Sepia theme</a>
            </form>
        <?php
        }
        if ($_SESSION["themetype"] === 'sepia') {
        ?>
            <form method="post" id="themeswitchform"><input type="hidden" name="settheme" value="dark">
                <a class="themeswitcher" href="#" onclick="document.getElementById('themeswitchform').submit()">🌓Dark theme</a>
            </form>
        <?php
        }
        if ($_SESSION["themetype"] === 'dark') {
        ?>
            <form method="post" id="themeswitchform"><input type="hidden" name="settheme" value="light">
                <a class="themeswitcher" href="#" onclick="document.getElementById('themeswitchform').submit();">💡Light theme</a>
            </form>
    <?php
        }
    }
    if (!isset($_SESSION["themetype"])) {
        if (isset($_COOKIE["themetype"])) {
            $_SESSION["themetype"] = $_COOKIE["themetype"];
            $themetype = $_SESSION["themetype"];
        } else {
            $_SESSION["themetype"] = 'light';
            $themetype = 'light';
        }
    }
}

function filetimeline(){
    echo "<ul class=\"timeline\">";
    $timelinedottxt = __DIR__ . "/timelinebyid.txt";
    $postsbyid = file($timelinedottxt);
    foreach ($postsbyid as $idofpost) {
        $clean_id = preg_replace(
            "/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/",
            "",
            $idofpost
        );
        if (compilepost($clean_id, 'posttype') === 'article') {
            echo "<li class=\"postview article\">";
        } else {
            echo "<li class=\"postview\">";
        }
        comcompost($clean_id);
        echo "</li>\n<br>\n";
    }
    echo "</ul>";
}

function unifiedheader(bool $usedefaultsidebar, string $pagetitle = NULL) {
    ?>
    <!--
    Starting this project today.
    - Mar Bloeiman
    2022-4-26
    -->
    <?php
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
        if ($usedefaultsidebar) { ?>
            <button class="openbtn" onclick="openNav()">☰</button>
            <div class="sidebar" id="mySidebar"><a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <?php
                bottombarlink('/home/', '<img loading="lazy" src="/img/favicon.png" width="20px"> Ephew</a>');
                bottombarlink('/create/', '➕ New post');
                bottombarlink('/about/', '❔Info');
                bottombarlink('/feedback/', '❕Feedback');
                ?>
            </div>
            <div class="content">
        <?php }
}

function unifiedfooter(bool $usedefaultsidebar = true, bool $autoendcontentdiv = true)
    {
        if (isset($usedefaultsidebar) and $usedefaultsidebar === true) {
            if (!isset($autoendcontentdiv) or !($autoendcontentdiv === false)) {
                echo "</div>";
            }
        }
            ?>
            <div class="bottombar" id="mybottombar">
                <a href="javascript:void(0);" class="icon" onclick="unrollbottombar()">&#9776;</a>
                <?php
                bottombarlink("/home/", "Home");
                if (!isset($_SESSION["username"])) {
                    bottombarlink("/login/", "Log in");
                    bottombarlink("/signup/", "Sign up");
                } else {
                    bottombarlink("/logout/", "Log out");
                }
                bottombarlink("/feedback/", "Feedback");
                bottombarlink("/blog/", "Blog");
                filestyleswitcher();
                ?>
            </div>

    </body>

    <script src="/scripts/responsivemenus.js"></script>

    </html>
<?php

    }
