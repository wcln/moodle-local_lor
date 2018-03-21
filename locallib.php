<?php




function local_projectspage_get_projects($category, $order_by = "alphabetical") {
  global $DB;

  if ($order_by === "alphabetical") {
    $order_by = ' ORDER BY description ASC';
  } else if ($order_by === "new") {
    $order_by = ' ORDER BY date_created DESC';
  }

  if (is_null($category)) {
    // get ALL projects
    $projects = $DB->get_records_sql('SELECT * FROM {lor_project}' . $order_by);
  } else {
    $projects = $DB->get_records_sql('SELECT * FROM {lor_project}, {lor_project_categories} WHERE {lor_project}.id = {lor_project_categories}.pid AND {lor_project_categories}.cid=?' . $order_by, array($category));
  }

  return $projects;
}

function local_projectspage_get_project_from_id($id) {
  global $DB;

  $project = $DB->get_record_sql('SELECT {lor_project}.id, date_created, topics, description, cid FROM {lor_project} WHERE {lor_project}.id=?', array($id));
  return $project;
}

function local_projectspage_get_all_project_categories() {
  global $DB;

  $categories = $DB->get_records_sql('SELECT id, name FROM {lor_category}');
  return $categories;
}

function local_projectspage_get_project_category_from_id($id) {
  global $DB;

  $category = $DB->get_record_sql('SELECT id, name FROM {lor_category} WHERE id=?', array($id));
  return $category;
}

function local_projectspage_add_project($description, $categories, $topics, $contributors, $grades, &$form) {
  global $DB;
  global $CFG;

  date_default_timezone_set('America/Los_Angeles'); // PST

  $form->save_file('word', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('word'));
  $form->save_file('pdf', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('pdf'));
  $form->save_file('icon', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('icon'));


  $pid = str_replace(".pdf", "", $form->get_new_filename('pdf'));

  // insert into project table
  $DB->execute('INSERT INTO {lor_project} VALUES (?,?,?,?,?)', array($pid, $description, $topics, date("Ymd"), $contributors));

  // insert into project_categories table
  $categories = array_filter($categories);
  foreach ($categories as $category) {
    $record = new stdClass();
    $record->pid = $pid;
    $record->cid = (int)$category;
    $DB->insert_record('project_categories', $record);
  }

  // insert into project grades table
  $grades = array_filter($grades);
  foreach ($grades as $grade) {
    $record = new stdClass();
    $record->pid = $pid;
    $record->grade = (int)$grade;
    $DB->insert_record('project_grades', $record);
  }

  return $pid;
}
