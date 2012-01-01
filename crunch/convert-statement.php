<?php
/**
 * PHP script convert a santander csv statement into a format
 * to be imported into crunch.co.uk accounting system
 *
 * Usage:
 * php convert-statement.php statements/2011-10.csv > converted.csv
 */

$file = $argv[1];
$total = $argv[2] ? $argv[2] : '';

if (!file_exists($file)) {
  echo $file . ' does not exist' . "\n";
  return;
}

$file_contents = file_get_contents($file);
$file_contents = explode("\n", $file_contents);
array_pop($file_contents); // remove the last line

$results = array();
foreach ($file_contents as $line) {

  $line = explode(',', $line);
  array_shift($line); // Don't want first cell as rubbish
  $results[] = $line;
}

// sort into order we want
$results = array_reverse($results);

$headers = array(
  'Date',
  'Reference',
  'Amount',
  'Balance',
);

// output as csv
echo implode(',', $headers), "\n";

foreach ($results as $i => $cells) {
  // calculate the running totals
  if ($total) {
    if ($i) {
      $total += $cells[2];
    }
    $cells[] = $total;
  }
  echo implode(',', $cells), "\n";
}
