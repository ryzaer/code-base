<?php
require_once('autobase.php');
/* php 5++
 * example post curl pngcrush
 */
$link = "http://localhost/__repository/code-base/server-secret.php";  
$head = (object)[
    // ignore if code not 200
    'code' => 200,
    // make header xhr server method as put
    'http' => ['X-HTTP-Method-Override: PUT']
];
$data = [
    'name' => 'My latest single', 
    'description' => 'Check out my newest song',
    'binary' => realpath('.')."/assets/sodium/data.bin", 
    'picture' => realpath('.')."/assets/images/arini.jpg", 
];
$result = \__fn::get_site($link,$head,$data);
header("Content-Type:text/json");
print $result;