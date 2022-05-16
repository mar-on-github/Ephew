<?php
$pagetitle = "New post - Writing an article";
$usedefaultsidebar = "true";
include __DIR__ . ("/../../src/Ephew-internals/unifiedheader.php");
?>
<form target="_blank" action="/write.php" method="POST" class="big-ephew-form">
    <div class="centered">
        <h1>Article editor</h1>
        <hr>
        <div class="ephew-formlabels"><label for="privacy">
            <h4>Privacy</h4>
            <select name="privacy" required class="ephew-inputtablebox">
                <option value="public">Public</option>
                <option value="private" disabled>Fwends only</option>
                <option value="public-readonly" disabled>Comments off</option>
            </select>
        </label></div>
        <div class="ephew-formlabels"><label for="alttext">
            <h4>Title</h4><input type=text name=alttext value="title" minlength="6" class="ephew-inputtablebox" required>
            <p><i>A short version of your article, for displaying on the timeline! (6 characters minimum)</i></p>
        </label></div>
        <div class="ephew-formlabels"><label for="phewcontent">
            <h4>Write your article</h4>
            <p><i>In the Ephew-Markdown Article editor. </i></p>
            <p><small>100 characters minimum!</small></p>
    </div>
    <div><textarea name="phewcontent" id="MarkDownArticleEditor" class="ephew-inputtablebox" required minlength="100"></textarea></div><br>
    </label></div>
    <div class="centered">
        <input type="submit" class="ephew-buttons ephew-button-big" value="Post it!">
        <input type=hidden name=phewtype value="article"></div>
</form>
</div>
<?php
include __DIR__ . ("/../../src/Ephew-internals/unifiedfooter.php");
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    new SimpleMDE({
        element: document.getElementById("MarkDownArticleEditor"),
        autosave: {
            enabled: true,
            unique_id: "articlewriter-<?php echo session_id(); ?>",
        },
        spellChecker: false,
        forceSync: true,
    });
</script>