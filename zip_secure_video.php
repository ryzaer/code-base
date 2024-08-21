<?php
require_once "autobase.php";

$zipvideo = [
    "id"          => "19",
    "file_bin"    => "mabinogi1,mabinogi2",
];

$vid = new Manage\SecureVideo();
$vid->source("assets/videos",$zipvideo['file_bin']);
$vid->create();
$vid->read();