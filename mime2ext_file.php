<?php
require_once 'autobase.php';
$fn = 'assets/videos/hls.example/play11.ts';
$fn = 'assets/rahasia/arini.bin';
$fn = 'F:\Videos\INDOT.mkv';
$fi = new finfo(FILEINFO_MIME_TYPE);
$mm = $fi->buffer(file_get_contents($fn));
echo $mm;
var_dump(\__fn::mime2ext($mm));