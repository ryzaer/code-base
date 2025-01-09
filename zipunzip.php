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
$zip->create("$zip->folder/$zip->fname",function($z){
    $z->password($z->passwd);
    $z->export("$z->folder/safe/{$z->fname}.zip");
    $z->files($z->adFile);
    $z->info($z->adInfo);
});
// example show all content in zip file
// default output is json
// header("Content-Type:application/json");
// print $zip->open("$zip->folder/safe/$zip->fname.zip",$zip->passwd);

// // example blob show image file
header("Content-Type:image/jpeg");
print $zip->open("$zip->folder/safe/$zip->fname.zip",$zip->passwd,'arini.jpg');

// $zip = new ZipArchive();
// if ($zip->open("assets/zipunzip/safe/json_data.zip") === TRUE) {
//     // Locate the file inside the ZIP
//     $zip->setPassword('S$gjhs');
		
//     $index = $zip->locateName("arini.jpg");
//     var_dump($index);
//     if ($index !== false) {
//         // Open the file as a stream
//         $stream = $zip->getStream("arini.jpg");
//         if ($stream) {
//             // Read the file content in chunks and stream it
//             while (!feof($stream)) {
//                 echo fread($stream, 1024); // Read 1KB at a time
//             }
//             fclose($stream); // Close the stream
//         } else {
//             echo "Failed to open the file inside the ZIP.";
//         }
//     } else {
//         echo "File not found inside the ZIP.";
//     }
//     $zip->close(); // Close the ZIP archive
// } else {
//     echo "Failed to open the ZIP archive.";
// }


// change zip password
// $zip->protect("$zip->folder/safe/$zip->fname.zip",'12345','S$gjhs');
// print $zip->open("$zip->folder/safe/$zip->fname.zip",'12345');

// $zip->extractZip("$zip->folder/safe/$zip->fname.zip","$zip->folder/open/$zip->fname",'S$gjhs');