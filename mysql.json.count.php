<?php
include_once 'autobase.php';
$sql = db\mysql::open('root','123');
$cmd ="select count(*) as total from db_ops.tps_data where json_extract(vote,'$.pilgub')!='[]'";
$sql->exec($cmd);
$prm = $sql->fetch();
var_dump($prm);