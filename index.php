<?php

require_once(__DIR__ . '/../../config.php'); // standard config file
require_once(__DIR__ . '/locallib.php');


// checking URL for variables
$search_category = null;
$order_by = "alphabetical";

if (isset($_GET['category'])) {
  if ($_GET['category'] !== "-1") {
    $search_category = $_GET['category'];
  }
}

if (isset($_GET['order_by'])) {
  $order_by = $_GET['order_by'];
}

// setting up the page
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("BCLN: LOR");
$PAGE->set_heading("BCLN Learning Material");
$PAGE->set_url(new moodle_url('/local/lor/index.php', array('category' => $search_category)));

echo $OUTPUT->header();



// setting variables
$content = local_lor_get_content($search_category, $order_by);
$categories = local_projectspage_get_all_categories();

?>
<!-- TODO: replace these with better links -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="styles.css">

<h1>BCLN Projects</h1>
<div class="container-fluid" id="projects-container">


  <!-- Filter settings -->
  <div class="row" id="filters">
    <form action="index.php" method="GET">
      <b>Show:</b>
      <select name="category">
        <option value="-1">All Categories</option>
        <?php foreach($categories as $category): ?>
          <?php if ($category->id == $search_category): ?>
            <option selected="selected" value="<?=$category->id?>"><?=$category->name?></option>
          <?php else: ?>
            <option value="<?=$category->id?>"><?=$category->name?></option>
          <?php endif ?>
        <?php endforeach ?>
      </select>
      <b>Sort by:</b>
      <select name="order_by">
        <option value="alphabetical">Alphabetical</option>
        <?php if ($order_by === "new"): ?>
          <option value="new" selected="selected">Recently Added</option>
        <?php else: ?>
          <option value="new">Recently Added</option>
        <?php endif ?>
      </select>
      <button type="submit" class="btn btn-primary">Search</button>
  </form>
  </div>

  <!-- Projects -->
  <div class="row text-center">
    <p id="count"><i><?=count($projects)?> projects match your search</i></p>

    <?php if (!is_null($projects)): ?>
      <?php foreach ($projects as $project): ?>
      </div>
      <div class="row text-center project">
        <hr>
      <div class="col-md-4 project-image">
        <a href="https://bclearningnetwork.com/LOR/projects/<?=$project->id?>.pdf" target="_blank">
          <img src="https://bclearningnetwork.com/LOR/projects/<?=$project->id?>.png" width="200" height="150" />
        </a>

      </div>
      <div class="col-md-8 project-about text-left">
        <a href="https://bclearningnetwork.com/LOR/projects/<?=$project->id?>.pdf" target="_blank"><h3><?=$project->description?></h3></a>
        <p><i>Topics: </i><?=$project->topics?></p>
      </div>
    <?php endforeach ?>
    <?php endif ?>
    <hr>
</div>


<?php
echo $OUTPUT->footer();
?>
