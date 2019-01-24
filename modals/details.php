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
  <h4 class="lor-modal-title col-12 text-center" id="myModalLabel"><?=$item->title?></h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="lor-modal-body">
  <div class="row">
    <div class="col-md-12 text-center">
      <!-- Item details. -->
      <div id="item-details" class="text-left">
        <ul>
          <li><span class="label label-default">Categories:</span> <?=local_lor_get_categories_string_for_item($item->id)?></li>
          <li><span class="label label-danger">Grades:</span> <?=local_lor_get_grades_string_for_item($item->id)?:"N/A"?></li>
          <li><span class="label label-success">Topics:</span> <?=local_lor_get_topics_string_for_item($item->id)?></li>
          <li><span class="label label-info">Contributor(s):</span> <?=local_lor_get_contributors_string_for_item($item->id)?:"N/A"?></li>
          <li><span class="label label-warning">Date Created:</span> <?=date("F Y", strtotime($item->date_created))?></li> <!-- Date in format: June 2017 -->
        </ul>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <!-- Actual LOR item displayed/embedded. -->
      <?php if ($item->type == 1): // Game. ?>

        <p align="center"><iframe width="<?=$item->width?>px" height="<?=$item->height?>px" src="<?=$item->link?>" allowfullscreen="" frameborder="0"></iframe></p>

      <?php elseif ($item->type == 2): // Project. ?>

        <embed src="<?=$item->link?>" width="700" height="800" type='application/pdf'>

      <?php elseif ($item->type == 3): // Video. ?>
        <p align="center"><iframe src="https://www.youtube.com/embed/<?=local_lor_get_video_id_from_content_id($item->id)?>?rel=0" allowfullscreen="" frameborder="0" height="360" width="640" longdesc="<?=$item->title?>"></iframe></p>
      <?php elseif ($item->type == 5): // Lesson. ?>
        <form id="lti-form" target="lesson_iframe" method="post" action="https://bclearningnetwork.com/local/LTI/request.php">
          <input type="hidden" name="oauth_consumer_key" value="consumerkey" />
          <input type="hidden" name="custom_book_id" value="<?=local_lor_get_book_id_from_content_id($item->id)?>" /><!-- temp book id -->
          <input type="submit">
        </form>
        <iframe id="lessonFrame" name="lesson_iframe" width="1200px" height="500px" src="#" scrolling="no">
        </iframe>
        <script>
          // Hide the form and perform the submit.
         $(document).ready(function(){
           var lessonForm= document.getElementById("lti-form");
           lessonForm.style.display = "none";
           lessonForm.submit();
         });

         // IFrame resizing script.
         window.addEventListener("message", resizeIframe);
         function resizeIframe() {
           // $(".lor-modal").scrollTop($("#lessonFrame").offset().top);
           document.getElementById("lessonFrame").height = event.data;
           document.getElementById("lessonFrame").style.height = event.data + "px";
         }
        </script>
      <?php elseif ($item->type == 6): // Learning Guide. ?>

        <embed src="<?=$item->link?>" width="700" height="800" type='application/pdf'>

      <?php endif ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <p><b><i>Note:</i></b> Please contact WCLN if you would like to use this LOR item outside of bclearningnetwork.com</p>
    </div>
  </div>
<!-- End of modal HTML. -->
</div>
<div class="lor-modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

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
