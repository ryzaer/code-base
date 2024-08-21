<?php 
// FILE GET CONTENT UNTUK MANIPULASI STRING
// $test = preg_replace(['/<\?php(.+)function\(/','/\/\/\s+.+\n|\/\*.+\*\//Uis'],'',file_get_contents('content.php'));
// $test = preg_replace('/\n+/is',"\n",$test);
$time = microtime(true);
$path = 'fopen.anonim.func.php';
$name = 'fopen_anonim';
$regx = '/^<\?php(\n+|\t+|\s+)function\(/m';
chmod($path,0644);
$node = fopen($path,"r");
$text = null;
$nums = 0;
while($strs = fread($node,250)){
    if($nums==0){
        preg_match($regx,$strs,$check);
        if($check){
            $strs = preg_replace($regx,'',$strs);
            $text.= "<?php \n// replacing function from $path\n\$$name = function($strs";
            // ftruncate($node, 0); hapus data file stream
            // rewind($node);
        }else{
            $text.= $strs;
        }
    }else{
        $text.= $strs;
    }
    $nums++;
}
//$text = fread($node,filesize($path));
$path = "$name.php";
file_put_contents($path,$text);

fclose($node);

// jika filter aktif maka source path juga beda
include_once $path;
print $fopen_anonim();
print "<br> execution time : ". number_format((microtime(true) - $time),5);

// // yeah include will appears
// include_once($path);

// $node = fopen($path,"w");
// ftruncate($node, 0);
// rewind($node);
// fwrite($node,$text);
// fclose($node);