<?php
require_once "autobase.php";
//Php >= 7.4
$zip = new \Manage\Archives();
// example 1 output folder.zip
//$zip->createZip('assets/example/files','assets/compressed/files.zip');
// example 2 output riza.zip
$zip->fname = 'json_data';

$zip->folder = "assets/zipunzip";

$zip->createZip("$zip->folder/$zip->fname",function($zip){
    $zip->password('12345');
    $zip->export("$zip->folder/safe/{$zip->fname}.zip");
    $zip->info([
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
    ]);
});

$zip->password('S$gjhs')->encryptZip("$zip->folder/safe/$zip->fname.zip",'12345');
$zip->openZip("$zip->folder/safe/$zip->fname.zip",'S$gjhs');

$zip->extractZip("$zip->folder/safe/$zip->fname.zip","$zip->folder/open/$zip->fname",'S$gjhs');