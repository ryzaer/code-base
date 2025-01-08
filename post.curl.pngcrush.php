<?php
require_once('autobase.php');
/* php 5++
 * example post curl pngcrush
 */
$link = "http://localhost/__repository/code-base/server.php";  
// $link = "http://api.resptk.org/server.php";  
$data = [
    'rule' =>'ffmpeg',
    'exec' =>'img2webp',
    "file" => realpath('.')."/assets/example/files/pol.png"
];
$exec = \__fn::get_site($link,$data);
if($exec){
    echo "<img src=\"data:image/png;base64,".base64_encode($exec)."\">";
}else{
    echo "<i>Something broken!</i>";
}