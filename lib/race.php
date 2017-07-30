<?php
namespace lib;
set_time_limit('15000');

class race{

  protected $db;

  public function __construct($db){
    $this->db = $db;
  }

  /**
   * This function will overwrite existing data in the application's tables
   * @return boolean true: setup complete, false: an error occurred
   */
  public function setup(){
    $this->clearTables();
    return $this->parseFile();
  }

  /**
   * This function will read the given csv-file and save it's content into the application's tables
   * @return boolean true: file imported, false: an error occurred
   */
  public function parseFile(){
    $path = __DIR__.'/../races.csv';
    $data = file_get_contents($path);
    $lines = explode("\n", $data);
    $header = explode(';', str_replace("\r", '', array_shift($lines)));
    $entrys = 0;
    $errors = [];
    foreach($lines as $i => $line){
      if($line){
        // creates an array of a single line from the csv-file and removes carriage   returns ("\r")
        $entry = array_combine($header, preg_split('/(;)(?=(?:[^"]|"[^"]*")*$)/',   str_replace("\r", '', $line)));
        if(array_key_exists('forecast', $entry)){
          // unserializes the forecast-string, which quotes were reduced by one ('""' ->  '"', '"' -> '')
          $entry['forecast'] = unserialize(preg_replace('/"(?!")/', '', $entry['forecast' ]));
        }
        $data = [
          'id' => $entry['id'],
          'race_created' => \DateTime::createFromFormat('d.m.Y', $entry['race_created'])->format('Y-m-d H:i:s'),
          'race_driven' => $this->getRaceDriven($entry['race_created'], $entry['race_driven'])->format('Y-m-d H:i:s'),
          'track_id'    => $entry['track_id'],
          'challenger'  => $entry['challenger'],
          'opponent'    => $entry['opponent'],
          'money'       => $entry['money'],
          'fuel_consumption' => $entry['fuel_consumption'],
          'winner'      => $entry['winner'],
          'status_id'      => $this->getStatusId($entry['status']),
          'forecast_id'    => $this->getForecastId($entry['forecast']),
          'weather_id'     => $this->getWeatherId($entry['weather'])
        ];
        if($this->insertRace($data)){
          $entrys++;
        } else {
          $errors[] = 'Row "'.$data['id'].'" couldn´t be inserted.';
        }
      }
    }
    if(count($errors) > 0){
      file_put_contents('log.txt', json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    return $entrys;
  }

  public function insertRace($data){
    $pdo = $this->db->prepare('INSERT INTO `race` (`id`, `race_created`, `race_driven`, `track_id`, `challenger`, `opponent`, `money`, `fuel_consumption`, `winner`, `status_id`, `forecast_id`, `weather_id`) VALUES (:id, :race_created, :race_driven, :track_id, :challenger, :opponent, :money, :fuel_consumption, :winner, :status_id, :forecast_id, :weather_id)');
    if($pdo->execute($data)){
      return $this->db->lastInsertId();
    }
    return false;
  }

  public function getRaceDriven($created, $driven){
    if($driven == '0000-00-00 00:00:00'){
      return \DateTime::createFromFormat('d.m.Y', $created);
    } else {
      return \DateTime::createFromFormat('d.m.Y H:i', $driven);
    }
  }

  /**
   * This function will return the id of an existing or newly created status-entry
   * @param  string $status the status's name, e.g. 'completed'
   * @return int          the id of the status-entry
   */
  public function getStatusId($status){
    $pdo = $this->db->prepare('SELECT * FROM `status` WHERE `status` = :status LIMIT 1');
    $pdo->execute(['status' => $status]);
    $res = $pdo->fetch(\PDO::FETCH_ASSOC);
    if($res){
      return $res['id'];
    } else {
      $pdo2 = $this->db->prepare('INSERT INTO `status` (`id`, `status`) VALUES (NULL, :status)');
      if($pdo2->execute(['status' => $status])){
        return $this->db->lastInsertId();
      }
    }
    return false;
  }

  /**
   * This function will return the id of an existing or newly created weather-entry
   * @param  string $weather the weather's name, e.g. 'thundery'
   * @return int          the id of the weather-entry
   */
  public function getWeatherId($weather){
    $pdo = $this->db->prepare('SELECT * FROM `weather` WHERE `weather` = :weather LIMIT 1');
    $pdo->execute(['weather' => $weather]);
    $res = $pdo->fetch(\PDO::FETCH_ASSOC);
    if($res){
      return $res['id'];
    } else {
      $pdo2 = $this->db->prepare('INSERT INTO `weather` (`id`, `weather`) VALUES (NULL, :weather)');
      if($pdo2->execute(['weather' => $weather])){
        return $this->db->lastInsertId();
      }
    }
    return false;
  }

  /**
   * This function will return the id of an existing or newly created forecast-entry
   * @param  int $sunny    the probability of sunny weather
   * @param  int $rainy    the probability of rainy weather
   * @param  int $thundery the probability of thundery weather
   * @param  int $snowy    the probability of snowy weather
   * @return int           the id of the forecast-entry
   */
  public function getForecastId($data){
    $pdo = $this->db->prepare('SELECT * FROM `forecast` WHERE `sunny` = :sunny AND `rainy` = :rainy AND `thundery` = :thundery AND `snowy` = :snowy LIMIT 1');
    $data = $this->distributeValues($data);
    $pdo->execute($data);
    $res = $pdo->fetch(\PDO::FETCH_ASSOC);
    if($res){
      return $res['id'];
    } else {
      $pdo2 = $this->db->prepare('INSERT INTO `forecast` (`id`, `sunny`, `rainy`, `thundery`, `snowy`) VALUES (NULL, :sunny, :rainy, :thundery, :snowy)');
      if($pdo2->execute($data)){
        return $this->db->lastInsertId();
      }
    }
    return false;
  }

  /**
   * If the sum of the array isn't the combinedValue, the array is recalculated so that the sum is compareable
   * @param  array $a [10, 20, 30, ..., n]
   * @return array    the corrected array
   */
  public function distributeValues($a, $combinedValue = 100){
    $sum = array_sum($a);
    if($sum != $combinedValue){
      foreach($a as $i => $v){
        $a[$i] = round(($a[$i] / $sum) * $combinedValue);
      }
    }
    return $a;
  }

  public function setDB(){
    $host = '';
    $database = '';
    $username = '';
    $password = '';
    if(new \PDO('mysql:host='.$host.';dbname='.$database.';charset=utf8;', $username, $password)){
      return true;
    } else {
      return false;
    }
  }

  /**
   * This function creates all needed tables for this application
   * @return boolean true: Tables were created, false: an error occurred
   */
  public function createTables(){
    $sqlRace = 'CREATE TABLE IF NOT EXISTS `race` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `race_created` DATETIME NOT NULL, `race_driven` DATETIME NOT NULL, `track_id` INT UNSIGNED NOT NULL, `challenger` INT UNSIGNED NOT NULL, `opponent` INT UNSIGNED NOT NULL, `money` INT NOT NULL, `fuel_consumption` FLOAT NOT NULL, `winner` INT UNSIGNED NOT NULL, `status_id` INT UNSIGNED NOT NULL, `forecast_id` INT UNSIGNED NOT NULL, `weather_id` INT UNSIGNED NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlForecast = 'CREATE TABLE IF NOT EXISTS `forecast` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `sunny` INT NOT NULL, `rainy` INT NOT NULL, `thundery` INT NOT NULL, `snowy` INT NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlWeather = 'CREATE TABLE IF NOT EXISTS `weather` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `weather` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC))ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlStatus = 'CREATE TABLE IF NOT EXISTS `status` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `status` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC))ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    try {
      $this->db->exec($sqlRace);
      $this->db->exec($sqlForecast);
      $this->db->exec($sqlWeather);
      $this->db->exec($sqlStatus);
      return true;
    } catch(PDOException $e){
      return false;
    }
  }

  /**
   * This function will truncate the used tables.
   * It's protected because it would be a disaster if this function would be called by hazard
   * @return boolean true: Tables were truncated, false: an error occurred
   */
  public function clearTables(){
    $sqlRace     = 'TRUNCATE TABLE `race`';
    $sqlForecast = 'TRUNCATE TABLE `forecast`';
    $sqlWeather  = 'TRUNCATE TABLE `weather`';
    $sqlStatus   = 'TRUNCATE TABLE `status`';
    try {
      $this->db->exec($sqlRace);
      $this->db->exec($sqlForecast);
      $this->db->exec($sqlWeather);
      $this->db->exec($sqlStatus);
      return true;
    } catch(PDOException $e){
      return false;
    }
  }

}


?>