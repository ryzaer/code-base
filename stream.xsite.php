<?php
require_once('autoload.php');

$base_file_dir = 'K:/.ssh/102301';

$zip = new \Manage\ZipFile();
$zip->dname = str_replace(".zip","", isset($_GET['file']) ? $_GET['file'] : null);
$zip->fldir = $base_file_dir . "/{$zip->dname}";

$zip->getVid = isset($_GET['id']) ? $_GET['id'] : null;

// STREAM A VIDEO IN ZIP
if($zip->getVid){
    $zip->stream_logs("{$zip->fldir}.zip",$zip->getVid,md5($zip->dname));
    die();
}

// SHOW LIST OF VIDEOS IN ZIP
if(file_exists("$zip->fldir.zip")){
    if(!file_exists("$zip->fldir.json"))
        file_put_contents("$zip->fldir.json",$zip->open("{$zip->fldir}.zip",md5($zip->dname)));
    $list=[];
    foreach(json_decode(file_get_contents("$zip->fldir.json"),true) as $var => $val){
        $list[] = "<li><a href=\"./stream.xsite.php?file={$zip->dname}.zip&id={$val['name']}\">{$val['name']}</a></li>";
    }
    print "<ul>".implode("",$list)."</ul>";
    die();
}

// ZIPPER
// if(is_dir($zip->fldir)){
//     $zip->create($zip->fldir,function($z){
//         $z->password(md5($z->dname));
//         $z->export("{$z->fldir}.zip");
//         // $z->files($z->adFile);
//         // $z->info($z->adInfo);
//     });
//     \__fn::rm($zip->fldir);
// }

// SHOW LIST
$dom = \__fn::basedirs($base_file_dir,'/\.zip/');
$list = [];
foreach ($dom as $file) {
    $name = basename($file);
    $list[] = "<li><a href=\"./stream.xsite.php?file=$name\">$name</a></li>";
}
print "<ol>".implode("",$list)."</ol>";

