<?php
include_once('autoload.php');
$dom  = new parse\html();
header("Content-Type:text/plain");
$file = file_get_contents('assets/example.html');
echo $dom->convert($file);
