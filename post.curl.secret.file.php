<?php
require_once('autobase.php');
/* php 5++
 * example post curl pngcrush
 */
$file = realpath('.')."/assets/images/arini.jpg";
$bins = realpath('.')."/assets/sodium/data.bin";
$link = "http://localhost/__repository/code-base/server-secret.php";  

$result = \__fn::get_site([
    'name' => 'My latest single', 
    'description' => 'Check out my newest song',
    'binary' => $bins, 
    'picture' => $file, 
],$link);
// function __makeCurlFile($file){
//     $mime = mime_content_type($file);
//     $info = pathinfo($file);
//     $name = $info['basename'];
//     $output = new CURLFile($file, $mime, $name);
//     return $output;
// }

// $ch = curl_init($link);
// $photo = __makeCurlFile($file);
// $binary = __makeCurlFile($bins);
// $data = [
//     'name' => 'My latest single', 
//     'description' => 'Check out my newest song',
//     'binary' => $binary, 
//     'picture' => $photo, 
// ];
// curl_setopt($ch, CURLOPT_POST,1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
// $result = curl_exec($ch);
// if (curl_errno($ch)) {
//     $result = curl_error($ch);
// }
// curl_close ($ch);
header("Content-Type:text/json");
print $result;