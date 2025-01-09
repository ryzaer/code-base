<?php
require_once('autoload.php');
// $class = new getx\sites();
//echo __fn::app_location_host("test");
//echo __fn::get_site('https://pontianak.tribunnews.com/polda-kalbar');

$zip = new \Manage\ZipFile();
$zip->dname = isset($_GET['id']) ? $_GET['id'] : null;
$zip->fldir = "F:/.ssh/102301/{$zip->dname}";
if(is_dir($zip->fldir)){
    $zip->create($zip->fldir,function($z){
        $z->password(md5($z->dname));
        $z->export("{$z->fldir}.zip");
        // $z->files($z->adFile);
        // $z->info($z->adInfo);
    });
    \__fn::rm($zip->fldir);
}

if(file_exists("$zip->fldir.zip")){
    if(!file_exists("$zip->fldir.json"))
        file_put_contents("$zip->fldir.json",$zip->open("{$zip->fldir}.zip",md5($zip->dname)));

    // header("Content-Type:application/json");
    // print file_get_contents("$zip->fldir.json");
    
    // // print md5($zip->dname);
    header("Content-Type:video/mp4");
    print $zip->open("{$zip->fldir}.zip",md5($zip->dname),"1178500-scane-02.mp4");
}
