<?php

function local_lor_get_content($type, $platform, $categories, $grades, $order_by = "new", $keywords) {
  global $DB;

  if ($order_by === "alphabetical") {
    $order_by = ' ORDER BY title ASC';
  } else if ($order_by === "new") {
    $order_by = ' ORDER BY date_created DESC';
  }

  $sql = 'SELECT {lor_content}.id, type, title, image, link, date_created
          FROM {lor_content}, {lor_platform}, {lor_content_grades}, {lor_type}, {lor_content_categories}
          WHERE {lor_content}.platform = {lor_platform}.id
            AND {lor_content_grades}.content = {lor_content}.id
            AND {lor_type}.id = {lor_content}.type
            AND {lor_content_categories}.content = {lor_content}.id';

  $params = array();

  if(!is_null($type)) {
    $sql .= ' AND {lor_type}.id = ?';
    $params[] = $type;
  }

  if(!is_null($platform)) {
    $sql .= ' AND {lor_platform}.id = ?';
    $params[] = $platform;
  }

  // if(!is_null($categories)) {
  //   $sql .= ' AND {lor_type}.id = ?';
  //   $params[] = $type;
  // }

  $content = $DB->get_records_sql($sql, $params);
  return $content;
}

function local_lor_get_categories() {
  global $DB;

  $categories = $DB->get_records_sql('SELECT id, name FROM {lor_category}');
  return $categories;
}

function local_lor_get_platforms() {
  global $DB;

  $platforms = $DB->get_records_sql('SELECT id, name FROM {lor_platform}');
  return $platforms;
}

function local_lor_get_types() {
  global $DB;

  $types = $DB->get_records_sql('SELECT id, name FROM {lor_type}');
  return $types;
}

function local_lor_get_grades() {
  global $DB;

  $grades = $DB->get_records_sql('SELECT grade FROM {lor_grade}');
  return $grades;
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
