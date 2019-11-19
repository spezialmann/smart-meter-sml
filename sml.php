<?php
require_once 'sml_parser.php';
require_once 'send_data.php';

/** Parameter Parsen */
$params = array();
foreach ($argv as $arg) {
  $e=explode("=",$arg);
  if(count($e)==2) {
      $params[$e[0]]=$e[1];
  }
  else {   
      $params[]=$e[0];
  }
}
$pathname = (isset($params['path'])) ? $params['path'] : 'data/';

/** Datenfiles einlesen */
if ($handle = opendir($pathname)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $files[]=$file;
        }
    }

    if(is_array($files)) {
        rsort($files);

        foreach($files as $file) {
          if(substr($file,0,9)=='serialin_') {
            $sml_parser = new SML_PARSER();
            $sml_parser->parse_sml_file($pathname.$file);
          }
          break; 
        }

        //Nur die letzten 10 Files aufheben
        $i = 0;
        foreach($files as $file) {          
          $i++;
          if($i>10) {
            if(is_file($pathname.$file)) {
              unlink($pathname.$file);
            }
          }
        }
    }
    closedir($handle);
}

//Daten senden
if(!empty($sml_parser->total_power_consumption_value)) {
  $send_data = new SEND_DATA();
  //$send_data->url = "http://localhost:8080/api/v1/smartmeter/data";

  $send_data->useParams($params);
  $send_data->total_power_consumption_value = $sml_parser->total_power_consumption_value;
  $send_data->current_power_value = $sml_parser->current_power_value;
  $send_data->postData();
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Smart Meter</title>
  </head>
  <body>
    <h1>Zählerstand: <?php echo number_format($sml_parser->total_power_consumption_value, 2, ',', '.'); ?> kWh</h1>
    <h2>Aktuelle Leistung: <?php echo number_format($sml_parser->current_power_value, 2, ',', ' '); ?> W</h2>
    <h5>Last update: <?php echo $sml_parser->last_update ?></h5>
  </body>
</html>