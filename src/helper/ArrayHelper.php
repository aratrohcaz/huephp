<?php

namespace Helper;

class ArrayHelper
{

  /**
   * Based off of the code from igorw get_in function
   *
   * @param array $haystack
   * @param array $token_path
   * @param null  $default
   *
   * @return array|mixed|null
   */
  function getPathKey($token_path = [], $haystack = [], $default = null)
  {
    if (!$token_path) {
      return $haystack;
    }

    // This is a micro-optimization, it is fast for non-nested keys, but fails for null values
    if (count($token_path) === 1 && isset($haystack[$token_path[0]])) {
      return $haystack[$token_path[0]];
    }

    $current = $haystack;
    foreach ($token_path as $token) {
      if (!is_array($current) || !array_key_exists($token, $current)) {
        return $default;
      }
      $current = $current[$token];
    }

    return $current;
  }

  /**
   * @param array|string $token_path
   * @param mixed        $value
   * @param array        $existing_data
   * @param string       $key_text_delimiter
   *
   * @return array
   */
  public static function setPathKey($token_path, $value, $existing_data = [], $key_text_delimiter = '.')
  {
    if (!is_array($token_path)) {
      $token_path = explode($key_text_delimiter, $token_path);
    }

    $token_path = array_reverse($token_path);
    $container  = [];
    $prev_token = null;
    foreach ($token_path as $token) {
      $container[$token] = $container;
      if (!$prev_token) {
        $container[$token] = $value;
      }
      if ($prev_token) {
        unset($container[$prev_token]);
      }
      $prev_token = $token;
    }

    return array_merge_recursive($existing_data, $container);
  }
}
