<?php
include_once 'lib.php';
set_time_limit('15000');

\lib\sendHeaders();
$a = new analyze();
//$res = $a->lessSteps($a->getFuelConsumption(), 10);
//$res = $a->toChartist($a->lessSteps($a->getFuelConsumption(), 100));
//$res = $a->getFuelConsumption();


echo(json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

class analyze{

  public function __construct(){
    $this->db = \lib\db();
  }

  /**
   * returns an array with the track_id as index and the number of uses as value
   * @return array see description
   */
  public function getTracks(){
    $pdo = $this->db->prepare('SELECT `track_id` FROM `race` WHERE `id` BETWEEN :s AND :e');
    $running = true;
    $i = 0;
    $gap = 100;
    $tracks = [];
    while($running){
      $pdo->execute(['s' => $i*$gap, 'e' => ($i+1)*$gap]);
      $res = $pdo->fetchAll(PDO::FETCH_ASSOC);
      if($res){
        foreach($res as $id => $race){
          if(array_key_exists($race['track_id'], $tracks)){
            $tracks[$race['track_id']]++;
          } else {
            $tracks[$race['track_id']] = 1;
          }
        }
      } else {
        $running = false;
      }
      $i++;
    }
    return $tracks;
  }

  public function getMoney(){
    $pdo = $this->db->prepare('SELECT `money` FROM `race` WHERE `id` BETWEEN :s AND :e');
    $running = true;
    $i = 0;
    $gap = 100;
    $money = [];
    while($running){
      $pdo->execute(['s' => $i*$gap, 'e' => ($i+1)*$gap]);
      $res = $pdo->fetchAll(PDO::FETCH_ASSOC);
      if($res){
        foreach($res as $id => $race){
          if(array_key_exists($race['money'], $money)){
            $money[$race['money']]++;
          } else {
            $money[$race['money']] = 1;
          }
        }
      } else {
        $running = false;
      }
      $i++;
    }
    return $money;
  }

  public function getFuelConsumption(){
    $pdo = $this->db->prepare('SELECT `fuel_consumption` FROM `race` WHERE `id` BETWEEN :s AND :e');
    $running = true;
    $i = 0;
    $gap = 100;
    $fuel_consumption = [];
    while($running){
      $pdo->execute(['s' => $i*$gap, 'e' => ($i+1)*$gap]);
      $res = $pdo->fetchAll(PDO::FETCH_ASSOC);
      if($res){
        foreach($res as $id => $race){
          $key = number_format($race['fuel_consumption'], 4, '.', '');
          if(array_key_exists($key, $fuel_consumption)){
            $fuel_consumption[$key]++;
          } else {
            $fuel_consumption[$key] = 1;
          }
        }
      } else {
        $running = false;
      }
      $i++;
    }
    ksort($fuel_consumption);
    return $fuel_consumption;
  }

  public function lessSteps($data, $steps){
    ksort($data);
    $min = key(array_slice($data, 0, 1, TRUE));
    $max = key(array_slice($data, -1, 1, TRUE));
    $dif = $max - $min;
    $step = $dif / $steps;
    $res = [];
    foreach($data as $i => $v){
      $key = strval(floor(($i - $min - 0.00001) / $step));
      if($key < 0){
        $key = 0;
      }
      if(array_key_exists($key, $res)){
        $res[$key] += $v;
      } else {
        $res[$key] = $v;
      }
    }
    $res2 = [];
    foreach($res as $i => $v){
      $key = number_format($i*$step, 4, '.', '');  //.' - '.number_format(($i+1)*$step, 4, '.', '')
      $res2[$key] = $v;
    }
    return $res2;
  }

  public function toChartist($data){
    $labels = [];
    $serie = [];
    foreach($data as $label => $point){
      $labels[] = $label;
      $serie[] = $point;
    }
    $data = [
      "labels" => $labels,
      "series" => [$serie]
    ];
    return $data;
  }

}

?>