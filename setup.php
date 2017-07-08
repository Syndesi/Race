<?php
include_once 'lib.php';

sendHeaders();
$db = getDB();
createTables($db);
$res = parseFile($db);



//$res = parseFile();



echo(json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));



function parseFile($db){
  $path = 'races.csv';
  $data = file_get_contents($path);
  $lines = explode("\n", $data);
  $header = explode(';', str_replace("\r", '', array_shift($lines)));
  $entrys = 0;
  $pdo = $db->prepare('INSERT INTO `race` (`id`, `race_created`, `race_driven`, `track_id`, `challenger`, `opponent`, `money`, `fuel_consumption`, `winner`, `status_id`, `forecast_id`, `weather_id`) VALUES (:id, :race_created, :race_driven, :track_id, :challenger, :opponent, :money, :fuel_consumption, :winner, :status_id, :forecast_id, :weather_id)');
  foreach($lines as $i => $line){
    if($line){
      $entrys++;
      // creates an array of a single line from the csv-file and removes carriage returns ("\r")
      $entry = array_combine($header, preg_split('/(;)(?=(?:[^"]|"[^"]*")*$)/', str_replace("\r", '', $line)));
      if(array_key_exists('forecast', $entry)){
        // unserializes the forecast-string, which quotes were reduced by one ('""' -> '"', '"' -> '')
        $entry['forecast'] = unserialize(preg_replace('/"(?!")/', '', $entry['forecast']));
      }
      // insert the entry into the db
    }
  }
  return $entrys;
}

function createTables($db){
  $sqlRace = 'CREATE TABLE IF NOT EXISTS `race` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `race_created` DATETIME NOT NULL, `race_driven` DATETIME NOT NULL, `track_id` INT UNSIGNED NOT NULL, `challenger` INT UNSIGNED NOT NULL, `opponent` INT UNSIGNED NOT NULL, `money` INT NOT NULL, `fuel_consumption` FLOAT NOT NULL, `winner` INT UNSIGNED NOT NULL, `status_id` INT UNSIGNED NOT NULL, `forecast_id` INT UNSIGNED NOT NULL, `weather_id` INT UNSIGNED NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
  $sqlForecast = 'CREATE TABLE IF NOT EXISTS `forecast` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `sunny` INT NOT NULL, `rainy` INT NOT NULL, `thundery` INT NOT NULL, `snowy` INT NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
  $sqlWeather = 'CREATE TABLE IF NOT EXISTS `weather` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `weather` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC))ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
  $sqlStatus = 'CREATE TABLE IF NOT EXISTS `status` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `status` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC))ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
  try {
    $db->exec($sqlRace);
    $db->exec($sqlForecast);
    $db->exec($sqlWeather);
    $db->exec($sqlStatus);
    return true;
  } catch(PDOException $e){
    print_r($e);
    return false;
  }
}



?>