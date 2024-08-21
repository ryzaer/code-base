<?php
function get_fileinfo($file){
    $arr['ctime'] = filectime($file); // created time                    
    $arr['mtime'] = filemtime($file); // modified time
    $arr['size'] = filesize($file); // size
    if($grp = getimagesize($file)){
        $arr['width'] = isset($grp[0]) ? $grp[0]:0;
        $arr['height'] = isset($grp[1]) ? $grp[1]:0;
        $arr['bits'] = isset($grp['bits']) ? $grp['bits'] : 8;
        $arr['channels'] = isset($grp['channels']) ? $grp['channels'] : 0;
        $arr['mime_type'] =  isset($grp['mime']) ? $grp['mime'] : '';
    }else{
        $arr['mime_type'] =  mime_content_type($file);
    }
    return $arr;
}