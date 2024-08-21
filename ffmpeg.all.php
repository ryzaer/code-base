<?php
include_once('autoload.php');
// example sintax
// parse\ffmpeg::convert( your_object [optional & only at first param], callback(x) );
// $x->obj (if has object)
// $x->param($param[0],$param[1])->split(...$split)->moveto($param[2])->unlink()->print(save[false/true/(string location)])
// wait for code
// $x->quality(); default Medium
// $x->copy(); split time will delay 1.5 sec
// $x->codec();
// $x->cmdtxt();
// 45:10.301 
// example 
// for hls parse\ffmpeg::convert("file_video.mp4")->hls()->export("folder/filename");
//
parse\ffmpeg::convert(function($x){
    $folder = 'E:\@MYPROJECT\DATABASE_NAMA\videobind_02_jav\@shrinking_plan';
    //$folder = 'E:\@MYPROJECT\DATABASE_NAMA\videobind_02_jav\@shrinking_plan\@wait';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\videobind_03_jav\uncomprez';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\vidBind\@ENTITY';
    //$folder = 'C:\Action!\Video'; 
    $folder = "D:/riza-ttnt/Videos";
    $fname  = "Beauty and the Beast 2017";
    $sname  = "$fname";
    //$x->mode('fast');  
    //$x->fps(25);  
    //$x->scale(720);  
    //$x->fixtimecut("-5","+5"); 
    //$x->moveto("$folder/$fname-proc.mp4")->save();   
    $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"],  
        ["35:53.525","37:55.024",true,"$fname-scane-%s.mp4"],
        ["01:07:29.385","01:08:19.377"],
        ["01:10:33.000","01:11:43.314"],
        ["01:13:23.703","01:14:35.497",true,"$fname-scane-%s.mp4"],
        ["01:22:03.343","01:23:05.981"],
        ["01:23:19.361","01:24:26.122",true,"$fname-scane-%s.mp4"],
        ["01:54:19.091","01:57:24.896",true,"$fname-scane-%s.mp4"],
        ["01:59:35.291","01:59:53.683"],
        ["02:01:28.144","02:05:46.278",true,"$fname-scane-%s.mp4"],
        ["02:10:33.149","02:11:51.425",true,"$fname-scane-%s.mp4"],
        
    ])->print();
});
