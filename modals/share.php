<?php
// Required php files.
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../locallib.php');

// Check if an id parameter was supplied.
if (isset($_GET['id'])) {
    // Retrieve the id.
    $id   = $_GET['id'];
    $item = local_lor_get_content_from_id($id);

    // Check if an item with that id exists in the database.
    if ($item !== false) {
        ?>

        <!-- Modal -->
        <div class="lor-modal-header">
            <h4 class="lor-modal-title col-12 text-center" id="myModalLabel"><?= $item->title ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        </div>
        <div class="lor-modal-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>To share a link to this LOR item, copy the URL below:</p>
                    <textarea rows="1" cols="70"><?= "$CFG->wwwroot/local/lor/index.php?id=$item->id" ?></textarea>
                </div>
            </div>
            <!-- End of modal HTML. -->
        </div>
        <div class="lor-modal-footer">
            <p id="copy-success">Copied!</p>
            <button type="button" class="btn btn-primary" onclick="copyToClipboard()">Copy to Clipboard</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

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
