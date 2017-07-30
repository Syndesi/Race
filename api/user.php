<?php

require_once(__DIR__.'/../lib/db.php');
require_once(__DIR__.'/../lib/user.php');
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;


class user{

  protected $db;

  public function __construct($r){
    $this->db = \lib\db();
    $this->r = $r;
    $this->user = new \lib\user($this->db);
    switch($this->r->method){
      case 'create':
        $this->createUser();
        break;
      case 'get':
        $this->getUser();
        break;
      case 'login':
        $this->login();
        break;
      case 'logout':
        $this->logout();
        break;
      default:
        $this->r->abort(\lib\request::INVALID_REQUEST, 'No valid method specified');
    }
  }

  /**
   * this function will start a new session for the user
   * @return [type] [description]
   */
  public function login(){
    if($this->r->session->get('user')){
      $this->r->finish("already logged in");
    }
    $data = [
      "email"    => $this->r->getData('email', true),
      "password" => $this->r->getData('password', true)
    ];
    // address_id, profile_image_id, interests_id
    $validator = v::key('email', v::stringType()->email()->length(1, 255))
                  ->key('password', v::stringType()->length(8, NULL));
    try {
      $validator->assert($data);
    } catch(NestedValidationException $e){
      $this->r->abort(\lib\request::INVALID_REQUEST, $e->getFullMessage());
    }
    $res = $this->user->compareUserPassword($data['email'], $data['password'], false);
    if($res){
      $user = $this->user->getUser($data['email'], false);
      if($user){
        $this->r->session->restart();
        $this->r->session->set('user', $user);
        $this->r->finish("successfully logged in");
      }
    }
    $this->r->abort(\lib\request::REQUEST_DENIED, 'either the email or the password is not correct');
  }

  /**
   * closes the user's current session
   * @return void nothing
   */
  public function logout(){
    if($this->r->session->get('user')){
      $this->r->session->destroy();
      $this->r->finish("successfully logged out");
    }
    $this->r->finish("already logged out");
  }

  /**
   * Create a new user with all necessary forms
   * @return void
   */
  public function createUser(){
    $data = [
      "forename"         => $this->r->getData('forename', true),
      "surname"          => $this->r->getData('surname', true),
      "nickname"         => $this->r->getData('nickname', true),
      "email"            => $this->r->getData('email', true),
      "number"           => $this->r->getData('number', true),
      "address_id"       => null,
      "birthday"         => $this->r->getData('birthday', true),
      "gender"           => $this->r->getData('gender', true),
      "description"      => $this->r->getData('description', true),
      "profile_image_id" => null,
      "interests_id"     => null,
      "inform_email"     => $this->r->getData('inform_email', true),
      "inform_mobile"    => $this->r->getData('inform_mobile', true),
      "payed"            => false,
      "approved"         => false,
      "password"         => $this->r->getData('password', true)
    ];
    // address_id, profile_image_id, interests_id
    $validator = v::key('forename', v::stringType()->length(1, 35))
                  ->key('surname', v::stringType()->length(1, 35))
                  ->key('nickname', v::stringType()->length(0, 35))
                  ->key('email', v::stringType()->email()->length(1, 255))
                  ->key('number', v::stringType()->phone()->length(1, 20))
                  ->key('birthday', v::stringType()->date('Y-m-d'))
                  ->key('gender', v::intVal())
                  ->key('description', v::stringType()->length(1, 65000))
                  ->key('inform_email', v::boolVal())
                  ->key('inform_mobile', v::boolVal())
                  ->key('password', v::stringType()->length(8, NULL));
    try {
      $validator->assert($data);
    } catch(NestedValidationException $e){
      $this->r->abort(\lib\request::INVALID_REQUEST, $e->getFullMessage());
    }
    $result = $this->user->createUser($data['forename'], $data['surname'], $data['nickname'], $data['email'], $data['number'], $data['address_id'], $data['birthday'], $data['gender'], $data['description'], $data['profile_image_id'], $data['interests_id'], $data['inform_email'], $data['inform_mobile'], $data['payed'], $data['approved'], $data['password']);
    if($result){
      $obj = [
        "message" => "User created.",
        "user-id" => $result
      ];
      $this->r->finish($obj);
    }
    $this->r->abort(\lib\request::REQUEST_DENIED, 'User could not be created.');
  }

  public function getUser(){
    $this->r->finish('user returned - demo');
  }

}

?>