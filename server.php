<?php
require_once('autobase.php'); 
// your client must set response code 200 to access this script exp.\__fn::get_site(200....)
// set code , mime_type, text msg & location of temporary file
$code = 400; 
$mime = "text/html";
$text = "<i style=\"color:red\"><b>Bad Parameters</b> : The server attempted to execute incomplete data!</i>";
$save = false;
// init & set default bash & rule of binary name to use
// ex. ffmpeg, pngcrush
$file = realpath('.')."/assets/images/".uniqid();
$rule = isset($_POST['rule']) ? $_POST['rule'] : null;
$exec = isset($_POST['exec']) ? $_POST['exec'] : null;
$bash = "D:/.batch/bin/$rule.exe"; // window
$bash = "C:/Users/ditintelkam 3/$rule.exe"; // window
// $bash = "C:/Users/LENOVO/$rule.exe"; // window
//$bash = "/usr/local/bin/$rule"; // linux

if($rule == 'ffmpeg'){
    /* you must have ffmpeg binary app installed on your server
     * check here and installed based your basic or kernel system
     * https://www.johnvansickle.com/ffmpeg/ (recomanded for linux old system)
     * https://github.com/BtbN/FFmpeg-Builds/releases (all OS)
     */
    if($exec == 'img2webp' && isset($_FILES['file']['type'])){                 
        $exts = \__fn::mime2ext($_FILES['file']['type']);
        move_uploaded_file($_FILES['file']['tmp_name'],"$file.$exts");
        exec("\"$bash\" -i \"$file.$exts\" -c:v libwebp \"$file.webp\"");
        unlink("$file.$exts");
        // $code = 200; 
        // $mime = "image/webp";
        $file = "$file.webp";
        // $text = null;
        header("Content-Type:image/webp");
        // header("Content-Disposition: inline; name=\"{$_FILES['file']['name']}\"; filename=\"{$_FILES['file']['name']}.webp\"");
        print file_get_contents($file);
        unlink($file);
        die();
    }    
} 

if($rule == 'pngcrush'){
    /* you must have pngcrush binary app installed on your server
     * linux : available at all repositories type unix os
     * window : https://pmt.sourceforge.io/pngcrush/
     */ 
    $bash = "\"$bash\" -ow -rem allb -reduce"; // set yours
    if($exec == 'img2webp' && isset($_FILES['file']['type'])){    
        $exts = \__fn::mime2ext($_FILES['file']['type']);
        if($exts == 'png'){
            move_uploaded_file($_FILES['file']['tmp_name'],"$file.$exts");
            exec("$bash \"$file.$exts\"");
            $code = 200; 
            $mime = $_FILES['file']['type'];
            $file = $file.$exts;
            $text = null;
            header("Content-Type:image/png");
            print file_get_contents($file);
            unlink($file);
            die();
        }
    }
}

\__fn::send_response((object)[
    'time' => 'Asia/Jakarta',
    'code' => $code,
    'mime' => $mime,
    'file' => $file,
    'text' => $text,
    'save' => $save,
]); 