<?php

function local_lor_get_content($type, $platform, $categories, $grades, $order_by = "new", $keywords) {
  global $DB;

  $tables = "{lor_content}";
  $where_clause = '1=1';
  $params = array();

  // order by
  if ($order_by === "alphabetical") {
    $order_by = ' ORDER BY title ASC';
  } else if ($order_by === "new") {
    $order_by = ' ORDER BY date_created DESC';
  }

  // categories
  if(!is_null($categories)) {
    $tables .= ", {lor_content_categories}, {lor_category}";
    $where_clause .= " AND {lor_content}.id = {lor_content_categories}.content
                        AND {lor_content_categories}.category={lor_category}.id AND (";
    foreach ($categories as $cat) {
      $where_clause .= "{lor_category}.id = ? OR ";
      $params[] = $cat;
    }

    $where_clause = substr($where_clause, 0, -4) . ")";
  }

  // grades
  if(!is_null($grades)) {
    $tables .= ", {lor_content_grades}";
    $where_clause .= " AND {lor_content}.id = {lor_content_grades}.content AND (";
    foreach ($grades as $grade) {
      $where_clause .= "{lor_content_grades}.grade = ? OR ";
      $params[] = $grade;
    }

    $where_clause = substr($where_clause, 0, -4) . ")";
  }

  // type
  if(!is_null($type) && $type != -1) {
    $where_clause .= " AND {lor_content}.type = ?";
    $params[] = $type;
  }

  // platform
  if(!is_null($type) && $type == 1 && !is_null($platform)) {
    $where_clause .= ' AND {lor_content}.platform = ?';
    $params[] = $platform;
  }

  // keywords
  if (!is_null($keywords) && $keywords !== "") {
    $tables .= ", {lor_content_keywords}";
    // un comment when there are actually keywords in the database
    // $where_clause .= ' AND {lor_content_keywords}.content = {lor_content}.id AND (keyword LIKE ? OR title LIKE ?)';
    $where_clause .= ' AND title LIKE ?';
    $params[] = "%$keywords%";
    // un comment when there are actrually keywords in the database
    // $params[] = "%$keywords%";
  }

  // assemble query string
  $sql = "SELECT DISTINCT {lor_content}.id, type, title, image, link, date_created
          FROM $tables
          WHERE $where_clause $order_by";


  $content = $DB->get_records_sql($sql, $params);

  return $content;
}

function local_lor_get_content_from_id($id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_content}.id, type, {lor_type}.name, title, image, link, platform, date_created
          FROM {lor_content}, {lor_type}
          WHERE {lor_content}.id=? AND {lor_content}.type = {lor_type}.id";
  $item = $DB->get_record_sql($sql, array($id));
  return $item;
}

function local_lor_get_keywords_string_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT keyword FROM {lor_content_keywords} WHERE content = ?";

  $keywords = $DB->get_records_sql($sql, array($content_id));

  $keywords_str = "";
  foreach ($keywords as $keyword) {
    $keywords_str .= "$keyword->keyword, ";
  }

  if (strlen($keywords_str) > 1) {
    $keywords_str = substr($keywords_str, 0, -2);
  }

  return $keywords_str;
}

function local_lor_get_categories_string_for_item($content_id) {
  global $DB;

  $sql = "SELECT DISTINCT {lor_category}.name
          FROM {lor_content}, {lor_content_categories}, {lor_category}
          WHERE {lor_content}.id = {lor_content_categories}.content
          AND {lor_content_categories}.category = {lor_category}.id
          AND {lor_content}.id = ?";

  $categories = $DB->get_records_sql($sql, array($content_id));

  $categories_str = "";
  foreach ($categories as $category) {
    $categories_str .= "$category->name, ";
  }

  if (strlen($categories_str) > 1) {
    $categories_str = substr($categories_str, 0, -2);
  }

  return $categories_str;
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

function local_lor_add_project($title, $categories, $topics, $contributors, $grades, &$form) {
  global $DB;
  global $CFG;

  date_default_timezone_set('America/Los_Angeles'); // PST

  $form->save_file('word', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('word'));
  $form->save_file('pdf', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('pdf'));
  $form->save_file('icon', $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('icon'));


  $pid = str_replace(".pdf", "", $form->get_new_filename('pdf'));

  // insert into content table
  $record = new stdClass();
  $record->type = 2;
  $record->title = $title;
  $record->image = $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('icon');
  $record->link = $CFG->dirroot . '/LOR/projects/' . $form->get_new_filename('pdf');
  $record->date_created = date("Ymd");
  $pid = $DB->insert_record('lor_content', $record);

  // insert into categories table
  $categories = array_filter($categories);
  foreach ($categories as $category) {
    $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($pid, (int)$category));
  }

  // insert into grades table
  $grades = array_filter($grades);
  foreach ($grades as $grade) {
    $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($pid, (int)$grade));
  }

  // insert into lor_keyword table and lor_content_keywords table
  $keywords = explode(',', $topics);
  foreach ($keywords as $word) {

    // check if keyword exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name=?', array($word));
    if(sizeof($existing_record) > 0) {
      $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($pid, $word));
    } else {
      $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
      $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($pid, $word));
    }

  }

  // insert into lor_contributor and lor_content_contributors
  $contributors = explode(',', $contributors);
  foreach ($contributors as $contributor) {

    // check if contributor exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
    if(sizeof($existing_record) > 0) {
      $cid = $existing_record->id;
    } else {
      $cid = $DB->execute('INSERT INTO {lor_contributor}(name) VALUES (?)', array($contributor));
    }


    $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($pid, $cid));
  }

  return $pid;
}

function local_lor_add_game($title, $categories, $topics, $contributors, $grades, $link, $image, $platform) {
  global $DB;
  global $CFG;

  date_default_timezone_set('America/Los_Angeles'); // PST


  // insert into content table
  $record = new stdClass();
  $record->type = 1;
  $record->title = $title;
  $record->image = $image;
  $record->link = $link;
  $record->date_created = date("Ymd");
  $record->platform = $platform;
  $pid = $DB->insert_record('lor_content', $record);

  // insert into categories table
  $categories = array_filter($categories);
  foreach ($categories as $category) {
    $DB->execute('INSERT INTO {lor_content_categories}(content, category) VALUES (?,?)', array($pid, (int)$category));
  }

  // insert into grades table
  $grades = array_filter($grades);
  foreach ($grades as $grade) {
    $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($pid, (int)$grade));
  }

  // insert into lor_keyword table and lor_content_keywords table
  $keywords = explode(',', $topics);
  foreach ($keywords as $word) {

    // check if keyword exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name=?', array($word));
    if(sizeof($existing_record) > 0) {
      $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($pid, $word));
    } else {
      $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
      $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($pid, $word));
    }

  }

  // insert into lor_contributor and lor_content_contributors
  $contributors = explode(',', $contributors);
  foreach ($contributors as $contributor) {

    // check if contributor exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name=?', array($contributor));
    var_dump($existing_record);
    if($existing_record) {

      $cid = $existing_record->id;
    } else {
      $cid = $DB->execute('INSERT INTO {lor_contributor}(name) VALUES (?)', array($contributor));
    }


    $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($pid, $cid));
  }

  return $pid;
}
