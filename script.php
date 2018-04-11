<?php
error_reporting(-1);
require_once(__DIR__ . '/../../config.php'); // standard config file


$row = 1;
if (($handle = fopen("keywords.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    $keywords = [];
    for ($c=1; $c < $num; $c++) {
      if ($data[$c] !== "") {
        $keywords[] = $data[$c];
      }
    }

    echo $data[0] . "   --  ";
    var_dump($keywords);
    addKeywords($data[0], $keywords);
  }
  fclose($handle);
}


function addKeywords($content_id, $keywords) {
  global $DB;

  echo "inserting into db...";

  // insert into lor_keyword table and lor_content_keywords table
  foreach ($keywords as $word) {

    echo "\nsearching for keyword '$word'";
    // check if keyword exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT name FROM {lor_keyword} WHERE name = ?', array($word));
    echo "here";
    if ($existing_record) {
      echo "keyword already exists\n";
      $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($content_id, $word));
    } else {
      echo "inserting new keyword\n";
      $DB->execute('INSERT INTO {lor_keyword}(name) VALUES (?)', array($word));
      $DB->execute('INSERT INTO {lor_content_keywords}(content, keyword) VALUES (?,?)', array($content_id, $word));
    }

  }

  echo "done.";
}

?>
