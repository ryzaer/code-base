<?php
include_once('autobase.php');
parse\ffmpeg::convert("lagoon.mp4")->export(".");
