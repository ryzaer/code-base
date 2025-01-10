<?php
require_once "autobase.php";
//Php >= 7.4
$zip = new \Manage\ZipFile();
// example 1 output folder.zip
//$zip->createZip('assets/example/files','assets/compressed/files.zip');
// example 2 output riza.zip
$zip->fname = 'json_data';
$zip->folder = "assets/zipunzip";
// set password
// $zip->passwd = null;
$zip->passwd = 'S$gjhs';
// adding info
$zip->adInfo = [
    "id"            => uniqid(),
    "title"         => "Project Title",
    "author"        => "John Doe",
    "description"   => "Project Description",
];
// adding files
$zip->adFile = [
    "assets/images/arini.jpg",
    "assets/tpsa.csv",
    "assets/tpsa.xml",
];
// create zip with password
// $zip->create("$zip->folder/$zip->fname",function($z){
//     $z->password($z->passwd);
//     $z->export("$z->folder/safe/{$z->fname}.zip");
//     $z->files($z->adFile);
//     $z->info($z->adInfo);
// });
// example show all content in zip file
// default output is json
// header("Content-Type:application/json");
// print $zip->open("$zip->folder/$zip->fname.zip",$zip->passwd);

// // example blob show video file
// $zip->stream("C:/htdocs/jin.zip",'jin/output1.ts');
// print $zip->open("C:/htdocs/jin.zip",null,'jin/output1.ts');


// example blob show image file 
header("Content-Type:image/jpeg");
print $zip->open("$zip->folder/safe/$zip->fname.zip",$zip->passwd,'arini.jpg');
// dont know this function works on php8 only
// $zip->stream("$zip->folder/safe/$zip->fname.zip",'arini.jpg',$zip->passwd);


// change zip password
// $zip->protect("$zip->folder/safe/$zip->fname.zip",'12345','S$gjhs');
// print $zip->open("$zip->folder/safe/$zip->fname.zip",'12345');

// $zip->extract("$zip->folder/safe/$zip->fname.zip","$zip->folder/open/$zip->fname",'S$gjhs');