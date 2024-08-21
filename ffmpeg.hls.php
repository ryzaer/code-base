<?php
include_once('autobase.php');
parse\ffmpeg::convert("file_video.mp4")->hls()->export("folder/filename");