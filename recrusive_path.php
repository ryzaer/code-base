<?php
// panggil class recrusive
$dirs = 'assets';
$file = new \RecursiveIteratorIterator(
new \RecursiveDirectoryIterator($dirs),
\RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($file as $name){
    // skip file jika realpath gak ada cutomisasi
    $filePath = $name->getRealPath();
    //echo $name."<br>";
    //if($name->isFile())	
    //if($name->isDir())	
    if(preg_match('/\.(zip|bin)/',$filePath)){ echo $filePath ."<br>";}
}