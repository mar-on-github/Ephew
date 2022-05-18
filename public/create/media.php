<?php
$pagetitle = "New post - Writing an article";
$usedefaultsidebar = "true";
include __DIR__ . ("/../../src/Ephew-internals/unifiedheader.php");
?>
<form target="_blank" action="2.php" method="POST" class="big-ephew-form" style="text-align: center;">
    <h1>Media post</h1>
    <hr>
    <p>Ephew media posts are planned to support multiple media files at once.</p>
    <div class="ephew-formlabels"><label for="privacy">
            <h4>Privacy</h4>
            <select name="privacy" required class="ephew-inputtablebox">
                <option value="public">Public</option>
                <option value="private" disabled>Fwends only</option>
                <option value="public-readonly" disabled>Comments off</option>
            </select>
        </label></div>
    <hr>
    <div class="media-upload-frame">
        <div class="media-upload-center">
            <div class="media-upload-title">
                <p>Drop a media file to upload</p>
            </div><br style="size: 4px">


            <div class="media-upload-dropzone">
                <img loading="lazy" src="/img/media-upload.jpg" class="media-upload-icon" />
                <input type="file" class="media-upload-input" />
            </div><br style="size: 10px">
            <button type="button" class="ephew-buttons" name="media-upload" style="border-radius: 5px 5px 20px 20px;  border: 0;  outline: 0;  width: 255px;  height: 80px;  font-size: 16px;  padding: 17px;  bottom: 0px; text-align: -webkit-center;  text-align: center;  cursor: pointer;">Upload file</button>
        </div><br 
        <div class="ephew-formlabels"><label for="alttext">
                <h3>What is it?</h3><input type=text name=alttext value="" class="ephew-inputtablebox">
                <p><i>An alt text for your upload, or something you want to say about it.</i></p>
            </label></div>
    </div>
    <hr>
    <input type="submit" class="ephew-buttons ephew-button-big" value="Post it!">
    <input type=hidden name=phewtype value="media">


</form>
</div>
<?php
include __DIR__ . ("/../../src/Ephew-internals/unifiedfooter.php");
?>