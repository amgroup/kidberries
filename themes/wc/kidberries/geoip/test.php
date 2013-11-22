<?php
require_once('php/ipgeobase.php');

$geo = new IPGeoBase();
$data = $geo->getRecord('188.134.33.234');

foreach ( $data as $key => $value ) {
  $data[ $key ] = iconv( "CP1251", "UTF-8", $data[ $key ] );
}

var_dump($data);

?>
