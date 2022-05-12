<!-- Start of styleswitcher script -->
<?php
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
?>
<!-- End of styleswitcher script -->