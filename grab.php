<?php
require_once 'autobase.php';
$reqs['link'] = '';
$reqs['code'] = '';
$reqs['json'] = '';
$init = 0;
foreach ($_REQUEST as $key => $val) {
    if(isset($reqs[$key])) $reqs[$key] = $val; 
    $init++;
}
$bin = \__fn::get_site(...[$reqs['link'],$reqs['code'] ? abs($reqs['code']) : '',$reqs['json']]);
if($init && $bin){
    //$mim = mime_content_type($bin);
    header("Content-Type:text/plain");
    echo $bin;
}



// $host = "hpjav.tv";

// $response = file_get_contents("https://{$host}");

// $response = null;
// //if ( $fp = fsockopen("ssl://{$host}", 443, $errno, $errstr, 30) ) {
// if ( $fp = fopen("https://{$host}","r") ) {
//     $msg  = "GET /targetscript.php?variable=bilaada HTTP/1.1" . " ";
//     $msg .= 'Host: ' . $host . " ";
//     $msg .= 'Connection: close' . " ";
//     if ( fwrite($fp, $msg) ){
//         while ( !feof($fp) ) {
//             $response .= fgets($fp, 1024);
//         }
//     }
//     fclose($fp);
// // }

// echo $response;