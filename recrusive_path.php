<?php
// panggil class recrusive
$file = new \RecursiveIteratorIterator(
new \RecursiveDirectoryIterator('assets'),
\RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($file as $name){
    // skip file jika realpath gak ada cutomisasi
    $filePath = $name->getRealPath();
    //echo $name."<br>";
    //if($name->isFile())	
    //if($name->isDir())	
    if(preg_match('/\.zip/',$filePath)){ echo $filePath ."<br>";}
}