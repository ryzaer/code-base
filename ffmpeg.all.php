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
    $fname  = "HND-748";
    $sname  = "$fname";
    //$x->mode('fast');  
    // $x->fps(25);  
    // $x->scale(720);  
    //$x->fixtimecut("-5","+5"); 
    //$x->moveto("$folder/$fname-proc.mp4")->save();   
    $x->param("$folder/$sname.mp4","$fname-cut.mp4")->split([
        // dont remove this example 
        //["00:00.000","00:00.000",true,"$fname-scane-%s.mp4"], 
        
        ["18:55.684","19:25.180"],
        
        ["21:59.640","23:21.871"],
        ["30:31.300","31:25.486",true,"$fname-scane-%s.mp4"],
        ["34:31.063","35:24.432"],
        ["37:16.646","39:00.549",true,"$fname-scane-%s.mp4"],
        ["46:10.904","47:24.635"],
        ["48:33.605","51:11.040",true,"$fname-scane-%s.mp4"],
        ["01:18:17.719","01:19:17.953",true,"$fname-scane-%s.mp4"],
        ["01:22:35.030","01:28:31.252",true,"$fname-scane-%s.mp4"],
        ["01:30:27.706","01:32:48.819"],
        ["01:34:23.409","01:35:43.651"],
        ["01:38:00.338","01:39:17.010",true,"$fname-scane-%s.mp4"],
        ["01:41:32.312","01:44:10.034"],
        ["01:45:47.960","01:46:18.588"],
        ["01:47:42.079","01:48:36.707",true,"$fname-scane-%s.mp4"],
        ["01:53:21.057","01:56:42.796",true,"$fname-scane-%s.mp4"],
    ])->print();
});
