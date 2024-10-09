<?php
include_once('autoload.php');
$file_path = null;
if(isset($_GET['fname']) && $_GET['fname']){
    $file_path = urldecode($_GET['fname']);
    //echo mime_content_type($file_path);
}
__fn::http_file_stream($file_path);
