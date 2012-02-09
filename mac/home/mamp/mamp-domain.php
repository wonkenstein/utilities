<?php
/**
 * Run as sudo
 * PHP script as running MAMP which has php, should be able to be ported to
 * WAMP or XAMPP
 */
$MAMP_ROOT = '/Applications/MAMP';
$DOC_ROOT = '/Users/markwong/workspace';
$HOSTS_FILE = '/private/etc/hosts';
$VHOSTS_TEMPLATE = 'vhost.tpl.txt';
$USER = 'markwong';

$settings = array();
set_setting($settings, 'domain');
set_setting($settings, 'doc_root');
set_setting($settings, 'email');
set_setting($settings, 'parse_html');
set_setting($settings, 'utf8');


// Add hosts file entry
$host_entry = "127.0.0.1\t" . $settings['domain'];

$contents = file_get_contents($HOSTS_FILE);

// check if exists in our hosts file
if (strpos($contents, $host_entry) > -1) {
  echo "Host entry exists in " . $HOSTS_FILE . "\n";
}
else {
  $res = file_put_contents($HOSTS_FILE, "$host_entry\n", FILE_APPEND);

  if (!$res) {
    echo "Couldn't write to hosts file $HOSTS_FILE\n";
    echo "Try running script using sudo\n";
    exit;
  }
    else {
      echo "Added $host_entry to $HOSTS_FILE\n";
  }
}

// check to see if doc root exists
$filepath = $DOC_ROOT . '/' . $settings['doc_root'];
if (!file_exists($filepath))  {
  echo "Doc root does not exist! Create?";
  $create_dir = trim(fgets(STDIN));
  if (strtolower($create_dir) == 'yes') {
    mkdir($filepath);
    chown($filepath, $USER); // change owner to default user
    echo "Created directory $filepath\n";
  }
}


// copy the vhosts
$vhost = file_get_contents($VHOSTS_TEMPLATE);

$vhost = str_replace('[EMAIL]', $settings['email'], $vhost);
$vhost = str_replace('[DOMAIN]', $settings['domain'], $vhost);
$vhost = str_replace('[DOCUMENT_ROOT]', $DOC_ROOT . '/' .$settings['doc_root'], $vhost);

if (strtolower($settings['parse_html']) == 'yes') {
  $vhost = str_replace(
      '# AddHandler application/x-httpd-php .html',
      'AddHandler application/x-httpd-php .html',
      $vhost
      );
}
if (strtolower($settings['utf8']) == 'yes') {
  $vhost = str_replace(
      '# AddDefaultCharset utf-8',
      'AddDefaultCharset utf-8',
      $vhost
      );

}

$filename = $settings['domain'] . '.conf';
$filepath = $MAMP_ROOT . '/conf/apache/extra/vhost/' . $filename;

if (file_exists($filepath)) {
  echo "$filepath File Exists\n";
  echo "Overwrite?";
  $write = trim(fgets(STDIN));
  $write = (strtolower($write) == 'yes') ? true : false;
}
else {
  $write = true;
}

if ($write) {
  $res = file_put_contents($filepath, $vhost);

  if ($res) {
    echo "Wrote conf file " . $filepath . "\n";
    chown($filepath, $USER); // change owner to default user
  }
  else {
    echo "Could not write conf file " . $filepath . "\n";
  }
}


//
function set_setting(&$setting, $fieldname) {
  echo "Enter $fieldname: ";
  $setting[$fieldname] = trim(fgets(STDIN)); // reads one line from STDIN
}