<?php
if (!isset($_REQUEST['posttype'])) {
    goto choosetype;
}
if ($_REQUEST['posttype'] == 'post') {
    unifiedheader(true, "New post");
?>
                <form action="/write.php" method="POST" class="big-ephew-form">
                    <div class="centered">
                        <h1>New post</h1>
                        <hr>
                        <div class="ephew-formlabels"><label for="privacy">
                                <div class="ephew-formlabels"><label for="phewcontent">
                                        <textarea name="phewcontent" class="ephew-textinputtablebox" required maxlength="300" style="height: 200px; width: 90%"></textarea>
                                        <p style="font-size: x-small;">300 characters maximum!</p><br>
                                    </label></div>
                                <h4>Privacy</h4>
                                <select name="privacy" required class="ephew-inputtablebox" style="width:100px;">
                                    <option value="public">Public</option>
                                    <option value="private" disabled>Fwends only</option>
                                    <option value="public-readonly" disabled>Comments off</option>
                                </select>
                            </label>
                        </div>
                        <input type="submit" class="ephew-buttons ephew-button-big" value="Post it!">
                    </div>
                    <input type=hidden name=phewtype value="post">
                    <input type="hidden" name=alttext value="na" required>
                </form>
        </div>
    <?php
                unifiedfooter();
            }
            if ($_REQUEST['posttype'] == 'article') {
                unifiedheader(true, "New post - Writing an article");
    ?>
        <form action="/write.php" method="POST" class="big-ephew-form">
            <div class="centered">
                <h1>Article editor</h1>
                <hr>
                <div class="ephew-formlabels"><label for="privacy">
                        <h4>Privacy</h4>
                        <select name="privacy" required class="ephew-inputtablebox" style="width:100px;">
                            <option value="public">Public</option>
                            <option value="private" disabled>Fwends only</option>
                            <option value="public-readonly" disabled>Comments off</option>
                        </select>
                    </label></div>
                <div class="ephew-formlabels"><label for="alttext">
                        <h4>Title</h4><input type=text name=alttext value="title" minlength="6" class="ephew-textinputtablebox" required>
                        <p><i>A short version of your article, for displaying on the timeline! (6 characters minimum)</i></p>
                    </label></div>
                <div class="ephew-formlabels"><label for="phewcontent">
                        <h4>Write your article</h4>
                        <p><i>In the Ephew-Markdown Article editor. Built on SimpleMDE.</i></p>
                        <p style="font-size: x-small;">100 characters minimum!</p>
                </div>
                <div><textarea name="phewcontent" id="MarkDownArticleEditor" class="ephew-textinputtablebox" required minlength="100"></textarea></div><br>
                </label>
            </div>
            <div class="centered">
                <input type="submit" class="ephew-buttons ephew-button-big" value="Post it!">
                <input type=hidden name=phewtype value="article">
            </div>
        </form>
        </div>
        <?php
                unifiedfooter();
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
        </script> <?php
                }
                if ($_REQUEST['posttype'] == 'media') {
                    unifiedheader(true, "New post - Writing an article");
                    ?>
        <form action="/write-media/" method="POST" class="big-ephew-form" style="text-align: center;">
            <h1>Media post</h1>
            <hr>
            <p>Ephew media posts are planned to support multiple media files at once.</p>
            <div class="ephew-formlabels"><label for="privacy">
                    <h4>Privacy</h4>
                    <select name="privacy" required class="ephew-inputtablebox" style="width:100px;">
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
                </div><br <div class="ephew-formlabels"><label for="alttext">
                    <h3>What is it?</h3><input type=text name=alttext value="" class="ephew-textinputtablebox">
                    <p><i>An alt text for your upload, or something you want to say about it.</i></p>
                </label>
            </div>
            </div>
            <hr>
            <input type="submit" class="ephew-buttons ephew-button-big" value="Post it!" disabled>
            <input type=hidden name=phewtype value="media">


        </form>
        </div>
    <?php
                    unifiedfooter();
                }
                if ($_REQUEST['posttype'] == 'link') {
                    unifiedheader(true, "New post - Posting a link");
    ?>
        <form action="/write.php" method="POST" class="big-ephew-form">
            <div class="centered">
                <h1>Post a link!</h1>
                <p>Your link will be embedded using the Ephew-URL-embedder.</p>
                <hr>
                <div class="ephew-formlabels"><label for="privacy">
                        <h4>Privacy</h4>
                        <select name="privacy" required class="ephew-inputtablebox" style="width:100px;">
                            <option value="public">Public</option>
                            <option value="private" disabled>Fwends only</option>
                            <option value="public-readonly" disabled>Comments off</option>
                        </select>
                    </label></div>
                <div class="ephew-formlabels"><label for="phewcontent">
                        <h4>What link do you want to post?</h4>
                        <p><i>Put in the link to what you want to share!</i></p>
                        <p style="font-size: x-small;">Is it a cool blog? Is it a youtube video? Anything.</p>
                        <div><input name="phewcontent" type="url" class="ephew-textinputtablebox" value="https://..." required></div><br>
                    </label></div>
                <div class="ephew-formlabels"><label for="alttext">
                        <h4>What is it?</h4><input type=text name=alttext value="" class="ephew-textinputtablebox">
                        <p><i>An alt text for your link, or something you want to say about it.</i></p>
                    </label></div>
                <div class="centered">
                    <input type="submit" class="ephew-buttons ephew-button-big" value="Post it!">
                    <input type=hidden name=phewtype value="link">
                </div>
        </form>
        </div>
    <?php
                    unifiedfooter();
                }
                exit;
                choosetype:
                if (session_id() == '') {
                    session_start();
                }
                if (!isset($_SESSION["username"])) {
                    header("Location: /login/");
                    exit();
                }
                $pagetitle = "New post - Choose a post type";

                unifiedheader(true, $pagetitle);
    ?>
    <h1>Choose post type</h1>
    <div class="ephew-form">
        <a href="?posttype=post"><button class="ephew-buttons ephew-button-big">Plain post</button></a>
        <a href="?posttype=media"><button class="ephew-buttons ephew-button-big">Photo/video post</button></a>
        <a href="?posttype=article"><button class="ephew-buttons ephew-button-big">Article post</button></a>
        <a href="?posttype=link"><button class="ephew-buttons ephew-button-big">Link post</button></a>
    </div>


<?php
            unifiedfooter();
            die;
            ?>