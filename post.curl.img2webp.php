<?php
require_once('autobase.php');
/* php 5++
 * example post curl convert image to webp
 */
$link = "http://localhost/__repository/code-base/server.php";  
// $link = "http://api.resptk.org/server.php";  
// $meta = @getimagesize($file);
// $data = new CURLFile($file,$meta['mime'],pathinfo($file)['basename']);

$data = [
    'rule' =>'ffmpeg',
    'exec' =>'img2webp',
    'file' => realpath('.')."/assets/images/arini.jpg"
];
$exec = \__fn::get_site($link,$data);
if($exec){
    echo "<img src=\"data:image/webp;base64,".base64_encode($exec)."\">";
}else{
    echo "<i>Something broken!</i>";
}