<?php
$handle = fopen('assets/tpsa.csv', 'r');
$data = [];
while (($row = fgetcsv($handle)) !== false) {
    foreach ($row as $field) {
        // print "$field<br>";
        $sprts = [];
        foreach (explode(";",$field) as $value) {
            $sprts[] = trim(preg_replace('/\|/',',',utf8_decode($value)));
        }
        $data[] = $sprts;
        // print implode(" -> ",$sprts)."<br>";
        
    }
}
/**
 * PREPARE DATABASE
 */
$dbhost ='localhost';
$dbuser ='root';
$dbpass ='123';
$dbdsn  = "mysql:host={$dbhost}";
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
$sql = "INSERT INTO db_ops.tps ($imk) VALUES (:$imv)";
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
fclose($handle);