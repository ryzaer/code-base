<?php
require_once('../autoload.php');
include_once('conf.php');

$base_file_dir = $arr_base_dirs[0];
$name_file_req = isset($_GET['file']) ? $_GET['file'] : null;
$break=false;
foreach ($arr_base_dirs as $key => $value) {
    if(!$break && file_exists("$value/$name_file_req")){
        $base_file_dir = $value;
        $break=true;
    }
}

$zip = new \Manage\ZipFile();
$zip->dname = str_replace(".zip","",$name_file_req);
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
        $list[] = "<li><a href=\"./stream.php?file={$zip->dname}.zip&id={$val['name']}\">{$val['name']}</a></li>";
    }
    print "<ul>".implode("",$list)."</ul>";
    die();
}

// SHOW LIST
$list = [];
$dom = \__fn::basedirs($arr_base_dirs,'/\.zip/');
foreach ($dom as $file) {
    $name = basename($file);
    $list[] = "<li><a href=\"./stream.php?file=$name\">$name</a></li>";
}

print "<ol>".implode("",$list)."</ol>";

