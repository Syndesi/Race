<?php

$s = new setup();

class setup{

  $db = false;

  public function __construct($db){
    $this->db = $db;
    echo('hi');
  }

  public function createTables(){
    $sqlCsv = 'CREATE TABLE IF NOT EXISTS `csv` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `race_created` DATETIME NOT NULL, `race_driven` DATETIME NOT NULL, `track_id` INT UNSIGNED NOT NULL, `challenger` INT UNSIGNED NOT NULL, `opponent` INT UNSIGNED NOT NULL, `money` INT NOT NULL, `fuel_consumption` FLOAT NOT NULL, `winner` INT UNSIGNED NOT NULL, `status` VARCHAR(45) NOT NULL, `forecast` VARCHAR(255) NOT NULL, `weather` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlForecast = 'CREATE TABLE IF NOT EXISTS `forecast` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `sunny` INT NOT NULL, `rainy` INT NOT NULL, `thundery` INT NOT NULL, `snowy` INT NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlRace = 'CREATE TABLE IF NOT EXISTS `race` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `race_created` DATETIME NOT NULL, `race_driven` DATETIME NOT NULL, `track_id` INT UNSIGNED NOT NULL, `challenger` INT UNSIGNED NOT NULL, `opponent` INT UNSIGNED NOT NULL, `money` INT NOT NULL, `fuel_consumption` FLOAT NOT NULL, `winner` INT UNSIGNED NOT NULL, `status_id` INT UNSIGNED NOT NULL, `forecast_id` INT UNSIGNED NOT NULL, `weather_id` INT UNSIGNED NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlStatus = 'CREATE TABLE IF NOT EXISTS `status` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `status` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlWeather = 'CREATE TABLE IF NOT EXISTS `weather` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `weather` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    try {
      $this->db->exec($sqlCsv);
      $this->db->exec($sqlForecast);
      $this->db->exec($sqlRace);
      $this->db->exec($sqlStatus);
      $this->db->exec($sqlWeather);
      return true;
    } catch(PDOException $e) {
      return false;
    }
  }

}

?>