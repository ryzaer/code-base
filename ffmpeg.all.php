<?php
include_once('autobase.php');
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
    $folder = 'G:\@MYPROJECT\DATABASE_NAMA\videobind_02_jav\@shrinking_plan';
    //$folder = 'G:\@MYPROJECT\DATABASE_NAMA\videobind_02_jav\@shrinking_plan\@wait';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\videobind_03_jav\uncomprez';
    //$folder = 'G:\@Backup_WWW\asin_ebook\edited\vidBind\@ENTITY';
    //$folder = 'C:\Action!\Video'; 
    // $folder = "D:/riza-ttnt/Videos";
    $fname  = "TOTTE-027";
    $sname  = "$fname";
    //$x->mode('fast');  
    // $x->fps(25);  
    // $x->scale(720);  
    //$x->fixtimecut("-5","+5"); 
    //$x->moveto("$folder/$fname-proc.mp4")->save();   
    // $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
    $x->param("$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"], 
        ["18:06.860","19:28.142"],
        
        ["34:01.656","35:07.867"],
        ["37:19.231","39:09.663"],
        ["40:54.246","43:07.149"],
        ["46:50.931","53:35.342"],
        ["54:18.553","54:53.755"],
        ["01:00:52.741","01:01:39.607"],
        ["01:02:01.899","01:02:37.760"],
        ["01:03:13.153","01:06:11.155",true,"$fname-scane-%s.mp4"],
    ])->print();
});
