<?php
error_reporting(-1);
require_once(__DIR__ . '/../../config.php'); // standard config file

$grades_arr = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

$row = 1;
if (($handle = fopen("grades.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
    $grades = [];
    for ($c=2; $c < $num; $c++) {
      $grades[] = $data[$c];
    }

    echo $data[0] . "   --  ";
    var_dump($grades);
    addGrades($data[0], $grades);
  }
  fclose($handle);
}


function addGrades($content_id, $grades) {
  global $DB;
  global $grades_arr;

  echo "inserting into db...";

  for ($i = 0; $i < sizeof($grades); $i++) {

    if ($grades[$i] !== "") {
      $DB->execute('INSERT INTO {lor_content_grades}(content, grade) VALUES (?,?)', array($content_id, $grades_arr[$i]));
    }

  }

  echo "done.";
}

?>
