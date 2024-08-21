<?php
include_once('autoload.php');
$srtFile = 'F:/Videos/THE LAST - NARUTO THE MOVIE.srt';
header('content-type:text/vtt');
print __fn::convert_srt_to_vtt($srtFile);