<?php
include_once('autoload.php');
$file_path = 'C:\htdocs\jin.mp4';
 //echo mime_content_type($file_path);
__fn::http_file_stream($file_path);