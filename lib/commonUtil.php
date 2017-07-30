<?php

namespace lib;

function getConfig(){
  $path = realpath(dirname(__FILE__).'/../config.json');
  if(file_exists($path)){
    return json_decode(file_get_contents($path), true);
  }
  return false;
}

function setConfig($obj){
  $path = realpath(dirname(__FILE__).'/../config.json');
  return file_put_contents($path, json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

/**
 * returns if the script was started via command line or web
 * @return boolean True: started via console
 */
function isCommandLineInterface(){
  // returns the environment type
  return (php_sapi_name() === 'cli');
}

/**
 * returns if the os is windows - yep, I'm developing on windows o.O
 * @return boolean True: It's a Windows OS
 */
function isWindows(){
  if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
    return true;
  }
  return false;
}

/**
 * Returns if the operating system is 64 bit or not. Keep in mind that WAMPP & Co using 32 bit software under Windows 64.
 * @return boolean a magical number
 */
function is64System(){
  return PHP_INT_SIZE > 4;
}


/**
 * Create a custom int, dependent of the system's architecture (64 bit int's are twice as long as 32 bit int's)
 * @return int random, secure int
 */
function createCustomId(){
  $id = hexdec(bin2hex(random_bytes(PHP_INT_SIZE)));
  return abs($id >> 1);
}

/**
 * Base change from 10 (integer) to 64, an alphanumeric string
 * @param  int $num the number which should be converted
 * @return string      the resulting base64-string, limited by the system's integer limit
 */
function intToBase64($num) {
  $index = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
  $res = '';
  do {
    $res = $index[$num % 64] . $res;
    $num = intval($num / 64);
  } while ($num);
  return $res;
}

/**
 * Base change from 64 (alphanumeric string) to 10 (integer)
 * @param  string $base64 the string which should be converted to an integer
 * @return int   the resulting integer, limited by the system's integer limit
 */
function base64ToInt($base64){
  $index = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
  $num = 0;
  $array = str_split($base64);
  $letters = count($array);
  foreach($array as $i => $n){
    $num += pow(64, $letters - $i - 1) * strpos($index, $n);
  }
  return $num;
}

/**
 * returns the formated size in KB, MB, GB and so on
 * @source  https://stackoverflow.com/a/2510459/4417327 The original code, slightly modified
 * @param  [type]  $bytes     [description]
 * @param  integer $precision [description]
 * @return [type]             [description]
 */
function formatBytes($bytes, $precision = 2){
  $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);
  return round($bytes/pow(1024, $pow), $precision).' '.$units[$pow]; 
} 

?>