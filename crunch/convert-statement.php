<?php
/**
 * PHP script convert a santander csv statement into a format
 * to be imported into crunch.co.uk accounting system
 *
 * Usage:
 * php convert-statement.php statements/2011-10.csv > converted.csv
 */

$file = $argv[1];

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

  // change date to
  // according to docs http://www.php.net/manual/en/function.strtotime.php
  // "Dates in the m/d/y or d-m-y formats are disambiguated by looking at the
  // separator between the various components: if the separator is a slash (/),
  // then the American m/d/y is assumed; whereas if the separator is a dash (-)
  // or a dot (.), then the European d-m-y format is assumed.
  $date = str_replace('/', '-', $line[0]);
  $date = date('Y-m-d', strtotime($date));
  $results[$date] = $line;
}

// sort on the date
ksort($results);

$headers = array(
  'Date',
  'Reference',
  'Amount',
  'Balance',
);

// output as csv
echo implode(',', $headers), "\n";

foreach ($results as $k => $v) {
  echo implode(',', $v), "\n";
}
