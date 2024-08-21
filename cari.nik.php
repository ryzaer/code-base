<?php 
require_once 'autobase.php';
$nik = isset($_GET['nik']) && $_GET['nik'] ? $_GET['nik'] : null;

function cari_attacment_nik($dirs,$nik){
    // show main attachment folders
    $folder = \__fn::basedirs($dirs);
    $result = [];
    foreach ($folder as $part) {
        if($path = \__fn::basedirs($part))
            foreach ($path as $dir) {
                if(is_dir("$dir/$nik")){            
                    $src = \__fn::basedirs("$dir/$nik",'/\.(jp(e?)g|png|webp)/s',true);
                    foreach ($src as $k => $v) {
                        $mdf = [];
                        foreach ($v as $t) {
                            $mdf[$t] = \__fn::get_fileinfo("$k/$t");
                        }
                        $result[$k] = $mdf;
                    }
                }
            }        
    }
    return $result;
}
// if(is_dir($src)){
//     $img = [];
//     foreach ([
//         "akta",
//         "foto",
//         "kk",
//         "ktp"
//     ] as $file) {
//         $base = null;
//         if(file_exists("$src/$file.jpg"))
//             if(!file_exists("$src/$file.webp"))
//                 shell_exec("ffmpeg -i \"$src/$file.jpg\" -c libwebp -quality 50 \"$src/$file.webp\"");
//         if(file_exists("$src/$file.webp"))
//             $base = "data:image/webp;base64,".base64_encode(file_get_contents("$src/$file.webp"));
//         if($base){
//             $img[] = "<span><p>$src/$file.webp</p><img style=\"width:200;margin:5px\" src=\"$base\"/></span>";
//         }
//     }
//     if($img)
//         print implode("",$img);
// }
$result = cari_attacment_nik([
    'D:/.ssh'
],$nik);
// header('Content-Type:text/json');
var_dump($result);