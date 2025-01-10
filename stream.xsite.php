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

// // example blob show video file with logs
$name_vid = "{$zip->dname}-scane-01.mp4";
$zip->stream_logs("{$zip->fldir}.zip",$name_vid,md5($zip->dname));



