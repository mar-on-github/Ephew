<?php
$pagetitle = "The Ephew Blog";
$usedefaultsidebar = "true";
unifiedheader($usedefaultsidebar, $pagetitle);
?>
    <ul class="timeline">
        <?php
        $postsbyid = file('blogpostslistedbymar.txt');
        foreach ($postsbyid as $idofpost) {
            $clean_id = preg_replace(
                "/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/",
                "",
                $idofpost
            );
            if (compilepost($clean_id, 'posttype') === 'article') {
                echo "<li class=\"postview\ article\">";
            } else {
                echo "<li class=\"postview\">";
            }
            comcompost($clean_id);
            echo "</li>\n<br>\n";
        }
        ?>
    </ul>
<?php
unifiedfooter();
?>