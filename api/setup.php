<?php

require_once(__DIR__.'/../lib/setup.php');


class setup{

  public function __construct($r){
    $this->r = $r;
    $this->setup = new \lib\setup();
    switch($this->r->method){
      case 'setup':
        $this->setup();
        break;
      case 'status':
        $this->getStatus();
        break;
      default:
        $this->r->abort(\lib\request::INVALID_REQUEST, 'No method specified');
    }
  }

  public function setup(){
    $this->r->finish('setup returned - demo');
  }

  public function getStatus(){
    $this->r->abort(\lib\request::REQUEST_DENIED, 'User could not be created.');
  }
}

?>