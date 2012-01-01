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

// remove the last line as it's not needed
unset($file_contents[count($file_contents)-1]);

// read each line and stuff into an array keyed on date
$results = array();
foreach ($file_contents as $line) {

  $line = explode(',', $line);
  // don't want the first column as text we don't need it
  array_shift($line);
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
