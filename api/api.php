<?php

require_once '../vendor/autoload.php';
require_once '../lib/request.php';
require_once '../lib/lib.php';




$r = new \lib\request();

// just for testing
//$r->finish('testing');

$apiName = $r->api;
if(!ctype_alnum($apiName)){
  $r->abort(\lib\request::INVALID_REQUEST, 'The API-name must be alphanumeric.');
}
$apiPath = './'.$apiName.'.php';
if(file_exists($apiPath)){
  include_once($apiPath);
  $a = new $apiName($r);
} else {
  $r->abort(\lib\request::INVALID_REQUEST, 'This API does not exist.');
}

exit;

?>