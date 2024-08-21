<?php
// Read entire file into string 
$handle = file_get_contents('assets/tpsa.xml');
  
// Convert xml string into an object 
$new = simplexml_load_string($handle); 
// Convert into json 

$data=[]; 
foreach ($new->Worksheet->Table->Row as $key => $value) {
    $rows=[];
    foreach ($value as $var => $val) {
        $rows[]= (string) $val->Data;
    }
    $data[] = $rows;
}

/**
 * PREPARE DATABASE
 */
$dbhost ='localhost';
$dbuser ='root';
$dbpass ='123';
$dbdsn  = "mysql:host={$dbhost}";

$dbtb   = 'db_ops.tps_data';

try {
    $pdo = new PDO($dbdsn, $dbuser, $dbpass);
} catch (PDOException $e) {
  echo 'Connection failed: '.$e->getMessage();
}

$keys = $data[0];
/**
 * CONVERT KEYS
 */
$imk = implode(',',$keys);
$imv = implode(',:',$keys);
/** 
 * GENERATE SQL SINTAX & PREPARE DB
 */
$sql = "INSERT INTO $dbtb ($imk) VALUES (:$imv)";
$stmt= $pdo->prepare($sql);

echo $sql;
unset($data[0]);
$trans=[];

foreach ($data as $k=>$v) { 
    $fetch = [];
    foreach ($v as $l => $k) {
        $fetch[$keys[$l]] = is_numeric($k) ? abs($k) : $k;
    }
    /**
     * EXPORT TO DATABASE
     */
    $stmt->execute($fetch);
    $trans[]= $fetch;
} 

$data = json_encode($trans,JSON_PRETTY_PRINT);
// var_dump($data);
header("Content-Type:text/json");
print $data;