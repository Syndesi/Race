<?php
namespace lib;

class progress{

  protected $start;
  protected $steps = 160000;
  protected $now = 0;

  public function __construct($steps){
    $this->start = gmdate();
    $this->steps = $steps;
  }

  public function set($steps){
    $this->now = $steps;
  }

}


?>