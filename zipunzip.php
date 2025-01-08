<?php
require_once "autobase.php";
//Php >= 7.4
$zip = new \Manage\Archives();
// example 1 output folder.zip
//$zip->createZip('assets/example/files','assets/compressed/files.zip');
// example 2 output riza.zip
$zip->fname = 'json_data';

$zip->folder = "assets/zipunzip";
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

$zip->createZip("$zip->folder/$zip->fname",function($z){
    $z->password('S$gjhs');
    $z->export("$z->folder/safe/{$z->fname}.zip");
    $z->info($z->adInfo);
});

$zip->openZip("$zip->folder/safe/$zip->fname.zip",'S$gjhs');

// $zip->password('S$gjhs')->encryptZip("$zip->folder/safe/$zip->fname.zip",'12345');

// $zip->extractZip("$zip->folder/safe/$zip->fname.zip","$zip->folder/open/$zip->fname",'S$gjhs');