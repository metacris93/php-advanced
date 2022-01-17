<?php
use Carbon\Carbon;

if (! function_exists('dd')) {
  /**
    * Print content variable
    *
    * @param  dynamic  key|key,default|data,expiration|null
    * @return void
    */
  function dd()
  {
      echo "<pre>";
      $args = func_get_args();
      var_dump($args);
      die;
  }
}
if (! function_exists('storage_path')) {
  /**
    * Get the path to the storage folder.
    *
    * @param  string  $path
    * @return string
    */
  function storage_path($path = '')
  {
      return dirname(__DIR__)
        .DIRECTORY_SEPARATOR
        .'storage'
        .DIRECTORY_SEPARATOR
        .'app'
        .DIRECTORY_SEPARATOR
        .'public'
        .($path ? DIRECTORY_SEPARATOR.$path : $path);
  }
}
if (! function_exists('guidv4'))
{
  /**
    * Generate a unique value
    *
    * @return string
    */
  function guidv4() {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = random_bytes(16);
    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }
}
if (! function_exists('get_last_substring'))
{
  /**
    * get the last string splitted by a separator
    *
    * @param string $separator
    * @param string $data
    * @return string
    */
  function get_last_substring($separator, $data)
  {
    if (!isset($data) && !isset($separator)) return '';
    if (empty($data) && empty($separator)) return '';
    $values = explode($separator, $data);
    return $values[count($values) - 1];
  }
}

if (! function_exists('get_current_datetime'))
{
  /**
    * get current date time by current timezone
    * @return string
    */
  function get_current_datetime()
  {
    return Carbon::now('America/Guayaquil')->format('Y-m-d H:i:s');
  }
}