<?php
include_once('autoload.php');
parse\ffmpeg::convert("file_video.mp4")->hls()->export("folder/filename");