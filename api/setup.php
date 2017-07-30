<?php
require_once(__DIR__.'/../lib/commonUtil.php');
require_once(__DIR__.'/../lib/db.php');
require_once(__DIR__.'/../lib/race.php');

class setup{

  protected $db;

  public function __construct($r){
    $this->r = $r;
    $this->db = \lib\db();
    $this->race = new \lib\race($this->db);
    switch($this->r->method){
      case 'mysql':
        $this->mysql();
        break;
      case 'setup':
        $this->setup();
        break;
      case 'import':
        $this->import();
        break;
      case 'getProgress':
        $this->getProgress();
        break;
      default:
        $this->r->abort(\lib\request::INVALID_REQUEST, 'No valid method specified');
    }
  }

  public function import(){
    $this->race->createTables();
    $this->race->clearTables();
    $lines = $this->race->parseFile();
    $this->r->finish('imported lines: '.$lines);
  }

  public function getProgress(){
    $this->r->finish(['progress' => (rand(1, 99)/100), 'remaining' => (rand(10, 20).' min')]);
  }

  public function mysql(){
    $host = $this->r->getData('host', true);
    $database = $this->r->getData('database', true);
    $username = $this->r->getData('username', true);
    $password = $this->r->getData('password', true);
    try{
      $pdo = new \PDO('mysql:host='.$host.';dbname='.$database.';charset=utf8;', $username, $password);
    } catch(PDOException $e){
      $this->r->abort(\lib\request::REQUEST_DENIED, 'The given credentials are not working.');
    }
    $c = \lib\getConfig();
    $c['db'] = [
      'host'     => $host,
      'database' => $database,
      'username' => $username,
      'password' => $password
    ];
    \lib\setConfig($c);
    $this->r->finish('The given credentials are working and saved.');
  }

  public function setup(){
    $this->r->finish('Hello world :) setup');
  }
}

?>