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
  <h4 class="modal-title col-12 text-center" id="myModalLabel"><?=$item->title?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12 text-center">
      <?php if ($item->type == 1): // GAME ?>

        <p>To include this game/media in a course, or on any page, copy the text below and paste it into the page HTML.</p>
        <textarea rows="5" cols="70">
          <p align="center"><iframe src="<?=$item->link?>" allowfullscreen="" frameborder="0"></iframe></p>
        </textarea>


      <?php elseif ($item->type == 2): // PROJECT ?>


        <p>To include this project in a course, or on any page, copy the text below and paste it into the page HTML.</p>
        <textarea rows="12" cols="100">
          <table align="center" border="1" style="width: 600px;">
            <tbody>
              <tr>
                <td width="200px"><a href="<?=$item->link?>"><img src="<?=$item->image?>" width="200" height="150" /></a></td>
                <td><b><span style="background-color: transparent; color: #7d9fd3; font-size: 16px;"><?=$item->title?></span><br /></b><br /><span style="color: #c8c8c8;">Topics: <?=local_lor_get_keywords_string_for_item($item->id)?></span></td>
              </tr>
            </tbody>
          </table>
        </textarea>
        <embed src="<?=$item->link?>" width="700" height="800" type='application/pdf'>

      <?php endif // todo: video section once implemented ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <p><b><i>Note:</i></b> Please contact WCLN if you would like to use this LOR item outside of bclearningnetwork.com</p>
    </div>
  </div>
<!-- End of modal HTML. -->
</div>
<div class="modal-footer">
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
