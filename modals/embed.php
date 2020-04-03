<?php

// Required php files.
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../locallib.php');

if (! (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
    redirect('index.php');
    // TODO: in future may be able to link to embed modal and auto open if an id is set.
    // if (isset($_GET['id'])) {
    //   redirect("index.php?embed=" . $_GET['id']);
    // } else {
    //   redirect('index.php');
    // }
}

// Check if an id parameter was supplied.
if (isset($_GET['id'])) {
// Retrieve the id.
$id   = $_GET['id'];
$item = local_lor_get_content_from_id($id);

// Check if an item with that id exists in the database.
if ($item !== false) {
?>

<?php if (isloggedin()): ?>
<!-- Modal -->
<div class="lor-modal-header">
    <h4 class="lor-modal-title col-12 text-center" id="myModalLabel"><?= $item->title ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="lor-modal-body">
    <div class="row">
        <div class="col-md-12 text-center">
            <?php if ($item->type == 1): // Game. ?>

                <p>To include this game/media in a course, or on any page, copy the text below and paste it into the
                    page HTML.</p>
                <textarea rows="5" cols="70">
            <p align="center"><iframe src="<?= $item->link ?>" width="<?= $item->width ?>px"
                                      height="<?= $item->height ?>px" allowfullscreen="" frameborder="0"></iframe></p>
          </textarea>


            <?php elseif ($item->type == 2 || $item->type == 7): // Project or Group Activity. ?>


                <p>To include this project in a course, or on any page, copy the text below and paste it into the page
                    HTML.</p>
                <textarea rows="12" cols="100">
            <table align="center" border="1" style="width: 600px;">
              <tbody>
                <tr>
                  <td width="200px"><a href="<?= $item->link ?>"><img src="<?= $item->image ?>" width="200"
                                                                      height="150"/></a></td>
                  <td><b><span style="background-color: transparent; color: #7d9fd3; font-size: 16px;"><?= $item->title ?></span><br/></b><br/><span
                              style="color: #c8c8c8;">Topics: <?= local_lor_get_topics_string_for_item($item->id) ?></span></td>
                </tr>
              </tbody>
            </table>
          </textarea>

            <?php elseif ($item->type == 3): // Video. ?>

                <p>To include this video tutorial in a course, or on any page, copy the text below and paste it into the
                    page HTML.</p>
                <textarea rows="5" cols="70">
            <p align="center"><iframe
                        src="https://www.youtube.com/embed/<?= local_lor_get_video_id_from_content_id($item->id) ?>?rel=0"
                        allowfullscreen="" frameborder="0" height="360" width="640"
                        longdesc="<?= $item->title ?>"></iframe></p>          </textarea>

            <?php elseif ($item->type == 5): // Lesson. ?>

                <p>Lessons may not be embedded. For more information on including lessons in your course. Contact
                    WCLN.</p>

            <?php elseif ($item->type == 6): // Learning Guide. ?>

                <textarea rows="6" cols="100">
            <a href="<?= $item->link ?>">
              <img src="<?= $item->image ?>" width="200px" height="150px"/>
            </a>
          </textarea>

            <?php endif ?>
        </div>
    </div>

    <?php if ($item->type != 5): // If the item is anything except a Lesson. ?>

        <div class="row">
            <div class="col-md-12 text-center">
                <p><b><i>Note:</i></b> Please contact WCLN if you would like to use this LOR item outside of wcln.ca</p>
            </div>
        </div>
        <div class="lor-modal-footer">
            <p id="copy-success">Copied!</p>
            <button type="button" class="btn btn-primary" onclick="copyToClipboard()">Copy to Clipboard</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

    <?php else: ?>

        <div class="lor-modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

    <?php endif ?>
    <!-- End of modal HTML. -->
    <?php else: ?>
        <div class="lor-modal-header">
            <h4 class="lor-modal-title" id="myModalLabel">Login Required</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        </div>
        <div class="lor-modal-body">
            <p>A WCLN account is required to view the item embed code. Please <a
                        href="<?= "$CFG->wwwroot/login/index.php" ?>/">login</a> to view this content.</p>

        </div>
        <div class="lor-modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    <?php endif ?>

    <script>
        function copyToClipboard() {
            $('textarea').select();
            document.execCommand('copy');
            $('p#copy-success').css('visibility', 'visible');
        }
    </script>

    <?php
    // Error handling in case of missing ID.
    } else {
        ?>
        <div class="lor-modal-header">
            <h4 class="lor-modal-title" id="myModalLabel">Error</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        </div>
        <div class="lor-modal-body">
            <p>Invalid LOR item ID provided.</p>
        </div>
        <div class="lor-modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <?php
    }
    } else {
        ?>
        <div class="lor-modal-header">
            <h4 class="lor-modal-title" id="myModalLabel">Error</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        </div>
        <div class="lor-modal-body">
            <p>Missing LOR item ID.</p>
        </div>
        <div class="lor-modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <?php
    }
    ?>
