<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');


// checking URL for variables
$search_categories = null;
$search_type = null;
$search_grades = null;
$search_keywords = null;
$order_by = "new";


if (isset($_GET['categories'])) {
  if ($_GET['categories'] !== "-1") {
    $search_categories = $_GET['categories'];
  }
}

if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
}

if (isset($_GET['type'])) {
  $search_type = $_GET['type'];
}

if (isset($_GET['grades'])) {
  $search_grades = $_GET['grades'];
}

if (isset($_GET['keywords'])) {
  $search_keywords = $_GET['keywords'];
}

// setting up the page
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("WCLN: LOR");
$PAGE->set_heading("WCLN Learning Material");
$PAGE->set_url(new moodle_url('/local/lor/index.php'));

// nav bar
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('lor', 'local_lor'), new moodle_url('/local/lor/index.php'));

echo $OUTPUT->header();



// setting variables
$content = local_lor_get_content($search_type, $search_categories, $search_grades, $order_by, $search_keywords);
$categories = local_lor_get_categories();
$types = local_lor_get_types();
$grades = local_lor_get_grades();

?>


<link rel="stylesheet" href="styles.css">

<!-- multiple select -->
<link href="lib/multiple-select/multiple-select.css" rel="stylesheet"/>


<h1>WCLN Learning Material</h1>
<div class="container-fluid" id="content-container">


  <!-- Filter settings -->
  <div class="row-fluid" id="filters">
    <form action="index.php" method="GET">

      <b>Type:</b>
      <select class="lor-select" name="type" id="type-select">
        <option value="-1">All Types</option>
        <?php foreach($types as $type): ?>
          <?php if ($type->id == $search_type): ?>
            <option selected="selected" value="<?=$type->id?>"><?=$type->name?></option>
          <?php else: ?>
            <option value="<?=$type->id?>"><?=$type->name?></option>
          <?php endif ?>
        <?php endforeach ?>
      </select>

      <b>Categories:</b>
      <select name="categories[]" class="multiple lor-select" multiple="multiple">
      <?php foreach ($categories as $category): ?>
        <?php if (in_array($category->id, $search_categories)): ?>
          <option selected="selected" value="<?=$category->id?>"><?=$category->name?></option>
        <?php else: ?>
          <option value="<?=$category->id?>"><?=$category->name?></option>
        <?php endif ?>
      <?php endforeach ?>
      </select>

      <b>Grades:</b>
      <select name="grades[]" class="multiple lor-select" multiple="multiple">
        <?php foreach ($grades as $grade): ?>
          <?php if (in_array($grade->grade, $search_grades)): ?>
            <option selected="selected" value="<?=$grade->grade?>"><?=$grade->grade?></option>
          <?php else: ?>
            <option value="<?=$grade->grade?>"><?=$grade->grade?></option>
          <?php endif ?>
        <?php endforeach ?>
      </select>

      <b>Sort by:</b>
      <select class="lor-select" name="order_by">
        <option value="new">Recently Added</option>
        <?php if ($order_by === "alphabetical"): ?>
          <option value="alphabetical" selected="selected">Alphabetical</option>
        <?php else: ?>
          <option value="alphabetical">Alphabetical</option>
        <?php endif ?>
      </select>

      <input type="text" placeholder="Keywords..." name="keywords" value="<?=$search_keywords?>">

      <button type="submit" class="btn btn-primary">Search</button>
  </form>
  </div>

  <!-- Content -->
  <div class="row-fluid text-center">
    <p id="count"><i><?=count($content)?> items match your search</i></p>

    <?php if (!is_null($content)): ?>
      <?php foreach ($content as $item): ?>
      </div>
      <div class="row text-center item">
        <hr>
      <div class="span4 item-image">
        <a href="show.php?id=<?=$item->id?>" target="_blank">
          <img class="lor-image" src="<?=$item->image?>" width="200px" height="150px" />
        </a>

      </div>
      <div class="span7 project-about text-left">
        <a href="show.php?id=<?=$item->id?>" target="_blank"><h3><?=$item->title?></h3></a>
        <p><i>Topics: </i><?=local_lor_get_keywords_string_for_item($item->id)?></p>
      </div>
    <?php endforeach ?>
    <?php endif ?>
    <hr>
</div>

<script src="lib/multiple-select/multiple-select.js"></script>
<script>
    $('.multiple').multipleSelect();
</script>



<?php
echo $OUTPUT->footer();
?>
