<?php
include_once 'autobase.php';
var_dump(__fn::mkdir_based_months('D:/.ssh/attachment_skck/'));
die();
$db = db\mysql::open('root','123');
// basic function class
$rest = $db->name('latihan')->table('db_user')->select();
var_dump($rest);

// basic string command
$db->exec("select * from latihan.db_user");
var_dump($db->fetch());