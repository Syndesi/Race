<?php
namespace lib;

/**
 * this class handles all addresses
 * @example include 'db.php';
 * @example $db = db();
 * @example $a = new address($db);
 * @example $country = $a->addCountry('us', 'United States of America');
 * @example $id = $a->setAddress('line 1', 'line 2', 'New York City', 'New York', '01234', $country);
 * @example echo(json_encode($a->getAddress($id)));
 */
class address{

  protected $db;

  public function __construct($db){
    $this->db = $db;
  }

  /**
   * creates all the tables which are neccessary to operate normally
   * @return boolean true: all tables could be generated, false: error occured
   */
  public function createTable(){
    $sqlAddress = 'CREATE TABLE IF NOT EXISTS `address` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `address_1` VARCHAR(100) NOT NULL, `address_2` VARCHAR(100) NOT NULL, `locality` VARCHAR(100) NOT NULL, `province` VARCHAR(100) NOT NULL, `postcode` VARCHAR(16) NOT NULL, `country` INT UNSIGNED NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    $sqlCountry = 'CREATE TABLE IF NOT EXISTS `country` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `abbreviation` VARCHAR(2) NOT NULL, `name` VARCHAR(45) NOT NULL, PRIMARY KEY (`id`), UNIQUE INDEX `id_UNIQUE` (`id` ASC)) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
    try {
      $this->db->exec($sqlAddress);
      $this->db->exec($sqlCountry);
      return true;
    } catch(PDOException $e) {
      return false;
    }
  }

  /**
   * insert a new address into the address-table
   * @param string $address1  the first line of the address
   * @param string $address2  the second line of the address
   * @param string $locality  the name of the city/village/place
   * @param string $province  the name of the province
   * @param string $postcode  the postcode of the locality, max. 16 chars
   * @param int $countryId the country's code
   */
  public function setAddress($address1, $address2, $locality, $province, $postcode, $countryId){
    $pdo = $this->db->prepare('INSERT INTO `address` (`id`, `address_1`, `address_2`, `locality`, `province`, `postcode`, `country`) VALUES (NULL, :address_1, :address_2, :locality, :province, :postcode, :country)');
    $data = [
      "address_1" => $address1,
      "address_2" => $address2,
      "locality"  => $locality,
      "province"  => $province,
      "postcode"  => $postcode,
      "country"   => $countryId
    ];
    $status = $pdo->execute($data);
    if($status){
      return $this->db->lastInsertId();
    }
    return false;
  }

  /**
   * returns the address at the specified id
   * @param  int $id the entry of the address
   * @return array     an array which contains all necessary informations or false, if an error occurred
   */
  public function getAddress($id){
    $pdo = $this->db->prepare('SELECT `address`.*, `country`.`abbreviation`, `country`.`name` FROM `address` LEFT JOIN `country` ON `address`.`country` = `country`.`id` WHERE `address`.`id` = :id LIMIT 1');
    $pdo->execute(['id' => $id]);
    return $pdo->fetch(\PDO::FETCH_ASSOC);
  }

  /**
   * Updates an address at the specified id
   * @param int $id           the id of the address which should be updated
   * @param string $address1  the first line of the address
   * @param string $address2  the second line of the address
   * @param string $locality  the name of the city/village/place
   * @param string $province  the name of the province
   * @param string $postcode  the postcode of the locality, max. 16 chars
   * @param int $countryId the country's code
   * @return boolean true: Update worked, false: an error occured (the 16th time?)
   */
  public function updateAddress($id, $address1, $address2, $locality, $province, $postcode, $countryId){
    $pdo = $this->db->prepare('UPDATE `address` SET `address_1` = :address_1, `address_2` = :address_2, `locality` = :locality, `province` = :province, `postcode` = :postcode, `country` = :country WHERE `id` = :id');
    $data = [
      "id"        => $id,
      "address_1" => $address1,
      "address_2" => $address2,
      "locality"  => $locality,
      "province"  => $province,
      "postcode"  => $postcode,
      "country"   => $countryId
    ];
    return $pdo->execute($data);
  }

  /**
   * deletes an address
   * @param  int $id the id of the address which should be deleted
   * @return boolean     true: deletion was successful, false: an error occured
   */
  public function deleteAddress($id){
    $pdo = $this->db->prepare('DELETE FROM `address` WHERE `id` = :id');
    return $pdo->execute(['id' => $id]);
  }

  /**
   * add an country
   * @param string $abbreviation the abbreviation, max. 2 chars
   * @param string $name         the name of the country, max. 45 chars
   * @return boolean true: insert was successful, false: error occurred
   */
  public function addCountry($abbreviation, $name){
    $pdo = $this->db->prepare('INSERT INTO `country` (`id`, `abbreviation`, `name`) VALUES (NULL, :abbreviation, :name)');
    $status = $pdo->execute(['abbreviation' => $abbreviation, 'name' => $name]);
    if($status){
      return $this->db->lastInsertId();
    }
    return false;
  }

  /**
   * returns an array of all available countrys
   * @return array an array of all countrys or false, if an error occured
   */
  public function getCountryList(){
    $pdo = $this->db->prepare('SELECT * FROM `country`');
    $pdo->execute();
    return $pdo->fetchAll(\PDO::FETCH_ASSOC);
  }

}

?>