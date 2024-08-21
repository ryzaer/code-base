<?php
require_once "autobase.php";
//Php >= 7.4
$zip = new Manage\Archives();
// example 1 output folder.zip
//$zip->createZip('assets/example/files','assets/compressed/files.zip');
// example 2 output riza.zip
$zip->fname = 'female';

$zip->createZip("assets/example/$zip->fname",function($zip){
    $zip->password('12345');
    $zip->export("assets/rahasia/{$zip->fname}.bin");
    $zip->info([
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

$zip->password('S$gjhs')->encryptZip("assets/rahasia/$zip->fname.bin",'12345');
$zip->openZip("assets/rahasia/$zip->fname.bin",'S$gjhs');
//$zip->extractZip("assets/rahasia/$zip->fname.bin","assets/extracted/$zip->fname",$zip->newPwd);