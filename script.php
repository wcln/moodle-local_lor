<?php
error_reporting(-1);
require_once(__DIR__ . '/../../config.php'); // standard config file


$row = 1;
if (($handle = fopen("contributors.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    $contributors = [];
    for ($c=1; $c < $num; $c++) {
      if ($data[$c] !== "") {
        $contributors[] = $data[$c];
      }
    }

    echo $data[0] . "   --  ";
    var_dump($contributors);
    addContributors($data[0], $contributors);
  }
  fclose($handle);
}


function addContributors($content_id, $contributors) {
  global $DB;

  echo "inserting into db...";

  foreach ($contributors as $contributor) {

    echo "\nsearching for contributor '$contributor'";
    // check if contributor exists already, if not then insert
    $existing_record = $DB->get_record_sql('SELECT id FROM {lor_contributor} WHERE name = ?', array($contributor));
    echo "here";
    if ($existing_record) {
      echo "contributor already exists\n";
      $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($content_id, $existing_record->id));
    } else {
      echo "inserting new contributor\n";
      $record = new stdClass();
      $record->name = $contributor;
      $id = $DB->insert_record('lor_contributor', $record);
      $DB->execute('INSERT INTO {lor_content_contributors}(content, contributor) VALUES (?,?)', array($content_id, $id));
    }

  }

  echo "done.";
}

?>
