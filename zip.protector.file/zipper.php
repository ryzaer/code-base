<?php
require_once('../autoload.php');
include_once('conf.php');

// ZIPPER
$zip = new \Manage\ZipFile();
$zip->fname = isset($_GET['fname']) ? $_GET['fname'] : null;

$folder= ($zip->fname?"Folder $zip->fname Not Found!":"Format Query : ?fname=name_folder_to_zip");
if($zip->fname)
    foreach ($arr_base_dirs as $dir) {
        $zip->folder = "$dir/{$zip->fname}";
        if(is_dir($zip->folder))
            if($zip->fname!=='.' && $zip->fname!=='..' && is_dir($zip->folder)){
                $zip->create($zip->folder,function($z){
                    $z->password(md5($z->fname));
                    $z->export("{$z->folder}.zip");
                });
                \__fn::rm($zip->folder);
                $folder = "Converted into zip : $zip->fname removed!";
            }
    }

print $folder;


// recursive to slow
// foreach ($arr_base_dirs as $dir) {
//     if(is_dir($dir))
//         if ($dh = opendir($dir)){
//             while (($vars = readdir($dh)) !== false){
//                 $zip->fname = $vars;
//                 $zip->folder = "$dir/{$zip->fname}";
//                 if($zip->fname!=='.' && $zip->fname!=='..' && is_dir($zip->folder)){
//                     $folder[] = $zip->fname;
                    
//                     $zip->create($zip->folder,function($z){
//                         $z->password(md5($z->fname));
//                         $z->export("{$zip->folder}.zip");
//                     });
//                     // \__fn::rm($zip->folder);
//                 }
//             }
//             closedir($dh);
//         }
// }



