<?php
/**
 * Run as sudo
 */


$MAMP_ROOT = '/Applications/MAMP/';
$DOC_ROOT = '/Users/markwong/workspace/';
$HOSTS_FILE = '/private/etc/hosts';
$VHOSTS_TEMPLATE = 'vhost.tpl.txt';
//$HOSTS_FILE = 'hosts';
$settings = array();
set_setting($settings, 'domain');
set_setting($settings, 'doc_root');
set_setting($settings, 'email');
set_setting($settings, 'parse_html');
set_setting($settings, 'utf8');

//var_dump($settings);
//echo $line;
// Add hosts file entry
/*
$host_entry = "127.0.0.1\t" . $settings['domain'];
$res = file_put_contents($HOSTS_FILE, "$host_entry\n", FILE_APPEND);

if (!$res) {
  echo "Couldn't write to hosts file $HOSTS_FILE\n";
  echo "Try running script using sudo\n";
  exit;
}
*/


// copy the vhosts
$vhost = file_get_contents($VHOSTS_TEMPLATE);

$vhost = str_replace('[EMAIL]', $settings['email'], $vhost);
$vhost = str_replace('[DOMAIN]', $settings['domain'], $vhost);
$vhost = str_replace('[DOCUMENT_ROOT]', $settings['doc_root'], $vhost);

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
echo $vhost;
$filename = $settings['domain'] . '.conf';
file_put_contents($MAMP_ROOT . '/conf/apache/extra/vhost/' . $filename, $vhost);
file_put_contents($filename, $vhost);


//
function set_setting(&$setting, $fieldname) {
  echo "Enter $fieldname: ";
  $setting[$fieldname] = trim(fgets(STDIN)); // reads one line from STDIN
}