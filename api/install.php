<?php

require_once(__DIR__.'/../lib/db.php');
require_once(__DIR__.'/../lib/address.php');
require_once(__DIR__.'/../lib/language.php');
require_once(__DIR__.'/../lib/upload.php');
require_once(__DIR__.'/../lib/user.php');
require_once(__DIR__.'/../lib/shortifier.php');


class install{

  protected $db;

  public function __construct($r){
    $this->db = \lib\db();
    $this->r = $r;
    $this->user = new \lib\user($this->db);
    switch($this->r->method){
      case 'createTable':
        $this->createTable();
        break;
      default:
        $this->r->abort(\lib\request::INVALID_REQUEST, 'No valid method specified');
    }
  }

  public function createTable(){
    $address  = new \lib\address($this->db);
    $language = new \lib\language($this->db);
    $upload   = new \lib\upload($this->db);
    $user     = new \lib\user($this->db);
    $shortifier     = new \lib\shortifier($this->db);
    $data = [
      "address-lib" =>  $address->createTable(),
      "language-lib" => $language->createTable(),
      "upload-lib" =>   $upload->createTable(),
      "user-lib" =>     $user->createTable(),
      "shortifier-lib" =>     $shortifier->createTable()
    ];
    $this->r->finish($data);
  }

}

?>