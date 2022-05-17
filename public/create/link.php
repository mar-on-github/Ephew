<?php
$pagetitle = "New post - Writing an article";
$usedefaultsidebar = "true";
include __DIR__ . ("/../../src/Ephew-internals/unifiedheader.php");
?>
<form target="_blank" action="/write.php" method="POST" class="big-ephew-form">
    <div class="centered">
        <h1>Post a link!</h1>
        <p>Your link will be embedded using the Ephew-URL-embedder.</p>
        <hr>
        <div class="ephew-formlabels"><label for="privacy">
                <h4>Privacy</h4>
                <select name="privacy" required class="ephew-inputtablebox">
                    <option value="public">Public</option>
                    <option value="private" disabled>Fwends only</option>
                    <option value="public-readonly" disabled>Comments off</option>
                </select>
            </label></div>
        <div class="ephew-formlabels"><label for="phewcontent">
                <h4>What link do you want to post?</h4>
                <p><i>Put in the link to what you want to share!</i></p>
                <p style="font-size: x-small;">Is it a cool blog? Is it a youtube video? Anything.</p>
                <div><input name="phewcontent" type="url" class="ephew-inputtablebox" value="https://..." required></div><br>
            </label></div>
        <div class="ephew-formlabels"><label for="alttext">
                <h4>What is it?</h4><input type=text name=alttext value="" class="ephew-inputtablebox">
                <p><i>An alt text for your link, or something you want to say about it.</i></p>
            </label></div>
        <div class="centered">
            <input type="submit" class="ephew-buttons ephew-button-big" value="Post it!">
            <input type=hidden name=phewtype value="link">
        </div>
</form>
</div>
<?php
include __DIR__ . ("/../../src/Ephew-internals/unifiedfooter.php");
?>