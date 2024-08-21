<?php
require_once('autobase.php');
// example for header icon
//header('Content-Type:image/x-icon');
//echo file_get_contents($ifile);
//die();
$ifile = realpath('.')."/assets/example/files/arini";
// watermark
$imark = realpath('.')."/assets/example/files/pol.png";
// crop original pic based watermark size pic 
$handle = @getimagesize($imark);
$w = $handle[0];
$h = $handle[1];

// // php 7.4 have problem with gd-lib for png
// if(\__fn::mime2ext($handle['mime']) == 'png'){
  $url = "http://localhost/__classes/autoloader/server-pngcrush.php";  
  $mtd = new CURLFile($imark,$handle['mime']);
  $get = \__fn::get_site((object)['data'=> ["file" => $mtd,'exec'=>'img2webp']],$url);
// }
echo $get;die();
// another function to get mimetype file
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($ifile);

// Create a new SimpleImage object if png or jpg 
// undermaintain
if(\__fn::mime2ext($mime) == 'gif'){
  header("Content-Type:text/html");
  $gif = base64_encode(file_get_contents($ifile));
  print "<img src=\"data:image/gif;base64,$gif\">";
}else{
  $class = new claviska\imager();
  try { 
      // Magic! ✨
      $class
        ->fromFile($ifile)                        // load image.jpg
        ->autoOrient()                            // adjust orientation based on exif data
        //->resize(320, 420)                      // resize to 320x200 pixels
        //->flip('x')                             // flip horizontally
        //->colorize('darkblue')                  // tint dark blue
        //->border('red', 10)                     // add a 10 pixel black border
        ->thumbnail($w,$h,'top')              // can use as cropping (default:center)
        ->overlay($imark, 'bottom right',0.12)       // add a watermark image
        //->toFile('new-image.png', 'image/png')  // convert to PNG and save a copy to new-image.png
        ->toScreen();                             // output to the screen
      // And much more! 💪
  } catch(Exception $err) {
      // Handle errors
      var_dump($err);
    //echo $err->getMessage();
  }
}