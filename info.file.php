<?php

$finfo = new finfo(FILEINFO_MIME);
if (!$finfo) return false;
echo $finfo->file('assets/images/clips.jpg');