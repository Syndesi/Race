<?php
require_once(__DIR__.'/../lib/email.php');

class email{

  protected $db;

  public function __construct($r){
    $this->r = $r;
    $this->email = new \lib\email();
    switch($this->r->method){
      case 'send':
        $this->send();
        break;
      default:
        $this->r->abort(\lib\request::INVALID_REQUEST, 'No valid method specified');
    }
  }

  public function send(){
    $this->email->sendPlain('soerenklein98@gmail.com', 'Title', 'Body');
  }
}

?>