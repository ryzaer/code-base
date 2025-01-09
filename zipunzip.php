<?php
require_once "autobase.php";
//Php >= 7.4
$zip = new \Manage\ZipFile();
// example 1 output folder.zip
//$zip->createZip('assets/example/files','assets/compressed/files.zip');
// example 2 output riza.zip
$zip->fname = 'json_data';

$zip->folder = "assets/zipunzip";
$zip->passwd = 'S$gjhs';
$zip->adInfo = [
    "id"            => uniqid(),
    "code"          => "ktra-332",
    "title"         => "aksdjklj dlakjdkajd lakdjlaksdj",
    "author"        => "riza",
    "production"    => "kskjk kkajsdkj kajsdkjdk",
    "description"   => "klasjd;lfk ;asldkf;lask df'as;lfk;ldjsf lkjsdfklj sadfkljasdklfj dsfkl",
    "category"      => "ksadjflkjs, jhaskjdhf",
    "cast"          => "kasjdkf lkjasdfkj, kjahsdkjfh,kjadfkj",
    "genre"         => "kajs",
    "tags"          => "okloipo, kjahdkjsfh aksdfjhkjhjsadf, ahsdkfjh kasfjhkadjsfhkjsdhf djsk, ajsdfkjhadkjsfh",
];
$zip->adFile = [
    "assets/images/arini.jpg",
    "assets/tpsa.csv",
    "assets/tpsa.xml",
];

// create zip with password
$zip->create("$zip->folder/$zip->fname",function($z){
    $z->password($z->passwd);
    $z->export("$z->folder/safe/{$z->fname}.zip");
    $z->files($z->adFile);
    $z->info($z->adInfo);
});
// header("Content-Type:application/json");
// print $zip->open("$zip->folder/safe/$zip->fname.zip",$zip->passwd);

// change zip password
// $zip->protect("$zip->folder/safe/$zip->fname.zip",'12345','S$gjhs');
// print $zip->open("$zip->folder/safe/$zip->fname.zip",'12345');

// $zip->extractZip("$zip->folder/safe/$zip->fname.zip","$zip->folder/open/$zip->fname",'S$gjhs');