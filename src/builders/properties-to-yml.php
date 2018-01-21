#!/usr/bin/php
<?php
$root = dirname(__DIR__, 2);
require_once $root . DIRECTORY_SEPARATOR . 'autoload.php';

$filename = 'build.properties';

$path = $root . DIRECTORY_SEPARATOR . $filename;

if (!is_readable($path)) {
  throw new Exception('Properties file is unreadble (' . $path . ')');
}

$configuration = file($path, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
print_r($configuration); // original file contents

$configuration = array_filter(
  $configuration, function ($value) {
  if (strpos($value, '#', 0) === false) {
    return true;
  }

  return false;
});
// Cleaned config

$configuration = array_values($configuration); // reset the numbers - array filter maintains keys
print_r($configuration);
$nested_config = [];

foreach ($configuration as $config_line) {
  $split_pos = strpos($config_line, '=');
  if ($split_pos === 0) {
    echo 'Error with line \'' . $config_line . '\'';
  }

  $key_path_dot = trim(substr($config_line, 0, ($split_pos)));
  $value        = trim(substr($config_line, $split_pos + 1)); // to be on the other side of the splitter

  $nested_config = Helper\ArrayHelper::setPathKey($key_path_dot, $value, $nested_config);

}
print_r($nested_config);

//$yw = new Symfony\Component\Yaml\Yaml();
//$yw::dump($nested_config, 2, 2, Symfony\Component\Yaml\Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
