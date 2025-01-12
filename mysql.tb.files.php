<?php
include_once 'autobase.php';

$db = \db\mysql::open('root','123');
$rest = $db->name('dbserver_skck')->table('base_skck_data')->select(null,null,5);
var_dump($rest);