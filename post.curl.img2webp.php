<?php
require_once('autobase.php');
/* php 5++
 * example post curl convert image to webp
 */
$file = realpath('.')."/assets/example/files/arini";
$link = "http://localhost/__classes/autoloader/server.php";  
// $link = "http://api.resptk.org/server.php";  
$meta = @getimagesize($file);
$data = new CURLFile($file,$meta['mime']);
$data = [
            '' =>'ffmpeg',
            'rule' =>'ffmpeg',
            'exec' =>'img2webp',
            "file" => $data
        ];
$exec = \__fn::get_site((object)['data'=> $data,'code'=>200],$link);
if($exec){
    echo "<img src=\"data:image/webp;base64,".base64_encode($exec)."\">";
}else{
    echo "<i>Something broken!</i>";
}