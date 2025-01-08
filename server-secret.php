<?php
require_once('autobase.php'); 
header("Content-Type:text/json");
print json_encode([
 "POST" => $_POST,
 "GET" => $_GET,
 "FILES" => $_FILES 
],JSON_PRETTY_PRINT);