<?php

require_once 'vendor/autoload.php';
require_once 'lib/request.php';

use Respect\Validation\Validator as v;



// This request-object handles all the client-server-communication and standartizes the data-flow
$r = new \lib\request();

// check if the required API exists and execute it
$apiName = $r->api;
if(!ctype_alnum($apiName)){
  $r->abort(\lib\request::INVALID_REQUEST, 'The API-name must be alphanumeric.');
}
$apiPath = 'api/'.$apiName.'.php';
if(file_exists($apiPath)){
  include_once($apiPath);
  // creates a new instance of the required API
  $a = new $apiName($r);
} else {
  $r->abort(\lib\request::INVALID_REQUEST, 'This API does not exist.');
}

exit;

?>