<?php
namespace lib;


function db(){
  $file = file_get_contents(__DIR__."/../config.json");
  $config = json_decode($file, true);
  $db = false;
  $db = new \PDO('mysql:host='.$config['db']['host'].';dbname='.$config['db']['database'].';charset=utf8;', $config['db']['username'], $config['db']['password']);
  $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  return $db;
}

?>