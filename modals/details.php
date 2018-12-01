<?php
// Required php files.
require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../locallib.php');

// Check if an id parameter was supplied.
if (isset($_GET['id'])) {
  // Retrieve the id.
  $id = $_GET['id'];
  $item = local_lor_get_content_from_id($id);

  // Check if an item with that id exists in the database.
  if ($item !== false) {
?>

<!-- Modal -->
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel"><?=$item->title?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">

  <!-- Item details. -->
  <ul>
    <li><b>Categories</b>: <?=local_lor_get_categories_string_for_item($item->id)?></li>
    <li><b>Grades</b>: <?=local_lor_get_grades_string_for_item($item->id)?></li>
    <li><b>Keywords</b>: <?=local_lor_get_keywords_string_for_item($item->id)?></li>
    <li><b>Contributor(s)</b>: <?=local_lor_get_contributors_string_for_item($item->id)?></li>
    <li><b>Date Created:</b> <?=date("F Y", strtotime($item->date_created))?></li> <!-- Date in format: June 2017 -->
  </ul>

  <!-- Actual LOR item displayed/embedded. -->
  <?php if ($item->type == 1): // Game ?>

    <p align="center"><iframe src="<?=$item->link?>" allowfullscreen="" frameborder="0"></iframe></p

  <?php elseif ($item->type == 2): // Project ?>

    <embed src="<?=$item->link?>" width="700" height="800" type='application/pdf'>

  <?php endif // TODO: videos, lessons, learning guides. ?>

  <p><b><i>Note:</i></b> Please contact WCLN if you would like to use this LOR item outside of bclearningnetwork.com</p>
  <?php if ($item->type == 1): ?>
        <script>
        /*
         * Compute width and height of iframe from the width and height of the canvas (for a game).
         */
        $(function() {

            // iframe loaded.
            $(".modal iframe").bind("load",function() {
              // defaults in case of Flash
              var width = 700;
              var height = 700;
              width = ($(this).contents().find('canvas').width()) + 10;
              height = ($(this).contents().find('canvas').height()) + 10;
              if (isNaN(width) || isNaN(height)) { // another check just in case gameshow
                width = 1025;
                height = 700;
              }
              <?php
              // Check if there is a manually set width and height, if so override the calculated ones.
                if (!is_null($item->width)) {
                  echo "width = $item->width;";
                }
                if (!is_null($item->height)) {
                  echo "height = $item->height;";
                }
              ?>

              // Set the iframe width and height.
              $('iframe').attr('width', width);
              $('iframe').attr('height', height);
            });
        });
        </script>
      <?php endif ?>

<!-- End of modal HTML. -->
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

<?php
// Error handling in case of missing ID.
  } else {
?>
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Error</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <p>Invalid LOR item ID provided.</p>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<?php
  }
} else {
?>
  <div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Error</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
      <p>Missing LOR item ID.</p>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
<?php
}
?>
