<?php
spl_autoload_register(function($class) {  
    $root  = preg_replace('~[\\\]~','/',__DIR__);
    $file  = $root."/classes";
    $find  = preg_split('~[\\\]~',$class);   
    
    $make  = $find[count($find)-1];      
    unset($find[count($find)-1]);   
    $newcc = implode('/',$find); 
    $space = $file.($newcc ? '/'.$newcc : null);
    
    if(!is_dir($space)){
        mkdir($space, 0755, true);
    }
    
    if(!file_exists("$space/$make.php")){
        file_put_contents("$space/$make.php","<?php".($find? "\nnamespace ".implode('\\',$find).";" : null)."\n\nclass $make {\n\n\tprivate \$static;\n\n\tpublic function __construct(\$args=[]){\n\t\t// start here ..\n\t}\n}");
		chmod("$space/$make.php", 0644); 
    }

    if(!function_exists('check_web_root')){
        function check_web_root(){
            $params = explode("/",$_SERVER['SCRIPT_NAME']);
            unset($params[count($params)-1]);
            return implode("/",array_filter($params));
        }
    }

    /* SET DEFAULT CONSTANTS */
    if(!defined("APP_ROOT_URL")){
        $reff = explode("/",$root);
        $reff = $reff[count($reff)-1];

        $deff = str_replace('/'.$reff,"",check_web_root());
        define('APP_ROOT_URL',$deff); 
        
        $reff = str_replace('/'.$reff,"",$root);
        define('APP_ROOT_DIR',$reff); 
    }

    if(!function_exists('check_parse_uri')){
        function check_parse_uri(){
            $checks = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            preg_match_all('/\/\//is',$checks,$matchs);
            if($matchs[0]){
                header("Refresh:0; url={$_SERVER['REQUEST_SCHEME']}://".preg_replace('/\/+/','/',$checks));
                die();
            }       
        }
    }
    check_parse_uri();
    require_once "$space/$make.php";       
    // run access API license
});
