<?php
namespace lib;

function db(){
  $file = file_get_contents(__DIR__."/config.json");
  $json = json_decode($file, true);
  $db = false;
  $db = new \PDO('mysql:host='.$json['db']['host'].';dbname='.$json['db']['database'].';charset=utf8;', $json['db']['username'], $json['db']['password']);
  $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  return $db;
}

function sendHeaders(){
  header('X-Powered-By: Syndesi´s hamsters');
  header('Access-Control-Allow-Origin: *'); 
  header("Access-Control-Allow-Credentials: true");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('Access-Control-Max-Age: 1000');
  header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
  header('Accept: application/json, application/xml, text/xml, application/x-yaml, text/yaml, application/x-www-form-urlencoded, multipart/form-data');
  header('Content-Type: application/json; charset=utf-8');
}

?>