<?php
// function for recursive remove
function rm($src){    
    if(!function_exists('__rm_file')){
        function __rm_file($src) {
            chmod($src,0755);
            @unlink($src);						
        }
    }
    if(!function_exists('__rm_dirs')){
        function __rm_dirs($src) {
            chmod($src,0755);     
            $dir = opendir( $src ); 		
            while( false !== ( $file = readdir( $dir ) ) ) { 
                if( $file != '.' && $file != '..' ) { 
                    if(is_dir("$src/$file")) 
                        __rm_dirs("$src/$file");                     
                    if(is_file("$src/$file"))
                        __rm_file("$src/$file");
                } 
            }			
            closedir($dir);
            @rmdir($src);
        }
    }
    if(is_dir($src))   
        __rm_dirs($src); 
    if(is_file($src))
        __rm_file($src);
}