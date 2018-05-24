<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');

$id = $_GET['id'];
$item = local_lor_get_content_from_id($id);

// setting up the page
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("BCLN: " . $item->title);
$PAGE->set_heading("LOR Item");
$PAGE->set_url($CFG->wwwroot.'/local/lor/show.php?id='.$id);

// nav bar
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));
$PAGE->navbar->add($item->name, new moodle_url('/local/lor/show.php?id='.$id));

echo $OUTPUT->header();
?>

<link rel="stylesheet" href="styles.css">


<div "container-fluid">
  <div class="row-fluid" id="item-info">
    <h1><?=$item->title?></h1>
    <ul>
      <li><b>Categories</b>: <?=local_lor_get_categories_string_for_item($item->id)?></li>
      <li><b>Grades</b>: <?=local_lor_get_grades_string_for_item($item->id)?></li>
      <li><b>Keywords</b>: <?=local_lor_get_keywords_string_for_item($item->id)?></li>
      <li><b>Contributor(s)</b>: <?=local_lor_get_contributors_string_for_item($item->id)?></li>
      <li><b>Date Created:</b> <?=date("F Y", strtotime($item->date_created))?></li> <!-- Date in format: June 2017 -->
    </ul>


    <?php if ($item->type == 1): // GAME ?>

      <p>To include this <?=strtolower($item->name)?> in a course, or on any page, copy the text below and paste it into the page HTML.</p>
      <textarea rows="5" cols="70">
      </textarea>
      <p><iframe src="<?=$item->link?>" allowfullscreen="" frameborder="0"></iframe></p

    <?php elseif ($item->type == 2): // PROJECT ?>


      <p>To include this <?=strtolower($item->name)?> in a course, or on any page, copy the text below and paste it into the page HTML.</p>
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

    <?php elseif ($item->type == 4): // ANIMATION ?>


      <p>To include this <?=strtolower($item->name)?> in a course, or on any page, copy the text below and paste it into the page HTML.</p>
      <textarea rows="5" cols="70">
      </textarea>
      <p align="center"><img id="animation-img" src="<?=$item->link?>" class="img-responsive atto_image_button_middle"></p>​

    <?php endif // todo: video section once implemented ?>

    <p><b><i>Note:</i></b> Please contact BCLN if you would like to use this <?=strtolower($item->name)?> outside of bclearningnetwork.com</p>
  </div>


  <a href="index.php"><button>Back</button></a>

</div>

<?php if ($item->type == 1): ?>
  <script>
  /*
   * Compute width and height of iframe from the width and height of the canvas (for a game)
   */

  $(function() {

      // iframe loaded (games)
      $("iframe").bind("load",function() {
        // defaults in case of Flash
        var width = 700;
        var height = 700;
        // HTML5, assuming this is usually true
        if (<?=$item->platform?> === 1) {
          width = ($(this).contents().find('canvas').width()) + 10;
          height = ($(this).contents().find('canvas').height()) + 10;
          if (isNaN(width) || isNaN(height)) { // another check just in case gameshow
            width = 1025;
            height = 630;
          }
        }
        $('iframe').attr('width', width);
        $('iframe').attr('height', height);
        $('textarea').val('<p><iframe src="<?=$item->link?>" allowfullscreen="" frameborder="0" width="'+width+'" height="'+height+'"></iframe></p>');
      });
  });
  </script>
<?php endif ?>

<?php if ($item->type == 4): ?>
  <script>
      // image loaded (animations)
      var img = new Image();
      alert("this");
      img.onload = function() {
        $('#animation-img').attr('width', this.width);
        $('#animation-img').attr('height', this.height);
        $('textarea').val('<p align="center"><img src="<?=$item->link?>" width="'+this.width+'" height="'+this.height+'" class="img-responsive atto_image_button_middle"></p>​')
      }
      img.src = "<?=$item->link?>";
  </script>
<?php endif ?>


<?php
echo $OUTPUT->footer();
?>
