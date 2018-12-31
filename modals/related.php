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
<div class="lor-modal-header">
  <h4 class="lor-modal-title col-12 text-center" id="myModalLabel">Related Functionality is Currently Under Construction</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="lor-modal-body">
  <div class="row">
    <div class="col-md-12 text-center">
      <img src="images/under_construction.png" height="150px">
      <h5>What will this page look like?</h5>
      <p style="max-width: 815px">Items in the Learning Object Repository which are often used in conjunction with the item you clicked will be shown here. For examle, if you clicked on a video, the lesson in which that video is referenced may appear here, as well as any other items which also appear in that lesson.</p>
    </div>
  </div>
<!-- End of modal HTML. -->
</div>
<div class="lor-modal-footer">
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
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
