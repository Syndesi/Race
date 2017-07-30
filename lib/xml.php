<?php

/**
 * Converts arrays to XML, based on Ghost answer: http://stackoverflow.com/a/26964222
 * @param  array  $array The content to be processed
 * @param  boolean $xml   the xml instance, used internally
 * @return string         the resulting xml string
 */
function array2xml($array, $xml = false){
  if($xml === false){
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result />');
  }
  foreach($array as $key => $value){
    $key = str_replace(' ', '_', $key);
    if(is_numeric($key)){
      $key = 'entry';
    }
    if(is_array($value)){
      array2xml($value, $xml->addChild($key));
    } else {
      $xml->addChild($key, $value);
    }
  }
  return $xml->asXML();
}

/**
 * Converts XML to an associative array
 * @param  string $xml the XML-encoded data
 * @return array      the resulting array
 */
function xml2array($xml){
  if(!$xml){
    return false;
  }
  $xml = simplexml_load_string($xml);
  $json = json_encode($xml);
  return json_decode($json, true);
}

?>