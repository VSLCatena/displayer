<?php 
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('./db_core.php');
$data = array();

// Grab all settings
$query = $pdo->query('SELECT * FROM settings');
$settings = array();
foreach($query->fetchAll(PDO::FETCH_ASSOC) as $key=>$value){
    #$settings[$value['setting']] = $value['value'];
    $settings[$key]['key'] = $value['setting'];
    $settings[$key]['value'] = $value['value'];
}
$data['settings'] = $settings;

// fetch all assets
$query = $pdo->query("SELECT * FROM assets");
// Save it to a separate array to easily link categories to it
$data['assets'] = $query->fetchAll(PDO::FETCH_ASSOC);

// fetch all logs
$query = $pdo->query("Select `timestamp`, `name` as `loglevel` , `ip`, `user`, `message` from `vslcatena_displayer`.`logs` left join `level` on `vslcatena_displayer`.`logs`.`level_id` = `level`.`id`");
$data['logs'] = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($data['assets'] as $key=>$asset){
	$data['assets'][$key]['is_enabled'] = $asset['is_enabled']  ? true : false;
	$data['assets'][$key]['is_processing'] = $asset['is_processing']  ? true : false;
	$data['assets'][$key]['nocache'] = $asset['nocache']  ? true : false;
}
echo json_encode($data,JSON_NUMERIC_CHECK);
