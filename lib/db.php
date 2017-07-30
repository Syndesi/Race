<?php
namespace lib;

/**
 * returnes a new PDO-handle
 * @param  string $database false: use the default database, string: use this database
 * @return object            the PDO-handle
 */
function db($database = false){
  $file = file_get_contents(__DIR__."/../config.json");
  $c = json_decode($file, true);
  if(!$database){
    $database = $c['db']['database'];
  }
  $db = false;
  $db = new \PDO('mysql:host='.$c['db']['host'].';dbname='.$database.';charset=utf8;', $c['db']['username'], $c['db']['password']);
  $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  return $db;
}

?>