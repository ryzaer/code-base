<?php
require_once('autobase.php');
/* php 5++
 * example post curl pngcrush
 */
$file = realpath('.')."/assets/example/files/pol.png";
$link = "http://localhost/__repository/code-base/server.php";  
$link = "http://api.resptk.org/server.php";  
$meta = @getimagesize($file);
$data = new CURLFile($file,$meta['mime']);
$data = [
            'rule' =>'ffmpeg',
            'exec' =>'img2webp',
            "file" => $data
        ];
$exec = \__fn::get_site((object)['data'=> $data],$link);
if($exec){
    echo "<img src=\"data:image/png;base64,".base64_encode($exec)."\">";
}else{
    echo "<i>Something broken!</i>";
}