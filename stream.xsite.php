<?php
require_once('autoload.php');
// $class = new getx\sites();

$zip = new \Manage\ZipFile();
$zip->dname = isset($_GET['id']) ? $_GET['id'] : "2767346";
$zip->fldir = "F:/.ssh/102301/{$zip->dname}";

// if(is_dir($zip->fldir)){
//     $zip->create($zip->fldir,function($z){
//         $z->password(md5($z->dname));
//         $z->export("{$z->fldir}.zip");
//         // $z->files($z->adFile);
//         // $z->info($z->adInfo);
//     });
//     \__fn::rm($zip->fldir);
// }

// if(file_exists("$zip->fldir.zip")){
//     if(!file_exists("$zip->fldir.json"))
//         file_put_contents("$zip->fldir.json",$zip->open("{$zip->fldir}.zip",md5($zip->dname)));

//     // header("Content-Type:application/json");
//     // print file_get_contents("$zip->fldir.json");
    
//     header("Content-Type:video/mp4");
//     // print $zip->open("{$zip->fldir}.zip",md5($zip->dname),"1178500-scane-02.mp4");
//     // dont know this function works on php 8 only [localhost:8030]
//     $zip->stream("{$zip->fldir}.zip","{$zip->dname}-scane-06.mp4",md5($zip->dname));
// }

// // example blob show video file
$temp_dir = "{$_SERVER['TEMP']}/".\__fn::create_device_id();
$name_vid = "{$zip->dname}-scane-01.mp4";
$json_vid = "logs.json";

is_dir($temp_dir) || mkdir($temp_dir, 0755, true);
file_exists("$temp_dir/$json_vid") || file_put_contents("$temp_dir/$json_vid",json_encode([$name_vid => time()]));

// check logs
$logs_vid = [];
foreach(json_decode(file_get_contents("$temp_dir/$json_vid"),true) as $k => $v){
    if((time() - $v) > 600){
        // remove file if reach limit time 60 sec
        \__fn::rm("$temp_dir/$k");
    }else{
        $logs_vid[$k] = $v;
    }
};
file_put_contents("$temp_dir/$json_vid",json_encode($logs_vid));

if(!file_exists("$temp_dir/$name_vid")){    
    $file_vid = $zip->open("{$zip->fldir}.zip",md5($zip->dname),$name_vid);
    file_put_contents("$temp_dir/$name_vid",$file_vid);
    // add history logs
    isset($logs_vid[$name_vid]) || file_put_contents("$temp_dir/$json_vid",json_encode(array_merge($logs_vid,[$name_vid => time()])));
}

\__fn::http_file_stream("$temp_dir/$name_vid");



